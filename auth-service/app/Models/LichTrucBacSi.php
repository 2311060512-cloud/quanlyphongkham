<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichTrucBacSi extends Model
{
    protected $table = 'lich_truc_bac_si';

    protected $fillable = [
        'bac_si_id',
        'thu',
        'ngay_trong_tuan',
        'ca_truc',
        'gio_bat_dau',
        'gio_ket_thuc',
        'so_luong_kham_toi_da',
        'phong_kham',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_trong_tuan' => 'integer',
        'so_luong_kham_toi_da' => 'integer',
    ];

    public function bacSi(): BelongsTo
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }
}
