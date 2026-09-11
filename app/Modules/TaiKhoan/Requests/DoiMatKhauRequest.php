<?php

namespace App\Modules\TaiKhoan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DoiMatKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mat_khau_cu' => 'required|string',
            'mat_khau_moi' => 'required|string|min:6|different:mat_khau_cu',
            'mat_khau_moi_confirmation' => 'required|same:mat_khau_moi',
        ];
    }

    public function messages(): array
    {
        return [
            'mat_khau_cu.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'mat_khau_moi.different' => 'Mật khẩu mới không được trùng với mật khẩu cũ.',
            'mat_khau_moi_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'mat_khau_moi_confirmation.same' => 'Xác nhận mật khẩu mới không trùng khớp.',
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
