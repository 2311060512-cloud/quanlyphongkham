<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietHoaDon extends Model
{
    protected $table = 'chi_tiet_hoa_don';

    protected $fillable = [
        'hoa_don_id',
        'loai_khoan_thu', // TIEN_KHAM, DICH_VU_CLS
        'ten_khoan_thu',
        'so_luong',
        'don_gia',
        'thanh_tien',
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'don_gia' => 'float',
        'thanh_tien' => 'float',
    ];

    public function hoaDon(): BelongsTo
    {
        return $this->belongsTo(HoaDon::class, 'hoa_don_id');
    }
}
