<?php

namespace App\Http\Requests\Admin\Review;

use Illuminate\Foundation\Http\FormRequest;

class ReplyReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reply_text' => 'required|string|min:5|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'reply_text.required' => 'Vui lòng nhập nội dung trả lời.',
            'reply_text.min' => 'Nội dung trả lời phải có ít nhất 5 ký tự.',
            'reply_text.max' => 'Nội dung trả lời không được vượt quá 1000 ký tự.',
        ];
    }
}
