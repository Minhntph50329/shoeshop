<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReplyCommentRequest extends FormRequest
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
            'reply_content' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'reply_content.required' => 'Vui lòng nhập nội dung trả lời.',
        ];
    }
}
