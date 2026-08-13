<?php

namespace App\Http\Requests\Client\Product;

use Illuminate\Foundation\Http\FormRequest;

class ClientProductSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|in:price_asc,price_desc,latest,popular',
        ];
    }
}
