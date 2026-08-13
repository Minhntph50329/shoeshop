<?php

namespace App\Http\Requests\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('brand');
        return [
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu này đã tồn tại.',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
            'logo.image' => 'File tải lên phải là hình ảnh.',
            'logo.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg, webp.',
            'logo.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
