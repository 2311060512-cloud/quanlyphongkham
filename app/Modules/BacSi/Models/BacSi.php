<?php

namespace App\Modules\BacSi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\TaiKhoan\Models\TaiKhoan;
use App\Modules\LichHen\Models\LichHen;

class BacSi extends Model
{
    protected $table = 'bac_si';

    protected $fillable = [
        'tai_khoan_id',
        'chuyen_khoa_id',
        'ma_bac_si',
        'ho_ten',
        'hinh_anh',
        'hoc_vi',
        'so_dien_thoai',
        'email',
        'gia_kham',
        'phong_kham',
        'kinh_nghiem',
        'trang_thai',
    ];

    public function taiKhoan(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }

    public function chuyenKhoa(): BelongsTo
    {
        return $this->belongsTo(ChuyenKhoa::class, 'chuyen_khoa_id');
    }

    public function lichHen(): HasMany
    {
        return $this->hasMany(LichHen::class, 'bac_si_id');
    }
}
