<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $cart = Cart::with(['items.product', 'items.variant.attributeValues.attribute'])
            ->firstOrCreate([
                'user_id' => Auth::id(),
                'status' => 'active',
            ]);

        return view('client.cart.index', compact('cart'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (kiểm tra giới hạn tồn kho)
     */
    public function add(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng.'], 401);
            }
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;
        $maxStock = $product->stock;

        if ($request->filled('product_variant_id')) {
            $variant = ProductVariant::findOrFail($request->product_variant_id);
            $priceAtTime = $variant->price ?? $product->price;
            $maxStock = $variant->stock;
        } else {
            $priceAtTime = $product->price;
        }

        // Lấy hoặc tạo active cart cho user
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id(),
            'status' => 'active',
        ]);

        // Tìm item trùng lặp trong giỏ
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        $existingQty = $cartItem ? $cartItem->quantity : 0;
        $newTotalQty = $existingQty + $request->quantity;

        // KIỂM TRA TỒN KHO
        if ($newTotalQty > $maxStock) {
            $productLabel = $variant ? "mã SKU {$variant->sku}" : "'{$product->name}'";
            $errorMsg = "Rất tiếc, sản phẩm {$productLabel} chỉ còn tồn kho {$maxStock} sản phẩm. (Giỏ hàng của bạn đã có {$existingQty} món).";
            
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMsg], 400);
            }
            return back()->with('error', $errorMsg);
        }

        if ($cartItem) {
            $cartItem->quantity = $newTotalQty;
            $cartItem->price_at_time = $priceAtTime;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
                'price_at_time' => $priceAtTime,
            ]);
        }

        // Xóa sản phẩm khỏi danh sách yêu thích nếu có
        \App\Models\Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
                'cart_count' => $cart->fresh()->total_quantity,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ (kiểm tra giới hạn tồn kho)
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->firstOrFail();
        $cartItem = CartItem::with(['product', 'variant'])->where('cart_id', $cart->id)->where('id', $id)->firstOrFail();

        $maxStock = $cartItem->variant ? $cartItem->variant->stock : $cartItem->product->stock;

        // KIỂM TRA TỒN KHO KHI CẬP NHẬT
        if ($request->quantity > $maxStock) {
            return back()->with('error', "Không thể tăng số lượng. Sản phẩm này chỉ còn tồn kho tối đa {$maxStock} món.");
        }

        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Đã cập nhật số lượng giỏ hàng.');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)->where('id', $id)->delete();
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * Xóa nhiều sản phẩm được chọn cùng một lúc (Checkbox)
     */
    public function removeMultiple(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:cart_items,id',
        ], [
            'item_ids.required' => 'Vui lòng chọn ít nhất một sản phẩm để xóa.',
        ]);

        $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();

        if ($cart) {
            $count = CartItem::where('cart_id', $cart->id)
                ->whereIn('id', $request->item_ids)
                ->delete();

            return back()->with('success', "Đã xóa {$count} sản phẩm được chọn khỏi giỏ hàng.");
        }

        return back()->with('error', 'Không tìm thấy giỏ hàng.');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }

        return back()->with('success', 'Đã dọn dẹp giỏ hàng.');
    }
}
