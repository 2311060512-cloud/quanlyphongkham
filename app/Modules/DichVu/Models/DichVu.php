<?php

namespace App\Modules\DichVu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DichVu extends Model
{
    protected $table = 'dich_vu';

    protected $fillable = [
        'ma_dich_vu',
        'ten_dich_vu',
        'loai_dich_vu', // XET_NGHIEM, CHIEU_CHUP, THU_THUAT, NOI_SOI
        'don_gia',
        'mo_ta',
        'trang_thai',
    ];

    public function suDungDichVu(): HasMany
    {
        return $this->hasMany(SuDungDichVu::class, 'dich_vu_id');
    }
}
