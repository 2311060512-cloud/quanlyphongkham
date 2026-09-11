<?php

namespace App\Modules\BacSi\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaoChuyenKhoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ma_khoa' => 'required|string|max:50|unique:chuyen_khoa,ma_khoa',
            'ten_khoa' => 'required|string|max:150',
            'mo_ta' => 'nullable|string',
            'hinh_anh' => 'nullable|string|max:255',
            'trang_thai' => 'nullable|in:HOAT_DONG,TAM_DONG',
        ];
    }

    public function messages(): array
    {
        return [
            'ma_khoa.required' => 'Vui lòng nhập mã chuyên khoa.',
            'ma_khoa.unique' => 'Mã chuyên khoa này đã tồn tại.',
            'ten_khoa.required' => 'Vui lòng nhập tên chuyên khoa.',
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
