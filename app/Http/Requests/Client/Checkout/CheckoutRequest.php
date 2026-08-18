<?php

namespace App\Http\Requests\Client\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'fullname'     => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email|max:255',
            'address'      => 'required|string|max:500',
            'payment_id'   => 'required|exists:payments,id',
            'shipping_type'=> 'required|in:standard,express',
            'note'         => 'nullable|string|max:500',
            'coupon_code'  => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required'    => 'Vui lòng nhập họ và tên người nhận.',
            'phone.required'       => 'Vui lòng nhập số điện thoại.',
            'email.required'       => 'Vui lòng nhập địa chỉ email.',
            'email.email'          => 'Địa chỉ email không đúng định dạng.',
            'address.required'     => 'Vui lòng nhập địa chỉ giao hàng.',
            'payment_id.required'  => 'Vui lòng chọn phương thức thanh toán.',
            'shipping_type.required'=> 'Vui lòng chọn phương thức vận chuyển.',
        ];
    }
}
