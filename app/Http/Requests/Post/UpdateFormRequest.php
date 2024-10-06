<?php

namespace App\Http\Requests\Post;

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
            'title' => 'required|string|max:255',
            'slug' => 'nullable|unique:posts,slug,' . $this->id,
            'content' => 'required',
            'image' => 'nullable|image',
            'status' => 'nullable|in:draft,published,private',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được bỏ trống.',
            'slug.unique' => 'Tiêu đề đã tồn tại.',
            'content.required' => 'Nội dung bài viết không được bỏ trống.',
            'image.image' => 'Ảnh có định dạng không hợp lệ.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'category_ids.*.exists' => 'Danh mục không hợp lệ.',
            'tags.*.string' => 'Thẻ phải là chuỗi ký tự.',
        ];
    }
}
