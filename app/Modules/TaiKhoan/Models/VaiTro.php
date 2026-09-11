<?php

namespace App\Modules\TaiKhoan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VaiTro extends Model
{
    protected $table = 'vai_tro';

    protected $fillable = [
        'ma_vai_tro',
        'ten_vai_tro',
        'mo_ta',
    ];

    public function taiKhoan(): HasMany
    {
        return $this->hasMany(TaiKhoan::class, 'vai_tro_id');
    }
}
