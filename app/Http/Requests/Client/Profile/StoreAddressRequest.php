<?php

namespace App\Http\Requests\Client\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'fullname'     => 'required|string|max:255',
            'phone_number' => 'required|string|max:10',
            'address'      => 'required|string|max:500',
            'province'     => 'required|string|max:255',
            'district'     => 'required|string|max:255',
            'ward'         => 'required|string|max:255',
            'street'       => 'nullable|string|max:255',
            'address_type' => 'required|in:home,office,other',
            'is_default'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required'     => 'Vui lòng nhập họ tên người nhận.',
            'fullname.max'          => 'Họ tên không được vượt quá 255 ký tự.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'phone_number.max'      => 'Số điện thoại không được vượt quá 10 ký tự.',
            'address.required'      => 'Vui lòng nhập địa chỉ đầy đủ.',
            'address.max'           => 'Địa chỉ không được vượt quá 500 ký tự.',
            'province.required'     => 'Vui lòng chọn Tỉnh / Thành phố.',
            'district.required'     => 'Vui lòng chọn Quận / Huyện.',
            'ward.required'         => 'Vui lòng chọn Phường / Xã.',
            'address_type.required' => 'Vui lòng chọn loại địa chỉ.',
            'address_type.in'       => 'Loại địa chỉ không hợp lệ.',
        ];
    }
}
