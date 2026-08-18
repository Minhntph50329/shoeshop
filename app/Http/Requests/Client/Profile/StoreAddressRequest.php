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
            'phone_number' => 'required|string|max:20',
            'address'      => 'required|string|max:500',
            'province'     => 'nullable|string|max:255',
            'district'     => 'nullable|string|max:255',
            'ward'         => 'nullable|string|max:255',
            'street'       => 'nullable|string|max:255',
            'address_type' => 'required|in:home,office,other',
            'is_default'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required'     => 'Vui lòng nhập người nhận.',
            'phone_number.required' => 'Vui lòng nhập sđt người nhận.',
            'address.required'      => 'Vui lòng nhập chi tiết địa chỉ.',
            'address_type.required' => 'Vui lòng chọn loại địa chỉ.',
        ];
    }
}
