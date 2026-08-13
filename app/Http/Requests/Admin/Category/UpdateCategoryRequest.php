<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category');
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id' => 'nullable|exists:categories,id|different:id',
            'icon' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'parent_id.exists' => 'Danh mục cha được chọn không hợp lệ.',
            'parent_id.different' => 'Danh mục cha không thể là chính danh mục này.',
        ];
    }
}
