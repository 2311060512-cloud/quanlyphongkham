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
        'so_dien_thoai',
        'so_cccd',
        'ngay_sinh',
        'gioi_tinh',
        'dia_chi',
        'nhom_mau',
        'tien_su_di_ung',
        'tien_su_benh',
        'nguoi_lien_he_khan_cap',
        'sdt_khan_cap',
        'quan_he_chu_tai_khoan',
    ];

    public function danhSachLichHen(): HasMany
    {
        return $this->hasMany(LichHen::class, 'benh_nhan_id');
    }
}
