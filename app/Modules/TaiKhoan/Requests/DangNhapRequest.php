<?php

namespace App\Modules\TaiKhoan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DangNhapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'thanh_cong' => false,
            'ma_trang_thai' => 422,
            'thong_bao' => 'Dữ liệu không hợp lệ',
            'chi_tiet_loi' => $validator->errors(),
            'thoi_gian' => now()->toIso8601String(),
        ], 422));
    }
}
