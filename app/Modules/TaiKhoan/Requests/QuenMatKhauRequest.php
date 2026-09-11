<?php

namespace App\Modules\TaiKhoan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuenMatKhauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:tai_khoan,email',
            'mat_khau_moi' => 'nullable|string|min:6',
            'mat_khau_moi_confirmation' => 'nullable|required_with:mat_khau_moi|same:mat_khau_moi',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng cung cấp địa chỉ email đã đăng ký.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.exists' => 'Không tìm thấy tài khoản liên kết với địa chỉ email này.',
            'mat_khau_moi.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'mat_khau_moi_confirmation.required_with' => 'Vui lòng xác nhận mật khẩu mới.',
            'mat_khau_moi_confirmation.same' => 'Xác nhận mật khẩu mới không trùng khớp.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'thanh_cong' => false,
            'ma_trang_thai' => 422,
            'thong_bao' => 'Thông tin khôi phục mật khẩu không hợp lệ',
            'chi_tiet_loi' => $validator->errors(),
            'thoi_gian' => now()->toIso8601String(),
        ], 422));
    }
}
