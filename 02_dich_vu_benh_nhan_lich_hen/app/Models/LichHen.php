<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichHen extends Model
{
    protected $table = 'lich_hen';

    protected $fillable = [
        'benh_nhan_id',
        'bac_si_id',
        'ngay_kham',
        'gio_bat_dau',
        'gio_ket_thuc',
        'ly_do_kham',
        'trang_thai', // CHO_KHAM, DANG_KHAM, HOAN_THANH, DA_HUY
        'ghi_chu_bac_si',
    ];

    public function benhNhan(): BelongsTo
    {
        return $this->belongsTo(BenhNhan::class, 'benh_nhan_id');
    }
}
