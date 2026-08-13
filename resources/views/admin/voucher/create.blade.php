@extends('layouts.admin')

@section('title', 'Tạo Voucher mới - Veloce Admin')
@section('page_title', 'Tạo mã giảm giá mới')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tạo mã giảm giá mới</h2>
            <p class="text-sm text-slate-500">Cấu hình loại giảm giá, giá trị và điều kiện áp dụng</p>
        </div>
        <a href="{{ route('admin.voucher.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.voucher.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mã Voucher (Code) <span class="text-rose-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="VD: SUMMER50K" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-mono font-bold text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 uppercase transition">
                @error('code') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tiêu đề Voucher</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="VD: Giảm 50k cho đơn từ 500k" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Loại giảm giá <span class="text-rose-500">*</span></label>
                <select name="discount_type" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Giá trị giảm <span class="text-rose-500">*</span></label>
                <input type="number" name="discount_value" value="{{ old('discount_value', 0) }}" min="0" step="any" required 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Giới hạn số lần dùng (Tối đa)</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Để trống nếu không giới hạn" min="1" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trạng thái kích hoạt</label>
                <select name="is_active" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="1">Kích hoạt (Active)</option>
                    <option value="0">Khóa (Inactive)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ngày bắt đầu</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ngày kết thúc</label>
                <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mô tả Voucher</label>
            <textarea name="description" rows="3" placeholder="Chi tiết về điều kiện sử dụng mã..." 
                class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_notified" value="1" id="is_notified" class="w-4 h-4 text-indigo-600 rounded">
            <label for="is_notified" class="text-xs font-bold text-slate-700 cursor-pointer">Gửi thông báo tới người dùng về mã giảm giá này</label>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.voucher.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Hủy bỏ</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                Tạo Voucher
            </button>
        </div>
    </form>
</div>
@endsection
