<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichHen extends Model
{
    protected $table = 'lich_hen';

    protected $fillable = [
        'ma_lich_hen',
        'benh_nhan_id',
        'bac_si_id',
        'ngay_kham',
        'gio_bat_dau',
        'gio_ket_thuc',
        'ly_do_kham',
        'trang_thai', // CHO_XAC_NHAN, DA_XAC_NHAN, DANG_KHAM, HOAN_THANH, DA_HUY
        'chuan_doan',
        'loi_khuyen',
        'ghi_chu_bac_si',
        'ly_do_huy',
        'so_lan_doi_lich',
        'ly_do_doi_lich',
        'tep_dinh_kem',
        'toa_thuoc',
        'ngay_tai_kham',
    ];

    protected $casts = [
        'so_lan_doi_lich' => 'integer',
        'tep_dinh_kem' => 'array',
        'toa_thuoc' => 'array',
        'ngay_tai_kham' => 'date:Y-m-d',
    ];

    public function benhNhan(): BelongsTo
    {
        return $this->belongsTo(BenhNhan::class, 'benh_nhan_id');
    }

    // Accessor / Mutator cho phep dung ca trieu_chung va ly_do_kham
    public function getTrieuChungAttribute(): ?string
    {
        return $this->attributes['ly_do_kham'] ?? null;
    }

    public function setTrieuChungAttribute(?string $value): void
    {
        $this->attributes['ly_do_kham'] = $value;
    }

    public function getGioKhamAttribute(): ?string
    {
        return $this->attributes['gio_bat_dau'] ?? null;
    }
}
