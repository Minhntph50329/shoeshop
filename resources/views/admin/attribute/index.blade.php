@extends('layouts.admin')

@section('title', 'Quản lý Thuộc tính')
@section('page_title', 'Thuộc tính Sản phẩm')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Thuộc tính & Giá trị thuộc tính</h2>
            <p class="text-sm text-slate-500">Tạo thuộc tính (Size, Màu sắc...) để phân loại và làm biến thể sản phẩm</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.attributes.values.trash') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="archive" class="w-4 h-4 text-amber-500"></i>
                Thùng rác Giá trị
            </a>
            <a href="{{ route('admin.attributes.trash') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                Thùng rác Thuộc tính
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @php
        $sizeAttr = $attributes->firstWhere('name', 'Size') ?? $attributes->firstWhere('name', 'size') ?? $attributes->firstWhere('name', 'SIZE');
        $colorAttr = $attributes->firstWhere('name', 'Màu sắc') ?? $attributes->firstWhere('name', 'Màu Sắc') ?? $attributes->firstWhere('name', 'màu sắc');
        $materialAttr = $attributes->firstWhere('name', 'Chất liệu') ?? $attributes->firstWhere('name', 'chất liệu') ?? $attributes->firstWhere('name', 'Chất Liệu');
        $otherAttributes = $attributes->filter(function($attr) use ($sizeAttr, $colorAttr, $materialAttr) {
            return (!$sizeAttr || $attr->id !== $sizeAttr->id) && 
                   (!$colorAttr || $attr->id !== $colorAttr->id) && 
                   (!$materialAttr || $attr->id !== $materialAttr->id);
        });
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- KHỐI QUẢN LÝ SIZE -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
            <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="ruler" class="w-5 h-5 text-indigo-600"></i> Quản lý Size
            </h3>
            
            @if($sizeAttr)
                <form action="{{ route('admin.attributes.values.store', $sizeAttr->id) }}" method="POST" class="flex flex-col gap-2 mb-6">
                    @csrf
                    <input type="text" name="value" placeholder="Nhập Size mới (VD: 41, 42)" required class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold transition">
                        Thêm Size
                    </button>
                </form>

                <div class="flex-1">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Các Size hiện có:</div>
                    <div class="flex flex-wrap gap-2">
                        @forelse($sizeAttr->values as $val)
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm">
                                {{ $val->value }}
                                <form action="{{ route('admin.attributes.values.destroy', $val->id) }}" method="POST" class="inline-block ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 transition" title="Xóa">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <span class="text-sm text-slate-400 font-italic">Chưa có size nào.</span>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-6 text-center">
                    <p class="text-sm text-slate-500 mb-4">Bạn chưa thiết lập thuộc tính Size cho hệ thống.</p>
                    <form action="{{ route('admin.attributes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="Size">
                        <input type="hidden" name="is_variant" value="1">
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold shadow-md transition">
                            Khởi tạo thuộc tính Size
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- KHỐI QUẢN LÝ MÀU SẮC -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
            <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="palette" class="w-5 h-5 text-rose-500"></i> Quản lý Màu sắc
            </h3>
            
            @if($colorAttr)
                <form action="{{ route('admin.attributes.values.store', $colorAttr->id) }}" method="POST" class="flex flex-col gap-2 mb-6">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="value" placeholder="Tên màu (VD: Đỏ)" required class="flex-1 min-w-0 px-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                        <input type="color" name="color_code" class="w-10 h-10 shrink-0 p-1 rounded-lg border border-slate-200 cursor-pointer bg-white" title="Chọn mã màu">
                    </div>
                    <button type="submit" class="w-full py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-sm font-bold transition">
                        Thêm Màu
                    </button>
                </form>

                <div class="flex-1">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Các Màu hiện có:</div>
                    <div class="flex flex-wrap gap-2">
                        @forelse($colorAttr->values as $val)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm">
                                @if($val->color_code)
                                    <span class="w-4 h-4 rounded-full border border-slate-300 shadow-inner" style="background-color: {{ $val->color_code }}"></span>
                                @endif
                                {{ $val->value }}
                                <form action="{{ route('admin.attributes.values.destroy', $val->id) }}" method="POST" class="inline-block ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 transition" title="Xóa">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <span class="text-sm text-slate-400 font-italic">Chưa có màu nào.</span>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-6 text-center">
                    <p class="text-sm text-slate-500 mb-4">Bạn chưa thiết lập thuộc tính Màu sắc cho hệ thống.</p>
                    <form action="{{ route('admin.attributes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="Màu sắc">
                        <input type="hidden" name="is_variant" value="1">
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="px-5 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-sm font-bold shadow-md transition">
                            Khởi tạo thuộc tính Màu sắc
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <!-- KHỐI QUẢN LÝ CHẤT LIỆU -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
            <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="box" class="w-5 h-5 text-emerald-500"></i> Quản lý Chất liệu
            </h3>
            
            @if($materialAttr)
                <form action="{{ route('admin.attributes.values.store', $materialAttr->id) }}" method="POST" class="flex flex-col gap-2 mb-6">
                    @csrf
                    <input type="text" name="value" placeholder="Nhập Chất liệu (VD: Da, Vải)" required class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    <button type="submit" class="w-full py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-bold transition">
                        Thêm Chất liệu
                    </button>
                </form>

                <div class="flex-1">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Các Chất liệu hiện có:</div>
                    <div class="flex flex-wrap gap-2">
                        @forelse($materialAttr->values as $val)
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm">
                                {{ $val->value }}
                                <form action="{{ route('admin.attributes.values.destroy', $val->id) }}" method="POST" class="inline-block ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 transition" title="Xóa">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <span class="text-sm text-slate-400 font-italic">Chưa có chất liệu nào.</span>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-6 text-center">
                    <p class="text-sm text-slate-500 mb-4">Bạn chưa thiết lập thuộc tính Chất liệu (Không biến thể).</p>
                    <form action="{{ route('admin.attributes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="Chất liệu">
                        <input type="hidden" name="is_variant" value="0">
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-bold shadow-md transition">
                            Khởi tạo thuộc tính Chất liệu
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- CÁC THUỘC TÍNH KHÁC -->
    @if($otherAttributes->count() > 0)
    <div class="mt-8">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i data-lucide="layers" class="w-5 h-5 text-slate-500"></i> Các thuộc tính khác
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($otherAttributes as $attr)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-slate-800 flex items-center gap-2">
                                    {{ $attr->name }}
                                    @if($attr->is_variant)
                                        <span class="px-2 py-0.5 text-[10px] uppercase font-bold bg-indigo-50 text-indigo-600 border border-indigo-200 rounded">Biến thể</span>
                                    @endif
                                </h4>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.attributes.destroy', $attr->id) }}" method="POST" onsubmit="return confirm('Xóa mềm thuộc tính này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-md transition" title="Xóa thuộc tính">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Giá trị hiện có:</div>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @forelse($attr->values as $val)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 border border-slate-200 text-slate-700 rounded-lg text-xs font-medium">
                                        {{ $val->value }}
                                        <form action="{{ route('admin.attributes.values.destroy', $val->id) }}" method="POST" class="inline-block ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-500 transition">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400 font-italic">Chưa có giá trị nào.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50/50 border-t border-slate-100">
                        <form action="{{ route('admin.attributes.values.store', $attr->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="text" name="value" placeholder="Nhập giá trị mới" required class="flex-1 px-3 py-1.5 rounded-md border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-slate-500">
                            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-md text-xs font-semibold transition shrink-0">
                                + Thêm
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
