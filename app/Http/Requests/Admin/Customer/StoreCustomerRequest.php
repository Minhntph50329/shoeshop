<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,locked',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'bank_name' => 'nullable|string|max:255',
            'user_bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'reason_lock' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
            'role.required' => 'Vui lòng chọn vai trò.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'avatar.image' => 'File tải lên phải là hình ảnh.',
            'avatar.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
