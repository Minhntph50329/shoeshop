<?php

namespace App\Http\Requests\Client\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'fullname'       => 'required|string|max:255',
            'phone_number'   => 'nullable|string|max:20',
            'gender'         => 'nullable|in:male,female,other',
            'birthday'       => 'nullable|date',
            'bank_name'      => 'nullable|string|max:255',
            'user_bank_name' => 'nullable|string|max:255',
            'bank_account'   => 'nullable|string|max:255',
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Vui lòng nhập họ và tên.',
            'avatar.image'      => 'File ảnh không hợp lệ.',
            'avatar.max'        => 'Dung lượng ảnh tối đa 2MB.',
        ];
    }
}
