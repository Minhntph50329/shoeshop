<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;

class CompareController extends Controller
{
    /**
     * Hiển thị trang so sánh sản phẩm
     */
    public function index()
    {
        $compareSession = session()->get('compare', []);
        
        $normalizedItems = [];
        foreach ($compareSession as $k => $item) {
            if (is_array($item)) {
                $pid = $item['product_id'] ?? null;
                $vid = $item['variant_id'] ?? null;
                if (!$pid) continue;
                $itemKey = $item['key'] ?? ($vid ? "{$pid}_{$vid}" : (string)$pid);
                $normalizedItems[] = [
                    'key'        => $itemKey,
                    'product_id' => (int)$pid,
                    'variant_id' => $vid ? (int)$vid : null,
                ];
            } else {
                $normalizedItems[] = [
                    'key'        => (string)$item,
                    'product_id' => (int)$item,
                    'variant_id' => null,
                ];
            }
        }

        $products = collect();

        if (count($normalizedItems) > 0) {
            $productIds = array_unique(array_column($normalizedItems, 'product_id'));
            $productsById = Product::whereIn('id', $productIds)
                ->with(['brand', 'variants.attributeValues.attribute'])
                ->get()
                ->keyBy('id');

            foreach ($normalizedItems as $item) {
                $product = $productsById->get($item['product_id']);
                if (!$product) continue;

                $variantId = $item['variant_id'];
                $selectedVariant = null;

                if ($variantId) {
                    $selectedVariant = $product->variants->firstWhere('id', $variantId);
                }

                // Nếu sản phẩm có biến thể nhưng chưa xác định được biến thể, fallback lấy biến thể đầu tiên
                if (!$selectedVariant && $product->variants->isNotEmpty()) {
                    $selectedVariant = $product->variants->first();
                }

                // 1. Ảnh hiển thị: ưu tiên ảnh biến thể đã chọn
                $displayImage = ($selectedVariant && $selectedVariant->image) 
                    ? $selectedVariant->image 
                    : $product->image;

                // 2. Giá hiển thị: ưu tiên giá của biến thể đã chọn
                $displayPrice = ($selectedVariant && $selectedVariant->price) 
                    ? $selectedVariant->price 
                    : $product->final_price;

                // 3. Tồn kho của biến thể
                $displayStock = $selectedVariant ? $selectedVariant->stock : $product->stock;

                // 4. Lấy ĐÚNG Size và Màu sắc theo biến thể đã chọn
                $sizeText = '—';
                $colorText = '—';
                $materialText = '—';
                $variantLabels = [];

                if ($selectedVariant) {
                    $sizes = [];
                    $colors = [];
                    $materials = [];

                    foreach ($selectedVariant->attributeValues as $val) {
                        $attrName = mb_strtolower($val->attribute->name ?? '');
                        if (in_array($attrName, ['size', 'kích thước'])) {
                            $sizes[] = $val->value;
                            $variantLabels[] = 'Size ' . $val->value;
                        } elseif (in_array($attrName, ['màu sắc', 'mau sac', 'màu', 'color'])) {
                            $colors[] = $val->value;
                            $variantLabels[] = $val->value;
                        } elseif (in_array($attrName, ['chất liệu', 'chat lieu', 'material'])) {
                            $materials[] = $val->value;
                        }
                    }

                    if (!empty($sizes)) $sizeText = implode(', ', $sizes);
                    if (!empty($colors)) $colorText = implode(', ', $colors);
                    if (!empty($materials)) $materialText = implode(', ', $materials);
                }

                $products->push((object)[
                    'key'            => $item['key'],
                    'id'             => $product->id,
                    'name'           => $product->name,
                    'slug'           => $product->slug,
                    'brand_name'     => $product->brand->name ?? '—',
                    'image'          => $displayImage,
                    'price'          => $displayPrice,
                    'original_price' => $product->price,
                    'discount'       => $product->discount,
                    'stock'          => $displayStock,
                    'size'           => $sizeText,
                    'color'          => $colorText,
                    'material'       => $materialText,
                    'variant_label'  => !empty($variantLabels) ? implode(' / ', $variantLabels) : null,
                    'sku'            => $selectedVariant ? $selectedVariant->sku : $product->sku,
                    'product'        => $product,
                    'variant'        => $selectedVariant,
                ]);
            }
        }

        return view('client.compare.index', compact('products'));
    }

