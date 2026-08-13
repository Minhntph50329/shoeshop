<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Danh sách sản phẩm yêu thích của user
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem danh sách yêu thích.');
        }

        $wishlists = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('client.wishlist.index', compact('wishlists'));
    }

    /**
     * Thêm / Xóa sản phẩm khỏi yêu thích (Toggle)
     */
    public function toggle($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào danh sách yêu thích.');
        }

        $product = Product::findOrFail($productId);
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Đã xóa sản phẩm khỏi danh sách yêu thích.';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $message = 'Đã thêm sản phẩm vào danh sách yêu thích!';
        }

        return back()->with('success', $message);
    }
}
