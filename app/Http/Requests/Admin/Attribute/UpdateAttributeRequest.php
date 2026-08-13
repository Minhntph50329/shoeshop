<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('attribute');
        return [
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên thuộc tính.',
            'name.unique' => 'Tên thuộc tính này đã tồn tại.',
            'name.max' => 'Tên thuộc tính không được vượt quá 255 ký tự.',
        ];
    }
}
