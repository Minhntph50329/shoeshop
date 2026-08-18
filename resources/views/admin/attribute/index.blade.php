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
        <a href="{{ route('admin.attributes.trash') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
            Thùng rác
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Form tạo thuộc tính mới -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-600"></i> Thêm Thuộc tính mới
        </h3>
        <form action="{{ route('admin.attributes.store') }}" method="POST" class="flex flex-col md:flex-row items-end gap-4">
            @csrf
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tên thuộc tính <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: Size, Màu sắc, Chất liệu..." class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                @error('name')
                    <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center gap-6 py-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_variant" value="1" checked class="w-4 h-4 text-indigo-600 rounded border-slate-300">
                    <span class="text-xs font-semibold text-slate-700">Dùng làm biến thể</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 rounded border-slate-300">
                    <span class="text-xs font-semibold text-slate-700">Hoạt động</span>
                </label>
            </div>

            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition shrink-0">
                Tạo thuộc tính
            </button>
        </form>
    </div>

    <!-- Danh sách thuộc tính & Giá trị -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($attributes as $attr)
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Header thuộc tính -->
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

                    <!-- Danh sách các giá trị của thuộc tính -->
                    <div class="p-4">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Giá trị hiện có:</div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @forelse($attr->values as $val)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 border border-slate-200 text-slate-700 rounded-lg text-xs font-medium">
                                    @if($val->color_code)
                                        <span class="w-3 h-3 rounded-full border border-slate-300 inline-block" style="background-color: {{ $val->color_code }}"></span>
                                    @endif
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

                <!-- Form thêm Giá trị thuộc tính -->
                <div class="p-4 bg-slate-50/50 border-t border-slate-100">
                    <form action="{{ route('admin.attributes.values.store', $attr->id) }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <div class="flex items-center gap-2">
                            <input type="text" name="value" placeholder="Giá trị (VD: Đỏ, 42...)" class="flex-1 px-3 py-1.5 rounded-md border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <input type="color" name="color_code" class="w-8 h-8 p-0.5 rounded border border-slate-200 cursor-pointer" title="Mã màu (nếu có)">
                            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-md text-xs font-semibold transition shrink-0">
                                + Thêm
                            </button>
                        </div>
                        @error('value')
                            <div class="text-rose-500 text-xs">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-2 py-8 text-center bg-white rounded-xl border border-slate-100 text-slate-400">
                Chưa có thuộc tính nào được tạo.
            </div>
        @endforelse
    </div>

    @if($attributes->hasPages())
        <div class="p-4 bg-white rounded-xl border border-slate-100">
            {{ $attributes->links() }}
        </div>
    @endif
</div>
@endsection
