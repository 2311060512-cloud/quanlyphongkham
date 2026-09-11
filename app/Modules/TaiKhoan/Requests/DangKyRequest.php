<?php

namespace App\Modules\TaiKhoan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DangKyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_dang_nhap' => 'required|string|min:3|max:100|unique:tai_khoan,ten_dang_nhap',
            'email' => 'required|email|max:150|unique:tai_khoan,email',
            'mat_khau' => 'required|string|min:6',
            'ho_ten' => 'required|string|max:150',
            'so_dien_thoai' => 'nullable|string|max:20',
            'gioi_tinh' => 'nullable|in:NAM,NU,KHAC',
            'ngay_sinh' => 'nullable|date',
            'dia_chi' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'ten_dang_nhap.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự.',
            'ten_dang_nhap.unique' => 'Tên đăng nhập này đã được sử dụng.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'ho_ten.required' => 'Vui lòng nhập họ và tên.',
            'gioi_tinh.in' => 'Giới tính chỉ chấp nhận: NAM, NU hoặc KHAC.',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng ngày tháng.',
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
