<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChuyenKhoa extends Model
{
    protected $table = 'chuyen_khoa';

    protected $fillable = [
        'ma_khoa',
        'ten_khoa',
        'mo_ta',
        'hinh_anh',
        'trang_thai',
    ];

    protected $appends = [
        'ten_chuyen_khoa',
        'ma_chuyen_khoa',
    ];

    public function bacSi(): HasMany
    {
        return $this->hasMany(BacSi::class, 'chuyen_khoa_id');
    }

    // Accessors tuong thich ten truong cu
    public function getMaChuyenKhoaAttribute(): ?string
    {
        return $this->ma_khoa;
    }

    public function getTenChuyenKhoaAttribute(): ?string
    {
        return $this->ten_khoa;
    }
}
