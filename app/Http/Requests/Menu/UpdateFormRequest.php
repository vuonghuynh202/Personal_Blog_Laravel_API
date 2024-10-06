<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormRequest extends FormRequest
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
            'slug' => 'unique:menus,slug,' . $this->id, // Bỏ qua kiểm tra unique cho menu hiện tại
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên menu không được bỏ trống.',
            'slug.unique' => 'Menu này đã tồn tại.',
            'url.max' => 'URL không được vượt quá :max ký tự.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
            'order.integer' => 'Thứ tự phải là số nguyên.',
            'status.boolean' => 'Trạng thái phải là kiểu boolean.',
        ];
    }
}
