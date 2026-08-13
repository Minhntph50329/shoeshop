<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('isDeleted', false)->where('type', 'slider')->get();
        $categories = Category::where('isDeleted', false)->get();
        
        $newProducts = Product::where('isDeleted', false)->where('isNew', true)->latest()->take(8)->get();
        $bestSellers = Product::where('isDeleted', false)->where('isBestSeller', true)->latest()->take(8)->get();
        $topViews = Product::where('isDeleted', false)->where('isTopView', true)->latest()->take(8)->get();

        return Inertia::render('Client/ClientHome', [
            'banners' => $banners,
            'categories' => $categories,
            'newProducts' => $newProducts,
            'bestSellers' => $bestSellers,
            'topViews' => $topViews
        ]);
    }
}
