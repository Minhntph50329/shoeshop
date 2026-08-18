<?php

namespace App\Http\Requests\Client\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required'         => 'Vui lòng nhập mật khẩu mới.',
            'password.min'              => 'Mật khẩu mới tối thiểu 6 ký tự.',
            'password.confirmed'        => 'Mật khẩu xác nhận không khớp.',
        ];
    }
}
