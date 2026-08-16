@extends('layouts.admin')

@section('title', 'Thêm mới người dùng - Veloce Admin')
@section('page_title', 'Thêm mới người dùng')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Thêm mới người dùng</h2>
            <p class="text-sm text-slate-500">Tạo tài khoản khách hàng hoặc quản trị viên mới</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Personal Info Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Thông tin cá nhân & Tài khoản</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required placeholder="Nguyễn Văn A" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('fullname') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Địa chỉ Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="user@example.com" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Số điện thoại</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="0912345678" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mật khẩu <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="••••••••" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Giới tính</label>
                    <select name="gender" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ngày sinh</label>
                    <input type="date" name="birthday" value="{{ old('birthday') }}" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ảnh đại diện (Avatar)</label>
                <input type="file" name="avatar" accept="image/*" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
        </div>

        <!-- Role & Status Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Phân quyền & Trạng thái</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Vai trò (Role) <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                        @if(!auth()->user()->hasAnyRole(['Super Admin', 'Admin'])) disabled @endif>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', 'Customer') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if(!auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
                        <input type="hidden" name="role" value="Customer">
                        <p class="text-[10px] text-slate-500 mt-1 italic">* Chỉ Admin và Super Admin mới có quyền chọn vai trò. Mặc định là Khách hàng.</p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trạng thái <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="locked" {{ old('status') == 'locked' ? 'selected' : '' }}>Khóa tài khoản</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lý do khóa (Nếu chọn Đã khóa)</label>
                <textarea name="reason_lock" rows="2" placeholder="Nhập lý do tại sao tài khoản này bị khóa..." 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('reason_lock') }}</textarea>
            </div>
        </div>

        <!-- Bank Details Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Thông tin Ngân hàng (Dùng cho hoàn tiền / rút tiền)</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tên ngân hàng</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Vietcombank, MB Bank..." 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tên chủ tài khoản</label>
                    <input type="text" name="user_bank_name" value="{{ old('user_bank_name') }}" placeholder="NGUYEN VAN A" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Số tài khoản</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}" placeholder="123456789" 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.customers.index') }}" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Hủy bỏ</a>
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                Thêm người dùng
            </button>
        </div>
    </form>
</div>
@endsection
