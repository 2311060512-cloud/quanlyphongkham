<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuDungDichVu extends Model
{
    protected $table = 'su_dung_dich_vu';

    protected $fillable = [
        'lich_hen_id',
        'benh_nhan_id',
        'bac_si_id',
        'dich_vu_id',
        'so_luong',
        'don_gia',
        'ket_qua',
        'ghi_chu',
        'file_ket_qua',
        'trang_thai', // CHO_THUC_HIEN, DA_CO_KET_QUA, DA_HUY
    ];

    public function dichVu(): BelongsTo
    {
        return $this->belongsTo(DichVu::class, 'dich_vu_id');
    }
}
