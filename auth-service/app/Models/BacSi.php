<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BacSi extends Model
{
    protected $table = 'bac_si';

    protected $fillable = [
        'tai_khoan_id',
        'chuyen_khoa_id',
        'ma_bac_si',
        'ho_ten',
        'avatar',
        'hoc_vi',
        'so_dien_thoai',
        'email',
        'gia_kham',
        'phong_kham',
        'kinh_nghiem',
        'trang_thai',
    ];

    protected $casts = [
        'gia_kham' => 'float',
    ];

    public function taiKhoan(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }

    public function chuyenKhoa(): BelongsTo
    {
        return $this->belongsTo(ChuyenKhoa::class, 'chuyen_khoa_id');
    }

    public function lichTruc()
    {
        return $this->hasMany(LichTrucBacSi::class, 'bac_si_id');
    }

    // Accessors tuong thich ten truong cu neu co
    public function getSoNamKinhNghiemAttribute()
    {
        return $this->kinh_nghiem;
    }
}
