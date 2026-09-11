<?php

namespace App\Modules\BenhNhan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\TaiKhoan\Models\TaiKhoan;
use App\Modules\LichHen\Models\LichHen;

class BenhNhan extends Model
{
    protected $table = 'benh_nhan';

    protected $fillable = [
        'tai_khoan_id',
        'ma_benh_nhan',
        'ho_ten',
        'so_dien_thoai',
        'so_cccd',
        'email',
        'gioi_tinh',
        'ngay_sinh',
        'dia_chi',
        'nhom_mau',
        'tien_su_benh',
        'tien_su_di_ung',
        'nguoi_lien_he_khan_cap',
        'sdt_khan_cap',
    ];

    public function taiKhoan(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }

    public function lichHen(): HasMany
    {
        return $this->hasMany(LichHen::class, 'benh_nhan_id');
    }
}
