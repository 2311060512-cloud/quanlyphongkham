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

    protected $casts = [
        'tien_kham' => 'float',
        'tien_dich_vu' => 'float',
        'tong_tien' => 'float',
        'giam_gia' => 'float',
        'thuc_thu' => 'float',
        'ngay_thanh_toan' => 'datetime',
    ];

    /**
     * Tu dong sinh ma hoa don dang HD0001, HD0002... khi tao moi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hoaDon) {
            if (empty($hoaDon->ma_hoa_don)) {
                $maxId = static::max('id') ?? 0;
                $nextNumber = $maxId + 1;
                $hoaDon->ma_hoa_don = 'HD' . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function chiTiet(): HasMany
    {
        return $this->hasMany(ChiTietHoaDon::class, 'hoa_don_id');
    }
}
