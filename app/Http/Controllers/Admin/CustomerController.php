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
     * Danh sách khách hàng (chỉ hiển thị tài khoản khách hàng)
     */
    public function index(Request $request)
    {
        $staffRoleNames = ['Super Admin', 'Admin', 'Staff'];

        // Chỉ hiển thị các tài khoản là khách hàng (không thuộc Super Admin, Admin, Staff)
        $query = User::whereDoesntHave('roles', function ($q) use ($staffRoleNames) {
            $q->whereIn('name', $staffRoleNames);
        })->whereNotIn('role', ['admin', 'staff', 'Super Admin', 'Admin', 'Staff']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Form tạo khách hàng mới
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Lưu khách hàng mới
     */
    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('users', 'public');
            $validated['avatar'] = 'storage/' . $path;
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'Customer';

        $user = User::create($validated);
        $user->assignRole('Customer');

        return redirect()->route('admin.customers.index')->with('success', 'Thêm mới khách hàng thành công!');
    }

    /**
     * Chi tiết khách hàng (Thông tin cá nhân, địa chỉ, đơn hàng, ngân hàng...)
     */
    public function show($id)
    {
        $customer = User::withTrashed()->with(['addresses', 'carts.items.product', 'carts.items.variant'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Form chỉnh sửa thông tin khách hàng
     */
    public function edit($id)
    {
        $staffRoleNames = ['Super Admin', 'Admin', 'Staff'];
        $customer = User::whereDoesntHave('roles', function ($q) use ($staffRoleNames) {
            $q->whereIn('name', $staffRoleNames);
        })->whereNotIn('role', ['admin', 'staff', 'Super Admin', 'Admin', 'Staff'])->findOrFail($id);

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Cập nhật thông tin khách hàng
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = User::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($customer->avatar) {
                $oldPath = str_replace('storage/', '', $customer->avatar);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('avatar')->store('users', 'public');
            $validated['avatar'] = 'storage/' . $path;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['role']); // Khách hàng luôn giữ vai trò Customer

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật thông tin khách hàng thành công!');
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

        return redirect()->route('admin.customers.index')->with('success', 'Đã chuyển tài khoản khách hàng vào thùng rác!');
    }

    /**
     * Thùng rác tài khoản khách hàng
     */
    public function trash(Request $request)
    {
        $staffRoleNames = ['Super Admin', 'Admin', 'Staff'];

        $query = User::onlyTrashed()->whereDoesntHave('roles', function ($q) use ($staffRoleNames) {
            $q->whereIn('name', $staffRoleNames);
        })->whereNotIn('role', ['admin', 'staff', 'Super Admin', 'Admin', 'Staff']);

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

        return redirect()->route('admin.customers.trash')->with('success', 'Khôi phục tài khoản khách hàng thành công!');
    }

    /**
     * Xóa vĩnh viễn (Xóa cứng)
     */
    public function forceDelete($id)
    {
        $customer = User::onlyTrashed()->findOrFail($id);

        if ($customer->avatar) {
            $oldPath = str_replace('storage/', '', $customer->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $customer->forceDelete();

        return redirect()->route('admin.customers.trash')->with('success', 'Xóa vĩnh viễn tài khoản khách hàng thành công!');
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
}
