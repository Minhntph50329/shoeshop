<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\Staff\StoreStaffRequest;
use App\Http\Requests\Admin\Staff\UpdateStaffRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Danh sách tài khoản Super Admin, Admin, Nhân viên
     */
    public function index(Request $request)
    {
        $staffRoleNames = ['Super Admin', 'Admin', 'Staff'];

        $query = User::where(function ($q) use ($staffRoleNames) {
            $q->whereHas('roles', function ($sub) use ($staffRoleNames) {
                $sub->whereIn('name', $staffRoleNames);
            })->orWhereIn('role', ['admin', 'staff', 'Super Admin', 'Admin', 'Staff']);
        });

        // Tìm kiếm theo từ khóa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Lọc theo vai trò
        if ($request->filled('role')) {
            $roleFilter = $request->role;
            $query->where(function ($q) use ($roleFilter) {
                $q->whereHas('roles', function ($sub) use ($roleFilter) {
                    $sub->where('name', $roleFilter);
                })->orWhere('role', $roleFilter);
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staffs = $query->latest()->paginate(10)->withQueryString();

        $currentUser = auth()->user();

        // Danh sách vai trò có thể chọn theo quyền hạn
        if ($currentUser->hasRole('Super Admin')) {
            $roles = Role::whereIn('name', ['Super Admin', 'Admin', 'Staff'])->get();
        } elseif ($currentUser->hasRole('Admin')) {
            $roles = Role::whereIn('name', ['Staff'])->get();
        } else {
            $roles = collect([]); // Nhân viên không có quyền chỉnh vai trò
        }

        return view('admin.staffs.index', compact('staffs', 'roles'));
    }

    /**
     * Form tạo nhân sự mới
     */
    public function create()
    {
        $currentUser = auth()->user();

        // Nhân viên không được quyền thêm mới
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return redirect()->route('admin.staffs.index')->with('error', 'Nhân viên không có quyền tạo tài khoản!');
        }

        if ($currentUser->hasRole('Super Admin')) {
            $roles = Role::whereIn('name', ['Super Admin', 'Admin', 'Staff'])->get();
        } else {
            // Admin chỉ được tạo nhân viên Staff
            $roles = Role::where('name', 'Staff')->get();
        }

        return view('admin.staffs.create', compact('roles'));
    }

    /**
     * Lưu nhân sự mới
     */
    public function store(StoreStaffRequest $request)
    {
        $currentUser = auth()->user();

        // Nhân viên không được chỉnh/tạo gì hết
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return redirect()->route('admin.staffs.index')->with('error', 'Nhân viên không có quyền thêm tài khoản!');
        }

        $validated = $request->validated();

        // Kiểm tra quyền cấp vai trò
        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if (in_array($validated['role'], ['Admin', 'Super Admin'])) {
                return back()->with('error', 'Bạn không có quyền tạo tài khoản với vai trò Quản trị viên!')->withInput();
            }
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('users', 'public');
            $validated['avatar'] = 'storage/' . $path;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.staffs.index')->with('success', 'Thêm mới nhân viên thành công!');
    }

    /**
     * Xem thông tin chi tiết nhân viên
     */
    public function show($id)
    {
        $staff = User::withTrashed()->with(['addresses', 'carts.items.product', 'carts.items.variant'])->findOrFail($id);
        return view('admin.staffs.show', compact('staff'));
    }

    /**
     * Form chỉnh sửa thông tin nhân viên
     */
    public function edit($id)
    {
        $currentUser = auth()->user();
        $staff = User::findOrFail($id);
        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        // 1. Nhân viên không được chỉnh gì hết
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return redirect()->route('admin.staffs.index')->with('error', 'Nhân viên không có quyền chỉnh sửa!');
        }

        // 2. Admin không được chỉnh Super Admin
        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if ($targetRole === 'Super Admin') {
                return redirect()->route('admin.staffs.index')->with('error', 'Bạn không có quyền chỉnh sửa tài khoản Super Admin!');
            }
        }

        // Chuẩn bị danh sách roles có thể gán
        if ($currentUser->hasRole('Super Admin')) {
            $roles = Role::whereIn('name', ['Super Admin', 'Admin', 'Staff'])->get();
        } elseif ($currentUser->hasRole('Admin')) {
            // Admin chỉ được chỉnh quyền của nhân viên
            $roles = Role::where('name', 'Staff')->get();
        } else {
            $roles = collect([]);
        }

        return view('admin.staffs.edit', compact('staff', 'roles'));
    }

    /**
     * Cập nhật thông tin nhân viên
     */
    public function update(UpdateStaffRequest $request, $id)
    {
        $currentUser = auth()->user();
        $staff = User::findOrFail($id);
        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        // 1. Nhân viên không được chỉnh gì hết
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return redirect()->route('admin.staffs.index')->with('error', 'Nhân viên không có quyền chỉnh sửa!');
        }

        $validated = $request->validated();

        // 2. Admin: ko được chỉnh quyền của admin khác và không được sửa Super Admin
        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if ($targetRole === 'Super Admin') {
                return redirect()->route('admin.staffs.index')->with('error', 'Bạn không có quyền chỉnh sửa tài khoản Super Admin!');
            }
            if ($targetRole === 'Admin' && $staff->id !== $currentUser->id) {
                // Admin khác -> Không được đổi quyền
                unset($validated['role']);
            }
            // Không được cấp quyền Admin hoặc Super Admin
            if (isset($validated['role']) && in_array($validated['role'], ['Admin', 'Super Admin'])) {
                return back()->with('error', 'Bạn không có quyền cấp vai trò Quản trị viên!')->withInput();
            }
        }

        if ($request->hasFile('avatar')) {
            if ($staff->avatar) {
                $oldPath = str_replace('storage/', '', $staff->avatar);
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

        // Xử lý cập nhật role
        $roleToAssign = $validated['role'] ?? null;

        $staff->update($validated);

        if ($roleToAssign) {
            $staff->syncRoles([$roleToAssign]);
        }

        return redirect()->route('admin.staffs.index')->with('success', 'Cập nhật thông tin nhân viên thành công!');
    }

    /**
     * Cập nhật nhanh vai trò
     * Super Admin: chỉnh sửa tất cả các vai trò của Admin và Nhân viên
     * Admin: chỉnh quyền của Nhân viên, ko được chỉnh quyền của admin khác
     * Nhân viên: không được chỉnh gì hết
     */
    public function updateRole(Request $request, $id)
    {
        $currentUser = auth()->user();

        // 1. Nhân viên không có quyền chỉnh gì hết
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return back()->with('error', 'Nhân viên không có quyền chỉnh sửa vai trò!');
        }

        $staff = User::findOrFail($id);

        // Không tự thay đổi vai trò của chính mình
        if ($staff->id === $currentUser->id) {
            return back()->with('error', 'Bạn không thể tự thay đổi vai trò của chính mình!');
        }

        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        // 2. Admin: chỉ chỉnh quyền của nhân viên, ko được chỉnh quyền của admin khác & super admin
        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if (in_array($targetRole, ['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền thay đổi vai trò của Quản trị viên!');
            }
            if (in_array($request->role, ['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền cấp vai trò Quản trị viên!');
            }
        }

        // 3. Super Admin có quyền chỉnh sửa tất cả vai trò của Admin và Nhân viên
        $request->validate(['role' => 'required|exists:roles,name']);

        $staff->role = $request->role;
        $staff->save();
        $staff->syncRoles([$request->role]);

        return back()->with('success', 'Đã cập nhật vai trò nhân sự thành công!');
    }

    /**
     * Khóa / Mở khóa tài khoản
     */
    public function toggleStatus(Request $request, $id)
    {
        $currentUser = auth()->user();

        // Nhân viên không có quyền
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return back()->with('error', 'Nhân viên không có quyền thực hiện thao tác này!');
        }

        $staff = User::findOrFail($id);

        if ($staff->id === $currentUser->id) {
            return back()->with('error', 'Bạn không thể khóa tài khoản của chính mình!');
        }

        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        // Admin không được khóa Admin khác và Super Admin
        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if (in_array($targetRole, ['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền khóa tài khoản của Quản trị viên khác!');
            }
        }

        if ($staff->status === 'active') {
            $staff->status = 'locked';
            $staff->reason_lock = $request->input('reason_lock', 'Bị tạm khóa bởi ban quản trị.');
            $message = 'Đã khóa tài khoản thành công!';
        } else {
            $staff->status = 'active';
            $staff->reason_lock = null;
            $message = 'Đã mở khóa tài khoản thành công!';
        }

        $staff->save();

        return back()->with('success', $message);
    }

    /**
     * Xóa mềm nhân viên (Soft Delete)
     */
    public function destroy($id)
    {
        $currentUser = auth()->user();

        // Nhân viên không có quyền xóa
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return back()->with('error', 'Nhân viên không có quyền xóa tài khoản!');
        }

        $staff = User::findOrFail($id);

        if ($staff->id === $currentUser->id) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
        }

        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        // Admin không được xóa Admin khác và Super Admin
        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if (in_array($targetRole, ['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền xóa tài khoản Quản trị viên!');
            }
        }

        $staff->delete();

        return redirect()->route('admin.staffs.index')->with('success', 'Đã chuyển tài khoản nhân sự vào thùng rác!');
    }

    /**
     * Thùng rác tài khoản nhân sự
     */
    public function trash(Request $request)
    {
        $currentUser = auth()->user();

        // Nhân viên không có quyền xem thùng rác
        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return redirect()->route('admin.staffs.index')->with('error', 'Nhân viên không có quyền truy cập thùng rác!');
        }

        $staffRoleNames = ['Super Admin', 'Admin', 'Staff'];

        $query = User::onlyTrashed()->where(function ($q) use ($staffRoleNames) {
            $q->whereHas('roles', function ($sub) use ($staffRoleNames) {
                $sub->whereIn('name', $staffRoleNames);
            })->orWhereIn('role', ['admin', 'staff', 'Super Admin', 'Admin', 'Staff']);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $trashed = $query->latest('deleted_at')->paginate(10)->withQueryString();

        return view('admin.staffs.trash', compact('trashed'));
    }

    /**
     * Khôi phục tài khoản nhân sự
     */
    public function restore($id)
    {
        $currentUser = auth()->user();

        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return back()->with('error', 'Nhân viên không có quyền khôi phục tài khoản!');
        }

        $staff = User::onlyTrashed()->findOrFail($id);
        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        // Admin không được khôi phục Super Admin / Admin khác nếu không có quyền
        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if (in_array($targetRole, ['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền khôi phục tài khoản Quản trị viên!');
            }
        }

        $staff->restore();

        return redirect()->route('admin.staffs.trash')->with('success', 'Khôi phục tài khoản thành công!');
    }

    /**
     * Xóa vĩnh viễn tài khoản nhân sự
     */
    public function forceDelete($id)
    {
        $currentUser = auth()->user();

        if (!$currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
            return back()->with('error', 'Nhân viên không có quyền xóa vĩnh viễn tài khoản!');
        }

        $staff = User::onlyTrashed()->findOrFail($id);
        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        if ($currentUser->hasRole('Admin') && !$currentUser->hasRole('Super Admin')) {
            if (in_array($targetRole, ['Super Admin', 'Admin'])) {
                return back()->with('error', 'Bạn không có quyền xóa vĩnh viễn tài khoản Quản trị viên!');
            }
        }

        if ($staff->avatar) {
            $oldPath = str_replace('storage/', '', $staff->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $staff->forceDelete();

        return redirect()->route('admin.staffs.trash')->with('success', 'Xóa vĩnh viễn tài khoản thành công!');
    }
}

