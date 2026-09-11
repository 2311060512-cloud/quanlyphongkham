<?php

namespace App\Modules\BacSi\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CapNhatBacSiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_ten' => 'sometimes|required|string|max:150',
            'chuyen_khoa_id' => 'sometimes|required|exists:chuyen_khoa,id',
            'hoc_vi' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'gia_kham' => 'sometimes|required|numeric|min:0',
            'phong_kham' => 'sometimes|required|string|max:100',
            'kinh_nghiem' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'chuyen_khoa_id.exists' => 'Chuyên khoa đã chọn không tồn tại.',
            'gia_kham.numeric' => 'Giá khám phải là định dạng số.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'thanh_cong' => false,
            'ma_trang_thai' => 422,
            'thong_bao' => 'Thông tin cập nhật không hợp lệ',
            'chi_tiet_loi' => $validator->errors(),
            'thoi_gian' => now()->toIso8601String(),
        ], 422));
    }
}
