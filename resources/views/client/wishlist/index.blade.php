@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích - Veloce')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sản phẩm yêu thích</h1>
            <p class="text-xs text-slate-500">Danh sách các sản phẩm bạn đã lưu lại để mua sau</p>
        </div>
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-700">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-xs flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-500"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(isset($wishlists) && $wishlists->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($wishlists as $item)
                @php $product = $item->product; @endphp
                @if($product)
                    <div class="group bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-xl shadow-slate-100/50 hover:shadow-2xl transition duration-300 flex flex-col justify-between relative">
                        <!-- Remove from Wishlist Form -->
                        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                            @csrf
                            <button type="submit" class="w-9 h-9 bg-white/90 backdrop-blur-md rounded-full text-rose-500 hover:bg-rose-50 flex items-center justify-center shadow-md transition" title="Xóa khỏi yêu thích">
                                <i data-lucide="heart" class="w-5 h-5 fill-current"></i>
                            </button>
                        </form>

                        <div>
                            <div class="relative h-56 bg-slate-50 overflow-hidden flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <i data-lucide="package" class="w-12 h-12 text-slate-300"></i>
                                @endif
                            </div>

                            <div class="p-5 space-y-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">SKU: {{ $product->sku ?? 'N/A' }}</span>
                                <h3 class="font-bold text-sm text-slate-900 group-hover:text-indigo-600 transition line-clamp-2">
                                    <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-base font-black text-indigo-600">{{ number_format($product->final_price, 0, ',', '.') }}đ</span>
                                    @if($product->discount > 0)
                                        <span class="text-xs text-slate-400 line-through font-medium">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-5 pt-0">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider shadow-md shadow-indigo-200 flex items-center justify-center gap-2 transition active:scale-[0.99]">
                                    <i data-lucide="shopping-bag" class="w-4 h-4"></i> Thêm vào giỏ hàng
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if($wishlists->hasPages())
            <div class="pt-4">
                {{ $wishlists->links() }}
            </div>
        @endif
    @else
        <div class="bg-white p-12 rounded-3xl border border-slate-100 shadow-sm text-center max-w-md mx-auto space-y-4">
            <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto">
                <i data-lucide="heart" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">Danh sách yêu thích trống!</h3>
            <p class="text-xs text-slate-500 leading-relaxed">Bạn chưa lưu sản phẩm nào vào yêu thích. Hãy bấm vào biểu tượng trái tim khi xem sản phẩm để lưu lại.</p>
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition">
                <span>Khám phá sản phẩm</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    @endif
</div>
@endsection
