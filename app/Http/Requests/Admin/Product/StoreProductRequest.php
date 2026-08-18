<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_type' => 'required|in:simple,variable',
            'name' => 'required|string|max:255',
            'price' => 'required_if:product_type,simple|nullable|numeric|min:0',
            'stock' => 'required_if:product_type,simple|nullable|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'discount' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'status' => 'required|in:draft,active,hidden',
        ];
    }

    public function messages(): array
    {
        return [
            'product_type.required' => 'Vui lòng chọn loại sản phẩm.',
            'product_type.in' => 'Loại sản phẩm không hợp lệ.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không vượt quá 255 ký tự.',
            'price.required_if' => 'Vui lòng nhập giá bán sản phẩm khi chọn loại sản phẩm đơn giản.',
            'price.numeric' => 'Giá bán phải là số.',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'stock.required_if' => 'Vui lòng nhập số lượng tồn kho khi chọn loại sản phẩm đơn giản.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'sku.unique' => 'Mã SKU này đã tồn tại trên hệ thống.',
            'image.image' => 'File ảnh đại diện không hợp lệ.',
            'image.max' => 'Dung lượng ảnh đại diện tối đa 2MB.',
            'gallery.*.image' => 'File trong thư viện ảnh không hợp lệ.',
            'gallery.*.max' => 'Dung lượng từng ảnh trong thư viện tối đa 2MB.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu được chọn không tồn tại.',
            'category_ids.required' => 'Vui lòng chọn danh mục.',
            'category_ids.*.exists' => 'Danh mục được chọn không tồn tại.',
            'discount_end.after_or_equal' => 'Thời gian kết thúc giảm giá phải sau hoặc bằng thời gian bắt đầu.',
        ];
    }
}
