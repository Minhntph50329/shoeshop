@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhân viên - Veloce Admin')
@section('page_title', 'Chỉnh sửa nhân sự')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Chỉnh sửa tài khoản: {{ $staff->fullname ?? $staff->email }}</h2>
            <p class="text-sm text-slate-500">Cập nhật thông tin nhân sự, phân quyền và trạng thái hoạt động</p>
        </div>
        <a href="{{ route('admin.staffs.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    @if(session('error'))
        <div class="flex items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700">
            <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.staffs.update', $staff->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Personal Info Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Thông tin cá nhân & Tài khoản</h3>
            
            <div class="flex items-center gap-4 py-2">
                @if($staff->avatar)
                    <img src="{{ asset($staff->avatar) }}" alt="{{ $staff->fullname }}" class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">
                @else
                    <div class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 font-black flex items-center justify-center text-xl uppercase border-2 border-indigo-200">
                        {{ substr($staff->fullname ?? $staff->email, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-bold text-slate-900">{{ $staff->fullname ?? 'Chưa đặt tên' }}</h4>
                    <p class="text-xs text-slate-500">ID: #{{ $staff->id }} | Ngày tham gia: {{ $staff->created_at ? $staff->created_at->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname', $staff->fullname) }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('fullname') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Địa chỉ Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $staff->email) }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Số điện thoại</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $staff->phone_number) }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Đổi mật khẩu (Bỏ trống nếu không đổi)</label>
                    <input type="password" name="password" placeholder="••••••••" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Giới tính</label>
                    <select name="gender" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="male" {{ old('gender', $staff->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $staff->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender', $staff->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ngày sinh</label>
                    <input type="date" name="birthday" value="{{ old('birthday', $staff->birthday ? $staff->birthday->format('Y-m-d') : '') }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Thay đổi Avatar</label>
                <input type="file" name="avatar" accept="image/*" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
        </div>

        <!-- Role & Status Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Phân quyền & Trạng thái</h3>

            @php 
                $currentUser = auth()->user();
                $isCurrentUser = ($currentUser->id === $staff->id);
                $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

                // Super admin có quyền chỉnh sửa tất cả các vai trò của admin và nhân viên
                // Admin thì chỉnh quyền của nhân viên và ko được chỉnh quyền của admin khác
                // Nhân viên thì không được chỉnh gì hết
                $canEditRole = false;
                if (!$isCurrentUser && $currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
                    if ($currentUser->hasRole('Super Admin')) {
                        $canEditRole = true;
                    } elseif ($currentUser->hasRole('Admin') && !in_array($targetRole, ['Super Admin', 'Admin'])) {
                        $canEditRole = true;
                    }
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Vai trò (Role) <span class="text-rose-500">*</span></label>
                    <select name="role" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                        @if(!$canEditRole) disabled @endif>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', $targetRole) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>

                    @if(!$canEditRole)
                        <input type="hidden" name="role" value="{{ $targetRole }}">
                        @if($isCurrentUser)
                            <p class="text-[10px] text-slate-500 mt-1 italic">* Bạn không thể tự đổi vai trò của chính mình</p>
                        @else
                            <p class="text-[10px] text-slate-500 mt-1 italic">* Bạn không có quyền thay đổi vai trò của tài khoản này</p>
                        @endif
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trạng thái <span class="text-rose-500">*</span></label>
                    <select name="status" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="locked" {{ old('status', $staff->status) == 'locked' ? 'selected' : '' }}>Khóa tài khoản</option>
                    </select>
                    @error('status') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lý do khóa (Nếu chọn Đã khóa)</label>
                <textarea name="reason_lock" rows="2" placeholder="Nhập lý do khóa tài khoản..." 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('reason_lock', $staff->reason_lock) }}</textarea>
            </div>
        </div>

        <!-- Bank Details Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Thông tin Ngân hàng (Dùng để trả lương / giao dịch)</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tên ngân hàng</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $staff->bank_name) }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tên chủ tài khoản</label>
                    <input type="text" name="user_bank_name" value="{{ old('user_bank_name', $staff->user_bank_name) }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Số tài khoản</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $staff->bank_account) }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.staffs.index') }}" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Hủy bỏ</a>
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                Cập nhật thông tin
            </button>
        </div>
    </form>
</div>
@endsection

