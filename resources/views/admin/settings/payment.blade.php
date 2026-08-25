@extends('layouts.admin')

@section('title', 'Cài đặt Thanh toán - Veloce')
@section('page_title', 'Cài đặt Thanh toán')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.settings.index') }}" class="text-slate-400 hover:text-indigo-600 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-bold text-slate-800">Cấu hình Thanh toán</h2>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 auto-hide-error">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
    </div>
    <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
</div>
@endif

<form action="{{ route('admin.settings.payment.save') }}" method="POST" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Thanh toán COD --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="truck" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Thanh toán khi nhận hàng</h3>
                        <p class="text-[10px] text-slate-500">Khách hàng thanh toán khi nhận được hàng (COD).</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="payment_cod" value="1" class="sr-only peer" {{ \App\Models\Setting::get('payment_cod', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>
            <div class="flex-1">
                <p class="text-sm text-slate-600">Đây là phương thức thanh toán phổ biến nhất. Khách hàng sẽ thanh toán tiền mặt cho nhân viên giao hàng sau khi kiểm tra và nhận sản phẩm.</p>
            </div>
        </div>

        {{-- Thanh toán VNPay --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Thanh toán VNPay</h3>
                        <p class="text-[10px] text-slate-500">Thanh toán trực tuyến thông qua cổng thanh toán VNPay.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="payment_vnpay" value="1" class="sr-only peer" {{ \App\Models\Setting::get('payment_vnpay', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <div class="flex-1 space-y-3">
                <p class="text-sm text-slate-600">Cho phép khách hàng thanh toán nhanh chóng qua thẻ ATM, Visa/MasterCard hoặc quét mã QR VNPay.</p>
                <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg flex gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-blue-500 shrink-0 mt-0.5"></i>
                    <p class="text-[11px] text-blue-800">Hệ thống đang sử dụng cấu hình VNPay hiện tại của project. Việc bật/tắt ở đây chỉ điều khiển việc hiển thị tùy chọn VNPay ở trang thanh toán.</p>
                </div>
            </div>
        </div>

        {{-- Chuyển khoản ngân hàng --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Chuyển khoản ngân hàng</h3>
                        <p class="text-[10px] text-slate-500">Khách hàng thanh toán bằng hình thức chuyển khoản.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="payment_bank" value="1" class="sr-only peer" onchange="document.getElementById('bank_details').style.display = this.checked ? 'block' : 'none'" {{ \App\Models\Setting::get('payment_bank', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            
            <div id="bank_details" style="display: {{ \App\Models\Setting::get('payment_bank', '1') == '1' ? 'block' : 'none' }};">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tên ngân hàng (và Chi nhánh)</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', \App\Models\Setting::get('bank_name')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm" placeholder="VD: Vietcombank - Chi nhánh Hà Nội">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Số tài khoản</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', \App\Models\Setting::get('bank_account_number')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tên chủ tài khoản</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', \App\Models\Setting::get('bank_account_name')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm uppercase">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Hướng dẫn & Nội dung chuyển khoản</label>
                        <textarea name="bank_transfer_content" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm" placeholder="VD: Nội dung chuyển khoản: [Mã đơn hàng] - [Số điện thoại]">{{ old('bank_transfer_content', \App\Models\Setting::get('bank_transfer_content', 'Vui lòng ghi rõ Mã đơn hàng trong nội dung chuyển khoản.')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div>
        <button type="submit" class="flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-sm shadow-indigo-200 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition disabled:opacity-70" onclick="this.disabled=true; this.innerHTML='<i data-lucide=\'loader-2\' class=\'w-5 h-5 animate-spin\'></i> Đang lưu...'; this.form.submit();">
            <i data-lucide="save" class="w-5 h-5"></i>
            Lưu thay đổi
        </button>
    </div>
</form>
@endsection
