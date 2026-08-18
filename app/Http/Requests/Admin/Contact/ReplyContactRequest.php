<?php

namespace App\Http\Requests\Admin\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ReplyContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reply_message' => 'required|string|min:5|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'reply_message.required' => 'Vui lòng nhập nội dung phản hồi.',
            'reply_message.min' => 'Nội dung phản hồi phải có ít nhất 5 ký tự.',
        ];
    }
}
