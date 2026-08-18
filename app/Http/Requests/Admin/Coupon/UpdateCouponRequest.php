<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
        $id = $this->route('voucher');
        return [
            'code' => ['required', 'string', 'max:50', \Illuminate\Validation\Rule::unique('coupons')->ignore($id)],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
            'is_notified' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã Voucher.',
            'code.unique' => 'Mã Voucher này đã tồn tại.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'is_active.required' => 'Vui lòng chọn trạng thái kích hoạt.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
