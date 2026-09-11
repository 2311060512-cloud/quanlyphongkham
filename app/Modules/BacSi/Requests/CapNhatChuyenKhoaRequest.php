<?php

namespace App\Modules\BacSi\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CapNhatChuyenKhoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_khoa' => 'sometimes|required|string|max:150',
            'mo_ta' => 'nullable|string',
            'hinh_anh' => 'nullable|string|max:255',
            'trang_thai' => 'nullable|in:HOAT_DONG,TAM_DONG',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_khoa.required' => 'Tên chuyên khoa không được để trống.',
            'trang_thai.in' => 'Trạng thái chỉ chấp nhận: HOAT_DONG hoặc TAM_DONG.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'thanh_cong' => false,
            'ma_trang_thai' => 422,
            'thong_bao' => 'Dữ liệu chuyên khoa không hợp lệ',
            'chi_tiet_loi' => $validator->errors(),
            'thoi_gian' => now()->toIso8601String(),
        ], 422));
    }
}
