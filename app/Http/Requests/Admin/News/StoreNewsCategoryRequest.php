<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewsCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục.',
        ];
    }
}
