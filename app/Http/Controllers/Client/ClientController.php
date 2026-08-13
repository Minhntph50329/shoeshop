<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Trang chủ (Home Page)
     */
    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->take(6)
            ->get();

        $brands = Brand::where('is_active', true)
            ->where('is_visible', true)
            ->take(8)
            ->get();

        $latestProducts = Product::where('status', 'active')
            ->with(['brand', 'categories'])
            ->latest()
            ->take(8)
            ->get();

        $discountProducts = Product::where('status', 'active')
            ->where('discount', '>', 0)
            ->with(['brand', 'categories'])
            ->latest()
            ->take(8)
            ->get();

        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        return view('client.home', compact('banners', 'categories', 'brands', 'latestProducts', 'discountProducts', 'wishlistIds'));
    }

    /**
     * Trang cửa hàng (Shop Page với lọc sản phẩm, danh mục, thương hiệu)
     */
    public function shop(Request $request)
    {
        $query = Product::where('status', 'active')->with(['brand', 'categories']);

        // Tìm kiếm theo từ khóa
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo danh mục (ID hoặc Slug)
        if ($request->filled('category')) {
            $catParam = $request->category;
            $query->whereHas('categories', function($q) use ($catParam) {
                if (is_numeric($catParam)) {
                    $q->where('categories.id', $catParam);
                } else {
                    $q->where('categories.slug', $catParam);
                }
            });
        }

        // Lọc theo thương hiệu (ID hoặc Slug)
        if ($request->filled('brand')) {
            $brandParam = $request->brand;
            if (is_numeric($brandParam)) {
                $query->where('brand_id', $brandParam);
            } else {
                $query->whereHas('brand', function($q) use ($brandParam) {
                    $q->where('slug', $brandParam);
                });
            }
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->withCount('products');
            }])
            ->withCount('products')
            ->get();

        $brands = Brand::where('is_active', true)
            ->where('is_visible', true)
            ->withCount('products')
            ->get();

        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        return view('client.products.index', compact('products', 'categories', 'brands', 'wishlistIds'));
    }

    /**
     * Trang chi tiết sản phẩm
     */
    public function productDetail($slug)
    {
        $product = Product::where('status', 'active')
            ->where(function($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('id', $slug);
            })
            ->with(['brand', 'categories', 'variants.attributeValues.attribute', 'activeReviews.user', 'activeReviews.images', 'activeReviews.replies.user'])
            ->firstOrFail();

        // Tăng lượt xem (Mỗi tài khoản/phiên chỉ tính 1 lượt xem)
        $sessionKey = 'viewed_product_' . $product->id;
        if (!session()->has($sessionKey)) {
            $product->increment('views');
            session()->put($sessionKey, true);
        }

        // Sản phẩm liên quan (cùng danh mục)
        $categoryIds = $product->categories->pluck('id');
        $relatedProducts = Product::where('status', 'active')
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->take(4)
            ->get();

        $isWishlisted = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists()
            : false;

        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        return view('client.products.show', compact('product', 'relatedProducts', 'isWishlisted', 'wishlistIds'));
    }
}
