<?php

namespace App\Http\Requests\Admin\Refund;

use Illuminate\Foundation\Http\FormRequest;

class HandleRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action'             => 'required|in:approve,reject,complete',
            'aadmin_reason'      => 'nullable|string|max:1000',
            'img_refunded_money' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Vui lòng chọn hành động xử lý.',
            'action.in' => 'Hành động không hợp lệ.',
            'aadmin_reason.max' => 'Lý do không được vượt quá 1000 ký tự.',
            'img_refunded_money.image' => 'File tải lên phải là hình ảnh.',
            'img_refunded_money.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
