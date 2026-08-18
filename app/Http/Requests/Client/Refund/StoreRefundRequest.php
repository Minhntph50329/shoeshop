<?php

namespace App\Http\Requests\Client\Refund;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefundRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'bank_account'     => 'required|string|max:100',
            'user_bank_name'   => 'required|string|max:255',
            'bank_name'        => 'required|string|max:100',
            'reason'           => 'required|string|max:1000',
            'reason_image'     => 'nullable|image|max:2048',
            'items'            => 'required|array',
            'items.*.quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'bank_account.required'   => 'Vui lòng nhập số tài khoản ngân hàng.',
            'user_bank_name.required' => 'Vui lòng nhập tên chủ tài khoản.',
            'bank_name.required'      => 'Vui lòng nhập tên ngân hàng.',
            'reason.required'         => 'Vui lòng nhập lý do trả hàng.',
            'items.required'          => 'Vui lòng chọn sản phẩm cần trả.',
            'reason_image.image'      => 'File tải lên phải là hình ảnh.',
            'reason_image.max'        => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
