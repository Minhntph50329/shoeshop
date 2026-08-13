<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'Vui lòng nhập giá trị thuộc tính.',
            'value.max' => 'Giá trị thuộc tính không được quá 255 ký tự.',
        ];
    }
}
