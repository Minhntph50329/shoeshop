<?php

namespace App\Http\Requests\Client\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Vui lòng nhập họ tên của bạn.',
            'email.required'   => 'Vui lòng nhập địa chỉ email.',
            'email.email'      => 'Địa chỉ email không đúng định dạng.',
            'message.required' => 'Vui lòng nhập nội dung liên hệ.',
            'message.min'      => 'Nội dung liên hệ phải có ít nhất 10 ký tự.',
        ];
    }
}
