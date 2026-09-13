@extends('layouts.admin')

@section('title', 'Thùng rác Giá trị thuộc tính')
@section('page_title', 'Thùng rác Giá trị thuộc tính')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Thùng rác – Giá trị thuộc tính</h2>
            <p class="text-sm text-slate-500">Các giá trị đã bị xóa mềm. Bạn có thể khôi phục hoặc xóa vĩnh viễn.</p>
        </div>
        <a href="{{ route('admin.attributes.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Giá trị</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Thuộc về</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Mã màu</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Xóa lúc</th>
                    <th class="px-6 py-3 text-right font-semibold text-slate-600">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($trashedValues as $val)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $val->value }}</td>
                        <td class="px-6 py-4 text-slate-500">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-semibold">
                                {{ $val->attribute->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($val->color_code)
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full border border-slate-300" style="background-color: {{ $val->color_code }}"></span>
                                    <span class="text-xs text-slate-500">{{ $val->color_code }}</span>
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs">{{ $val->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.attributes.values.restore', $val->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-md text-xs font-semibold transition">
                                        Khôi phục
                                    </button>
                                </form>
                                <form action="{{ route('admin.attributes.values.forceDelete', $val->id) }}" method="POST" onsubmit="return confirm('Xóa vĩnh viễn giá trị này? Không thể hoàn tác!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded-md text-xs font-semibold transition">
                                        Xóa vĩnh viễn
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i data-lucide="trash-2" class="w-10 h-10 mx-auto mb-3 text-slate-200"></i>
                            <p>Thùng rác trống. Không có giá trị nào bị xóa.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($trashedValues->hasPages())
        <div class="p-4 bg-white rounded-xl border border-slate-100">
            {{ $trashedValues->links() }}
        </div>
    @endif
</div>
@endsection
