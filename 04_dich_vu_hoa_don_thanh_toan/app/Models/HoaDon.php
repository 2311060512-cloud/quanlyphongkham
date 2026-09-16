<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HoaDon extends Model
{
    protected $table = 'hoa_don';

    protected $fillable = [
        'ma_hoa_don',
        'lich_hen_id',
        'benh_nhan_id',
        'tien_kham',
        'tien_dich_vu',
        'tong_tien',
        'giam_gia',
        'thuc_thu',
        'phuong_thuc_thanh_toan', // TIEN_MAT, CHUYEN_KHOAN, VNPAY, MOMO
        'trang_thai', // CHUA_THANH_TOAN, DA_THANH_TOAN, HUY
        'ngay_thanh_toan',
        'ghi_chu',
    ];

    public function chiTiet(): HasMany
    {
        return $this->hasMany(ChiTietHoaDon::class, 'hoa_don_id');
    }
}
