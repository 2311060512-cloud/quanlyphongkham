<?php

namespace App\Modules\BacSi\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DoiTrangThaiBacSiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trang_thai' => 'required|in:DANG_LAM_VIEC,NGHI_PHEP,NGHI_VIEC',
        ];
    }

    public function messages(): array
    {
        return [
            'trang_thai.required' => 'Vui lòng chọn trạng thái làm việc mới.',
            'trang_thai.in' => 'Trạng thái chỉ chấp nhận: DANG_LAM_VIEC, NGHI_PHEP hoặc NGHI_VIEC.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'thanh_cong' => false,
            'ma_trang_thai' => 422,
            'thong_bao' => 'Trạng thái không hợp lệ',
            'chi_tiet_loi' => $validator->errors(),
            'thoi_gian' => now()->toIso8601String(),
        ], 422));
    }
}
