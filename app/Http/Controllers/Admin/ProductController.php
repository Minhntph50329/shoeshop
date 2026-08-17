<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Tự động sinh mã SKU theo tiền tố PRD + 6 chữ số ngẫu nhiên
     */
    private function generateSku(): string
    {
        do {
            $sku = 'PRD' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Product::where('sku', $sku)->exists() || ProductVariant::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * Danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'categories'])->latest();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('sku', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Form thêm sản phẩm
     */
    public function create()
    {
        $brands = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $attributes = Attribute::where('is_active', true)->with('values')->get();

        return view('admin.products.create', compact('brands', 'categories', 'attributes'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->only([
            'brand_id', 'name', 'price', 'stock', 'discount',
            'discount_start', 'discount_end', 'status',
            'description', 'short_description', 'sku'
        ]);

        if ($request->product_type === 'variable') {
            $firstVariant = collect($request->variants)->first();
            $data['price'] = !empty($firstVariant['price']) ? $firstVariant['price'] : 0;
            $data['stock'] = isset($firstVariant['stock']) ? $firstVariant['stock'] : 0;
        }

        // Nếu SKU để trống -> tự động tạo mã SKU dạng PRD123456
        if (empty($data['sku'])) {
            $data['sku'] = $this->generateSku();
        }

        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['views'] = 0;

        // Xử lý ảnh đại diện chính
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $product = Product::create($data);

        // Xử lý thư viện ảnh sản phẩm (Lưu vào bảng product_images với product_variant_id = null)
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $url = 'storage/' . $path;
                $galleryPaths[] = $url;

                ProductImage::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'url' => $url,
                ]);
            }
            $product->gallery = $galleryPaths;
            $product->save();
        }

        // Gắn danh mục
        if ($request->filled('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

        // Xử lý biến thể sản phẩm (Variants) & Ảnh biến thể
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $index => $variantData) {
                if (empty($variantData['attribute_value_ids'])) continue;

                $variantSku = !empty($variantData['sku']) ? $variantData['sku'] : $this->generateSku();

                $variant = $product->variants()->create([
                    'sku' => $variantSku,
                    'price' => !empty($variantData['price']) ? $variantData['price'] : $product->price,
                    'stock' => isset($variantData['stock']) ? $variantData['stock'] : $product->stock,
                    'is_active' => true,
                ]);

                // Upload ảnh riêng cho từng sản phẩm biến thể (lưu vào bảng product_images)
                if ($request->hasFile("variants.{$index}.image")) {
                    $vPath = $request->file("variants.{$index}.image")->store('products/variants', 'public');
                    $vUrl = 'storage/' . $vPath;

                    $variant->image = $vUrl;
                    $variant->save();

                    ProductImage::create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'url' => $vUrl,
                    ]);
                }

                $variant->attributeValues()->sync($variantData['attribute_value_ids']);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * Chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::with(['brand', 'categories', 'images', 'variants.attributeValues.attribute', 'variants.images'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Form sửa sản phẩm
     */
    public function edit($id)
    {
        $product = Product::with(['categories', 'images', 'variants.attributeValues', 'variants.images'])->findOrFail($id);
        $brands = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $attributes = Attribute::where('is_active', true)->with('values')->get();

        return view('admin.products.edit', compact('product', 'brands', 'categories', 'attributes'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->only([
            'brand_id', 'name', 'price', 'stock', 'discount',
            'discount_start', 'discount_end', 'status',
            'description', 'short_description', 'sku'
        ]);

        if ($request->product_type === 'variable') {
            $firstVariant = collect($request->variants)->first();
            $data['price'] = !empty($firstVariant['price']) ? $firstVariant['price'] : 0;
            $data['stock'] = isset($firstVariant['stock']) ? $firstVariant['stock'] : 0;
        }

        // Nếu SKU để trống -> tự động sinh mã
        if (empty($data['sku'])) {
            $data['sku'] = $product->sku ?: $this->generateSku();
        }

        // Cập nhật ảnh đại diện chính
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = 'storage/' . $path;
        }

        // Cập nhật thư viện ảnh (gallery & bảng product_images)
        if ($request->hasFile('gallery')) {
            $galleryPaths = $product->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $url = 'storage/' . $path;
                $galleryPaths[] = $url;

                ProductImage::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'url' => $url,
                ]);
            }
            $data['gallery'] = $galleryPaths;
        }

        $product->update($data);

        // Sync danh mục
        $product->categories()->sync($request->category_ids ?? []);

        // Cập nhật biến thể
        if ($request->product_type === 'variable' && $request->has('variants') && is_array($request->variants)) {
            $existingVariantIds = [];
            foreach ($request->variants as $index => $variantData) {
                if (empty($variantData['attribute_value_ids'])) continue;

                $variantSku = !empty($variantData['sku']) ? $variantData['sku'] : $this->generateSku();

                if (!empty($variantData['id'])) {
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'sku' => $variantSku,
                            'price' => !empty($variantData['price']) ? $variantData['price'] : $product->price,
                            'stock' => isset($variantData['stock']) ? $variantData['stock'] : 0,
                        ]);

                        // Cập nhật ảnh riêng cho biến thể nếu được upload
                        if ($request->hasFile("variants.{$index}.image")) {
                            $vPath = $request->file("variants.{$index}.image")->store('products/variants', 'public');
                            $vUrl = 'storage/' . $vPath;

                            $variant->image = $vUrl;
                            $variant->save();

                            ProductImage::create([
                                'product_id' => $product->id,
                                'product_variant_id' => $variant->id,
                                'url' => $vUrl,
                            ]);
                        }

                        $variant->attributeValues()->sync($variantData['attribute_value_ids']);
                        $existingVariantIds[] = $variant->id;
                    }
                } else {
                    $variant = $product->variants()->create([
                        'sku' => $variantSku,
                        'price' => !empty($variantData['price']) ? $variantData['price'] : $product->price,
                        'stock' => isset($variantData['stock']) ? $variantData['stock'] : 0,
                        'is_active' => true,
                    ]);

                    if ($request->hasFile("variants.{$index}.image")) {
                        $vPath = $request->file("variants.{$index}.image")->store('products/variants', 'public');
                        $vUrl = 'storage/' . $vPath;

                        $variant->image = $vUrl;
                        $variant->save();

                        ProductImage::create([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'url' => $vUrl,
                        ]);
                    }

                    $variant->attributeValues()->sync($variantData['attribute_value_ids']);
                    $existingVariantIds[] = $variant->id;
                }
            }
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
        } else {
            // Delete all variants if product type is simple
            $product->variants()->delete();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa mềm sản phẩm
     */
    public function destroy($id)
    {
        $isInCart = \App\Models\CartItem::where('product_id', $id)
            ->whereHas('cart', function($query) {
                $query->where('status', 'active');
            })
            ->exists();

        if ($isInCart) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Sản phẩm này đang có trong giỏ hàng của người dùng, không thể xóa.');
        }

        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã chuyển sản phẩm vào thùng rác!');
    }

    /**
     * Thùng rác
     */
    public function trash()
    {
        $trashed = Product::onlyTrashed()->with(['brand', 'categories'])->latest()->paginate(10);
        return view('admin.products.trash', compact('trashed'));
    }

    /**
     * Khôi phục sản phẩm
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.trash')
            ->with('success', 'Đã khôi phục sản phẩm!');
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        $isInCart = \App\Models\CartItem::where('product_id', $id)
            ->whereHas('cart', function($query) {
                $query->where('status', 'active');
            })
            ->exists();

        if ($isInCart) {
            return redirect()->route('admin.products.trash')
                ->with('error', 'Sản phẩm này đang có trong giỏ hàng của người dùng, không thể xóa vĩnh viễn.');
        }

        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return redirect()->route('admin.products.trash')
            ->with('success', 'Đã xóa vĩnh viễn sản phẩm!');
    }
}
