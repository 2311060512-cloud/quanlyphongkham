<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BenhNhan extends Model
{
    protected $table = 'benh_nhan';

    protected $fillable = [
        'tai_khoan_id',
        'ma_benh_nhan',
        'ho_ten',
        'ngay_sinh',
        'gioi_tinh',
        'so_dien_thoai',
        'dia_chi',
        'tien_su_benh',
    ];

    public function danhSachLichHen(): HasMany
    {
        return $this->hasMany(LichHen::class, 'benh_nhan_id');
    }
}
