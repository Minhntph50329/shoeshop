<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'required|boolean',
            'alow_comments' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết.',
            'content.required' => 'Vui lòng nhập nội dung bài viết.',
            'thumbnail.image' => 'File tải lên phải là hình ảnh.',
            'thumbnail.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'thumbnail.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
