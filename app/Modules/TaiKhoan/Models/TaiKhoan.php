<?php

namespace App\Modules\TaiKhoan\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Modules\BacSi\Models\BacSi;
use App\Modules\BenhNhan\Models\BenhNhan;

class TaiKhoan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tai_khoan';

    protected $fillable = [
        'ten_dang_nhap',
        'email',
        'mat_khau',
        'ho_ten',
        'so_dien_thoai',
        'vai_tro_id',
        'trang_thai',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function vaiTro(): BelongsTo
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    public function bacSi(): HasOne
    {
        return $this->hasOne(BacSi::class, 'tai_khoan_id');
    }

    public function benhNhan(): HasOne
    {
        return $this->hasOne(BenhNhan::class, 'tai_khoan_id');
    }
}