    /**
     * Thêm sản phẩm vào danh sách so sánh (kèm biến thể nếu có)
     */
    public function add(Request $request, $id)
    {
        $product = Product::with('variants')->findOrFail($id);
        
        $variantId = $request->input('variant_id');

        // Nếu sản phẩm có biến thể mà request chưa có variant_id, fallback lấy biến thể đầu tiên
        if (!$variantId && $product->variants->isNotEmpty()) {
            $firstVariant = $product->variants->first();
            $variantId = $firstVariant ? $firstVariant->id : null;
        }

        // Tạo key duy nhất cho mỗi phân loại so sánh
        $key = $variantId ? "{$id}_{$variantId}" : (string)$id;

        $compareSession = session()->get('compare', []);

        // Chuẩn hóa danh sách hiện tại
        $normalized = [];
        foreach ($compareSession as $k => $item) {
            if (is_array($item)) {
                $pid = $item['product_id'] ?? null;
                $vid = $item['variant_id'] ?? null;
                if (!$pid) continue;
                $itemKey = $item['key'] ?? ($vid ? "{$pid}_{$vid}" : (string)$pid);
                $normalized[$itemKey] = [
                    'key'        => $itemKey,
                    'product_id' => (int)$pid,
                    'variant_id' => $vid ? (int)$vid : null,
                ];
            } else {
                $itemKey = (string)$item;
                $normalized[$itemKey] = [
                    'key'        => $itemKey,
                    'product_id' => (int)$item,
                    'variant_id' => null,
                ];
            }
        }

        if (isset($normalized[$key])) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'info', 'message' => 'Sản phẩm với phân loại này đã có trong danh sách so sánh.']);
            }
            return back()->with('info', 'Sản phẩm với phân loại này đã có trong danh sách so sánh.');
        }

        if (count($normalized) >= 4) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'warning', 'message' => 'Bạn chỉ có thể so sánh tối đa 4 sản phẩm cùng lúc.']);
            }
            return back()->with('warning', 'Bạn chỉ có thể so sánh tối đa 4 sản phẩm cùng lúc.');
        }

        $normalized[$key] = [
            'key'        => $key,
            'product_id' => $product->id,
            'variant_id' => $variantId ? (int)$variantId : null,
        ];

        session()->put('compare', $normalized);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Đã thêm sản phẩm vào danh sách so sánh.']);
        }
        return back()->with('success', 'Đã thêm sản phẩm vào danh sách so sánh.');
    }

    /**
     * Xóa sản phẩm khỏi danh sách so sánh theo key
     */
    public function remove(Request $request, $key)
    {
        $compareSession = session()->get('compare', []);
        
        $keyStr = (string)$key;

        if (is_array($compareSession)) {
            // Trường hợp key là associative array
            if (isset($compareSession[$keyStr])) {
                unset($compareSession[$keyStr]);
            } else {
                // Duyệt tìm nếu lưu dạng index array
                foreach ($compareSession as $k => $item) {
                    $itemKey = is_array($item) ? ($item['key'] ?? ($item['product_id'] ?? '')) : (string)$item;
                    $itemPid = is_array($item) ? ((string)($item['product_id'] ?? '')) : (string)$item;
                    if ((string)$itemKey === $keyStr || $itemPid === $keyStr || (string)$k === $keyStr) {
                        unset($compareSession[$k]);
                        break;
                    }
                }
            }
            session()->put('compare', $compareSession);
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Đã xóa sản phẩm khỏi danh sách so sánh.']);
        }
        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách so sánh.');
    }
}
