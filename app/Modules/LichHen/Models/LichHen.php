<?php

namespace App\Modules\LichHen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Modules\BenhNhan\Models\BenhNhan;
use App\Modules\BacSi\Models\BacSi;
use App\Modules\DichVu\Models\SuDungDichVu;
use App\Modules\HoaDon\Models\HoaDon;

class LichHen extends Model
{
    protected $table = 'lich_hen';

    protected $fillable = [
        'ma_lich_hen',
        'benh_nhan_id',
        'bac_si_id',
        'ngay_kham',
        'gio_kham',
        'trieu_chung',
        'chuan_doan',
        'loi_khuyen',
        'trang_thai', // CHO_XAC_NHAN, DA_XAC_NHAN, DANG_KHAM, HOAN_THANH, DA_HUY
        'ghi_chu',
    ];

    public function benhNhan(): BelongsTo
    {
        return $this->belongsTo(BenhNhan::class, 'benh_nhan_id');
    }

    public function bacSi(): BelongsTo
    {
        return $this->belongsTo(BacSi::class, 'bac_si_id');
    }

    public function suDungDichVu(): HasMany
    {
        return $this->hasMany(SuDungDichVu::class, 'lich_hen_id');
    }

    public function hoaDon(): HasOne
    {
        return $this->hasOne(HoaDon::class, 'lich_hen_id');
    }
}
