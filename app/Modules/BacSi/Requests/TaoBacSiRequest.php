<?php

namespace App\Modules\BacSi\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaoBacSiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_ten' => 'required|string|max:150',
            'chuyen_khoa_id' => 'required|exists:chuyen_khoa,id',
            'hoc_vi' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'gia_kham' => 'required|numeric|min:0',
            'phong_kham' => 'required|string|max:100',
            'hinh_anh' => 'nullable|string|max:255',
            'ca_lam_viec' => 'nullable|string|in:CA_SANG,CA_CHIEU,CA_NGAY,NGAY_NGHI',
            'kinh_nghiem' => 'nullable|string',
            'ten_dang_nhap' => 'nullable|string|min:3|max:100|unique:tai_khoan,ten_dang_nhap',
            'mat_khau' => 'nullable|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Vui lòng nhập họ tên bác sĩ.',
            'chuyen_khoa_id.required' => 'Vui lòng chọn chuyên khoa cho bác sĩ.',
            'chuyen_khoa_id.exists' => 'Chuyên khoa đã chọn không tồn tại trong hệ thống.',
            'gia_kham.required' => 'Vui lòng nhập đơn giá khám bệnh.',
            'gia_kham.numeric' => 'Giá khám phải là định dạng số.',
            'phong_kham.required' => 'Vui lòng chỉ định số phòng khám.',
            'ten_dang_nhap.unique' => 'Tên đăng nhập này đã được sử dụng.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'thanh_cong' => false,
            'ma_trang_thai' => 422,
            'thong_bao' => 'Thông tin bác sĩ không hợp lệ',
            'chi_tiet_loi' => $validator->errors(),
            'thoi_gian' => now()->toIso8601String(),
        ], 422));
    }
}
