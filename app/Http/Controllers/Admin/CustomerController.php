<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\Customer\StoreCustomerRequest;
use App\Http\Requests\Admin\Customer\UpdateCustomerRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Danh sách người dùng
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();
        if (auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Super Admin')) {
            $roles = $roles->filter(fn($r) => in_array($r->name, ['Customer', 'Staff']));
        }

        return view('admin.customers.index', compact('customers', 'roles'));
    }

    /**
     * Form tạo người dùng mới
     */
    public function create()
    {
        $roles = Role::all();
        if (auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Super Admin')) {
            $roles = $roles->filter(fn($r) => in_array($r->name, ['Customer', 'Staff']));
        }
        return view('admin.customers.create', compact('roles'));
    }

    /**
     * Lưu người dùng mới
     */
    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('users', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        if (auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Super Admin')) {
            if (in_array($validated['role'], ['Admin', 'Super Admin'])) {
                return back()->with('error', 'Bạn không có quyền tạo tài khoản với vai trò này!')->withInput();
            }
        }

        $user = User::create($validated);
        
        if (auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
            $user->assignRole($validated['role']);
        } else {
            $user->assignRole('Customer');
        }

        return redirect()->route('admin.customers.index')->with('success', 'Thêm mới người dùng thành công!');
    }

    /**
     * Chi tiết người dùng (Thông tin cá nhân, địa chỉ, đơn hàng, ngân hàng...)
     */
    public function show($id)
    {
        $customer = User::withTrashed()->with(['addresses', 'carts.items.product', 'carts.items.variant'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Form chỉnh sửa thông tin người dùng
     */
    public function edit($id)
    {
        $customer = User::findOrFail($id);
        $roles = Role::all();
        if (auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Super Admin')) {
            $roles = $roles->filter(fn($r) => in_array($r->name, ['Customer', 'Staff']));
        }
        return view('admin.customers.edit', compact('customer', 'roles'));
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = User::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($customer->avatar) {
                Storage::disk('public')->delete($customer->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('users', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Super Admin')) {
            if ($customer->hasAnyRole(['Super Admin', 'Admin'])) {
                unset($validated['role']); // Cannot change role of Admins
            } elseif (isset($validated['role']) && in_array($validated['role'], ['Admin', 'Super Admin'])) {
                return back()->with('error', 'Bạn không có quyền cấp vai trò này!')->withInput();
            }
        }

        if (!auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
            unset($validated['role']);
        }

        $customer->update($validated);

        if (isset($validated['role'])) {
            $customer->syncRoles([$validated['role']]);
        }

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật thông tin người dùng thành công!');
    }

    /**
     * Xóa mềm người dùng (Soft Delete)
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);

        if ($customer->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Đã chuyển tài khoản người dùng vào thùng rác!');
    }

    /**
     * Thùng rác tài khoản (Danh sách tài khoản đã xóa mềm)
     */
    public function trash(Request $request)
    {
        $query = User::onlyTrashed();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $trashed = $query->latest('deleted_at')->paginate(10)->withQueryString();

        return view('admin.customers.trash', compact('trashed'));
    }

    /**
     * Khôi phục tài khoản từ thùng rác
     */
    public function restore($id)
    {
        $customer = User::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('admin.customers.trash')->with('success', 'Khôi phục tài khoản thành công!');
    }

    /**
     * Xóa vĩnh viễn (Xóa cứng)
     */
    public function forceDelete($id)
    {
        $customer = User::onlyTrashed()->findOrFail($id);

        if ($customer->avatar) {
            Storage::disk('public')->delete($customer->avatar);
        }

        $customer->forceDelete();

        return redirect()->route('admin.customers.trash')->with('success', 'Xóa vĩnh viễn tài khoản thành công!');
    }

    /**
     * Khóa / Mở khóa tài khoản & Cập nhật lý do
     */
    public function toggleStatus(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        if ($customer->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể khóa tài khoản của chính mình!');
        }

        if ($customer->status === 'active') {
            $customer->status = 'locked';
            $customer->reason_lock = $request->input('reason_lock', 'Vi phạm quy định của hệ thống.');
            $message = 'Đã khóa tài khoản thành công!';
        } else {
            $customer->status = 'active';
            $customer->reason_lock = null;
            $message = 'Đã mở khóa tài khoản thành công!';
        }

        $customer->save();

        return back()->with('success', $message);
    }

    /**
     * Cập nhật vai trò (chỉ Super Admin)
     */
    public function updateRole(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) {
            return back()->with('error', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $customer = User::findOrFail($id);
        
        if ($customer->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự thay đổi vai trò của chính mình!');
        }

        if (auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Super Admin')) {
            if ($customer->hasAnyRole(['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền thay đổi vai trò của Quản trị viên khác!');
            }
            if (in_array($request->role, ['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền cấp vai trò này!');
            }
        }

        $request->validate(['role' => 'required|exists:roles,name']);
        
        $customer->syncRoles([$request->role]);

        return back()->with('success', 'Đã cập nhật vai trò thành công!');
    }
}
