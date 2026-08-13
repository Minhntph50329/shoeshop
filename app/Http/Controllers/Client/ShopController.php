<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('isDeleted', false);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->filled('category')) {
            $query->where('categoryId', $request->input('category'));
        }

        if ($request->filled('brand')) {
            $query->where('brandId', $request->input('brand'));
        }

        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('salesCount', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        return Inertia::render('Client/ClientShop', [
            'products' => $query->paginate(12),
            'categories' => Category::where('isDeleted', false)->get(),
            'brands' => Brand::where('isDeleted', false)->get(),
            'filters' => $request->only(['q', 'category', 'brand', 'sort'])
        ]);
    }
}
