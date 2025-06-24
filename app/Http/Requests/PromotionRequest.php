<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                Rule::unique('promotions', 'code')->ignore($this->promotion),
            ],
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',

            // ✅ Validate sản phẩm
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',

            // ✅ Validate danh mục
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã khuyến mãi.',
            'code.unique' => 'Mã khuyến mãi đã tồn tại.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'discount_value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng 0.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
            'usage_limit.integer' => 'Giới hạn lượt dùng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn lượt dùng phải lớn hơn 0.',
            'is_active.boolean' => 'Trạng thái kích hoạt không hợp lệ.',

            // ✅ Sản phẩm
            'product_ids.array' => 'Danh sách sản phẩm không hợp lệ.',
            'product_ids.*.exists' => 'Một hoặc nhiều sản phẩm đã chọn không tồn tại.',

            // ✅ Danh mục
            'category_ids.array' => 'Danh sách danh mục không hợp lệ.',
            'category_ids.*.exists' => 'Một hoặc nhiều danh mục đã chọn không tồn tại.',
        ];
    }
}
