<?php

namespace App\Modules\BacSi\Models;

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

    public function bacSi(): HasMany
    {
        return $this->hasMany(BacSi::class, 'chuyen_khoa_id');
    }
}
