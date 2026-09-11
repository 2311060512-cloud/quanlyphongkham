<?php

namespace App\Modules\HoaDon\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\LichHen\Models\LichHen;
use App\Modules\BenhNhan\Models\BenhNhan;

class HoaDon extends Model
{
    protected $table = 'hoa_don';

    protected $fillable = [
        'ma_hoa_don',
        'lich_hen_id',
        'benh_nhan_id',
        'tien_kham',
        'tien_dich_vu',
        'tong_tien',
        'phuong_thuc_thanh_toan', // TIEN_MAT, CHUYEN_KHOAN, VNPAY, THE
        'trang_thai', // CHUA_THANH_TOAN, DA_THANH_TOAN
        'ngay_thanh_toan',
        'ghi_chu',
    ];

    public function lichHen(): BelongsTo
    {
        return $this->belongsTo(LichHen::class, 'lich_hen_id');
    }

    public function benhNhan(): BelongsTo
    {
        return $this->belongsTo(BenhNhan::class, 'benh_nhan_id');
    }
}
