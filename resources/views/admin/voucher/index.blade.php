@extends('layouts.admin')

@section('title', 'Quản lý Mã giảm giá - Veloce Admin')
@section('page_title', 'Danh sách Mã giảm giá (Voucher / Coupon)')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">
    <!-- Header Actions -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">Quản lý Mã giảm giá</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">Tạo mã Voucher khuyến mãi, thiết lập thời gian và giới hạn sử dụng</p>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
            <a href="{{ route('admin.voucher.trash') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 sm:px-4">
                <i data-lucide="trash-2" class="w-4 h-4 text-slate-500 shrink-0"></i> 
                <span>Thùng rác</span>
            </a>
            <a href="{{ route('admin.voucher.create') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:px-4">
                <i data-lucide="ticket-percent" class="w-4 h-4 shrink-0"></i> 
                <span>Tạo Voucher</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700">
            <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- MOBILE CARDS -->
    <div class="space-y-3 md:hidden">
        @forelse($coupons as $coupon)
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex flex-col min-w-0 gap-2">
                    <div class="flex items-center justify-between gap-3">
                        <span class="px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-xl font-mono font-bold text-indigo-600 text-sm uppercase shrink-0">
                            {{ $coupon->code }}
                        </span>
                        <div>
                            @if($coupon->isValid())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Khả dụng
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 rounded-full text-[11px] font-bold border border-rose-100">
                                    Hết hạn / Khóa
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-slate-900 text-sm truncate">{{ $coupon->title ?? 'Không có tiêu đề' }}</h4>
                        <p class="text-emerald-600 font-bold text-xs mt-0.5">
                            Giảm: 
                            @if($coupon->discount_type === 'percent')
                                {{ number_format($coupon->discount_value, 0) }}%
                            @else
                                {{ number_format($coupon->discount_value, 0, ',', '.') }} VNĐ
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Đã dùng / Giới hạn</p>
                        <p class="mt-1 text-xs font-bold text-slate-800">{{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Thời hạn</p>
                        <div class="mt-1 text-[11px] text-slate-500">
                            <div>Từ: {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y') : 'Ngay lập tức' }}</div>
                            <div>Đến: {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y') : 'Vô thời hạn' }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.voucher.edit', $coupon->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600" title="Chỉnh sửa">
                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </a>
                    <form action="{{ route('admin.voucher.destroy', $coupon->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có muốn chuyển Voucher này vào thùng rác?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" title="Xóa mềm">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">Chưa có mã giảm giá nào.</div>
        @endforelse
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[700px] text-left border-collapse">
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
    </div>
    
    @if($coupons->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4 mt-4">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
