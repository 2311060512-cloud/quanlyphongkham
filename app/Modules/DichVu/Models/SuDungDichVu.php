<?php

namespace App\Modules\DichVu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\LichHen\Models\LichHen;

class SuDungDichVu extends Model
{
    protected $table = 'su_dung_dich_vu';

    protected $fillable = [
        'lich_hen_id',
        'dich_vu_id',
        'so_luong',
        'don_gia',
        'thanh_tien',
        'ket_qua',
        'ghi_chu',
    ];

    public function lichHen(): BelongsTo
    {
        return $this->belongsTo(LichHen::class, 'lich_hen_id');
    }

    public function dichVu(): BelongsTo
    {
        return $this->belongsTo(DichVu::class, 'dich_vu_id');
    }
}
