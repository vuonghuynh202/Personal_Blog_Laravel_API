<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image|max:2048',
            'role' => 'nullable|in:user,admin',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên người dùng không được bỏ trống.',
            'email.required' => 'Email không được bỏ trống.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Mật khẩu không được bỏ trống.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'image.image' => 'File phải là hình ảnh.',
            'image.max' => 'Hình ảnh không được lớn hơn 2MB.',
            'role.in' => 'Vai trò không hợp lệ, chỉ được chọn "user" hoặc "admin".',
        ];
    }
}
