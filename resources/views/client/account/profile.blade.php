@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân - Veloce')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-xs flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-500"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-xs flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 text-rose-500"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Sidebar Card -->
        <div class="space-y-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 text-center space-y-4">
                <div class="relative w-24 h-24 mx-auto">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->fullname }}" class="w-full h-full rounded-3xl object-cover border-4 border-indigo-50 shadow-md">
                    @else
                        <div class="w-full h-full rounded-3xl bg-indigo-600 text-white font-black flex items-center justify-center text-3xl uppercase border-4 border-indigo-50 shadow-md">
                            {{ substr($user->fullname ?? $user->email, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div>
                    <h3 class="font-black text-slate-900 text-base leading-tight">{{ $user->fullname ?? 'Chưa đặt tên' }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</p>
                </div>

                <div class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-[11px] font-bold">
                    {{ $user->isAdmin() ? 'Quản trị viên (Admin)' : 'Thành viên Veloce' }}
                </div>
            </div>

            <!-- Profile Navigation Links -->
            <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm space-y-1 text-xs font-semibold">
                <a href="#info-tab" onclick="switchTab('info')" id="tab-btn-info" class="tab-link flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-600 text-white transition">
                    <i data-lucide="user" class="w-4 h-4"></i> Thông tin tài khoản
                </a>
                <a href="#address-tab" onclick="switchTab('address')" id="tab-btn-address" class="tab-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Địa chỉ giao hàng ({{ $user->addresses->count() }})
                </a>
                <a href="#password-tab" onclick="switchTab('password')" id="tab-btn-password" class="tab-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition">
                    <i data-lucide="lock" class="w-4 h-4"></i> Đổi mật khẩu
                </a>
                <a href="{{ route('my-orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition">
                    <i data-lucide="package" class="w-4 h-4"></i> Đơn hàng của tôi
                </a>
            </div>
        </div>

        <!-- Right Content Panels -->
        <div class="lg:col-span-3 space-y-6">

            <!-- TAB 1: Personal Info & Bank Details -->
            <div id="tab-content-info" class="tab-content bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-black text-slate-900">Thông tin cá nhân & Ngân hàng</h2>
                    <p class="text-xs text-slate-500">Quản lý và cập nhật thông tin cá nhân của bạn</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Thông tin cơ bản</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                                <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" required 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                                @error('fullname') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Địa chỉ Email (Không đổi)</label>
                                <input type="email" value="{{ $user->email }}" disabled 
                                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-500 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Số điện thoại</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="0912345678" 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Giới tính</label>
                                <select name="gender" class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ngày sinh</label>
                                <input type="date" name="birthday" value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '') }}" 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Đổi Avatar</label>
                                <input type="file" name="avatar" accept="image/*" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>

                    <!-- Bank Account Info -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Thông tin Ngân hàng (Dùng cho hoàn tiền)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tên ngân hàng</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" placeholder="MB Bank, Vietcombank..." 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tên chủ tài khoản</label>
                                <input type="text" name="user_bank_name" value="{{ old('user_bank_name', $user->user_bank_name) }}" placeholder="NGUYEN VAN A" 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Số tài khoản</label>
                                <input type="text" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}" placeholder="123456789" 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: Shipping Addresses (user_addresses) -->
            <div id="tab-content-address" class="tab-content hidden bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-black text-slate-900">Địa chỉ nhận hàng (user_addresses)</h2>
                    <p class="text-xs text-slate-500">Quản lý các địa chỉ nhận hàng của bạn</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($user->addresses as $addr)
                        <div class="p-5 border rounded-2xl relative space-y-2 {{ $addr->is_default ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-200' }}">
                            @if($addr->is_default)
                                <span class="absolute top-4 right-4 px-2.5 py-1 bg-indigo-600 text-white rounded-md text-[10px] font-bold">Mặc định</span>
                            @endif
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                                <span class="font-bold text-xs text-slate-900">{{ $addr->fullname }}</span>
                                <span class="text-slate-500 text-xs">({{ $addr->phone_number }})</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $addr->address }}</p>
                            <div class="text-[11px] text-slate-400">
                                {{ $addr->ward ? $addr->ward . ', ' : '' }}{{ $addr->district ? $addr->district . ', ' : '' }}{{ $addr->province }}
                            </div>

                            <div class="pt-2 flex justify-end">
                                <form action="{{ route('profile.address.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-bold inline-flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Xóa địa chỉ
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-6 text-slate-400 text-xs">Bạn chưa thêm địa chỉ nhận hàng nào.</div>
                    @endforelse
                </div>

                <!-- Form Thêm Địa chỉ Mới -->
                <div class="pt-6 border-t border-slate-100 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Thêm địa chỉ giao hàng mới</h4>

                    <form action="{{ route('profile.address.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Họ tên người nhận <span class="text-rose-500">*</span></label>
                                <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" required placeholder="Nguyễn Văn A" 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">SĐT người nhận <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required placeholder="0912345678" 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tỉnh / Thành phố</label>
                                <input type="text" name="province" placeholder="Hà Nội, TP.HCM..." 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quận / Huyện</label>
                                <input type="text" name="district" placeholder="Thanh Xuân..." 
                                    class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Địa chỉ cụ thể (Số nhà, tên đường...) <span class="text-rose-500">*</span></label>
                            <input type="text" name="address" required placeholder="Số 123, đường Nguyễn Trãi..." 
                                class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_default" value="1" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                                <span class="text-xs font-bold text-slate-700">Đặt làm địa chỉ mặc định</span>
                            </label>
                            <select name="address_type" class="py-2 px-3 border border-slate-200 rounded-xl text-xs font-medium">
                                <option value="home">Nhà riêng</option>
                                <option value="office">Văn phòng</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>

                        <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition">
                            Thêm địa chỉ mới
                        </button>
                    </form>
                </div>
            </div>

            <!-- TAB 3: Change Password -->
            <div id="tab-content-password" class="tab-content hidden bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-black text-slate-900">Đổi mật khẩu</h2>
                    <p class="text-xs text-slate-500">Cập nhật mật khẩu để bảo mật tài khoản của bạn</p>
                </div>

                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4 max-w-md">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mật khẩu hiện tại <span class="text-rose-500">*</span></label>
                        <input type="password" name="current_password" required placeholder="••••••••" 
                            class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('current_password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mật khẩu mới <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Tối thiểu 6 ký tự" 
                            class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Xác nhận mật khẩu mới <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới" 
                            class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    </div>

                    <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition">
                        Cập nhật mật khẩu
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-link').forEach(el => {
        el.classList.remove('bg-indigo-600', 'text-white');
        el.classList.add('text-slate-600', 'hover:bg-slate-50');
    });

    const targetContent = document.getElementById('tab-content-' + tabName);
    const targetBtn = document.getElementById('tab-btn-' + tabName);

    if (targetContent) targetContent.classList.remove('hidden');
    if (targetBtn) {
        targetBtn.classList.add('bg-indigo-600', 'text-white');
        targetBtn.classList.remove('text-slate-600', 'hover:bg-slate-50');
    }
}
</script>
@endsection
