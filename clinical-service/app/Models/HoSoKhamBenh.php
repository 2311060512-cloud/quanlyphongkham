<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSoKhamBenh extends Model
{
    use HasFactory;

    protected $table = 'ho_so_kham_benh';

    protected $fillable = [
        'lich_hen_id',
        'benh_nhan_id',
        'bac_si_id',
        'trieu_chung',
        'chan_doan',
        'don_thuoc',
        'loi_dan_bac_si',
        'ngay_tai_kham',
        'trang_thai',
    ];

    protected $casts = [
        'don_thuoc' => 'array',
        'ngay_tai_kham' => 'date',
    ];
}
