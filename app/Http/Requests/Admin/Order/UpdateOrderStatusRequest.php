<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_id' => 'required|exists:order_statuses,id',
            'note'      => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'status_id.required' => 'Vui lòng chọn trạng thái đơn hàng.',
            'status_id.exists' => 'Trạng thái đơn hàng không hợp lệ.',
            'note.max' => 'Ghi chú không được vượt quá 500 ký tự.',
        ];
    }
}
