<?php

namespace App\Http\Requests\Client\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $rules = [
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ];

        if (!Auth::check()) {
            $rules['user_name']  = 'required|string|max:255';
            $rules['user_email'] = 'required|email|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'content.required'    => 'Vui lòng nhập nội dung bình luận.',
            'user_name.required'  => 'Vui lòng nhập họ tên của bạn.',
            'user_email.required' => 'Vui lòng nhập email hợp lệ.',
            'user_email.email'    => 'Email không đúng định dạng.',
        ];
    }
}
