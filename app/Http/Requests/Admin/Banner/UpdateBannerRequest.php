<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|string|max:500',
            'poisition' => 'required|string|max:100',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'File ảnh không hợp lệ.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
            'poisition.required' => 'Vui lòng chọn vị trí hiển thị.',
            'sort_order.required' => 'Vui lòng nhập thứ tự hiển thị.',
        ];
    }
}
