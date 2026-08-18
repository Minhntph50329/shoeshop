<?php

namespace App\Http\Requests\Client\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'fullname'     => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'province'     => 'required|string|max:255',
            'district'     => 'required|string|max:255',
            'ward'         => 'required|string|max:255',
            'street'       => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'address_type' => 'required|in:home,office,other',
            'is_default'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required'     => 'Vui lòng nhập họ và tên người nhận.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'province.required'     => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required'     => 'Vui lòng chọn Quận/Huyện.',
            'ward.required'         => 'Vui lòng chọn Phường/Xã.',
            'street.required'       => 'Vui lòng nhập tên đường, tòa nhà...',
            'address.required'      => 'Vui lòng nhập địa chỉ cụ thể.',
        ];
    }
}
