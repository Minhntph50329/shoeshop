@extends('layouts.admin')

@section('title', 'Quản lý Mã giảm giá - Veloce Admin')
@section('page_title', 'Danh sách Mã giảm giá (Voucher / Coupon)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Quản lý Mã giảm giá</h2>
            <p class="text-sm text-slate-500">Tạo mã Voucher khuyến mãi, thiết lập thời gian và giới hạn sử dụng</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.voucher.trash') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-slate-500"></i> Thùng rác
            </a>
            <a href="{{ route('admin.voucher.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                <i data-lucide="ticket-percent" class="w-4 h-4"></i> Tạo Voucher mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Mã Voucher</th>
                        <th class="p-4">Tiêu đề / Giảm giá</th>
                        <th class="p-4">Đã dùng / Giới hạn</th>
                        <th class="p-4">Thời hạn</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-mono font-bold text-indigo-600 text-sm">
                                <span class="px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-xl uppercase">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="p-4">
                                <h4 class="font-bold text-slate-900 text-sm">{{ $coupon->title ?? 'Không có tiêu đề' }}</h4>
                                <p class="text-emerald-600 font-bold text-xs mt-0.5">
                                    Giảm: 
                                    @if($coupon->discount_type === 'percent')
                                        {{ number_format($coupon->discount_value, 0) }}%
                                    @else
                                        {{ number_format($coupon->discount_value, 0, ',', '.') }} VNĐ
                                    @endif
                                </p>
                            </td>
                            <td class="p-4 font-bold text-slate-800">
                                {{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]">
                                <div>Từ: {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y H:i') : 'Ngay lập tức' }}</div>
                                <div>Đến: {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y H:i') : 'Vô thời hạn' }}</div>
                            </td>
                            <td class="p-4">
                                @if($coupon->isValid())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Khả dụng
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 rounded-full text-[11px] font-bold border border-rose-100">
                                        Hết hạn / Khóa
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.voucher.edit', $coupon->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Chỉnh sửa">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.voucher.destroy', $coupon->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có muốn chuyển Voucher này vào thùng rác?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Xóa mềm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Chưa có mã giảm giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
