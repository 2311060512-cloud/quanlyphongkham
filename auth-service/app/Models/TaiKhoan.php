<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class TaiKhoan extends Model
{
    protected $table = 'tai_khoan';

    protected $fillable = [
        'ten_dang_nhap',
        'email',
        'mat_khau',
        'ho_ten',
        'avatar',
        'so_dien_thoai',
        'ngay_sinh',
        'gioi_tinh',
        'dia_chi',
        'vai_tro_id',
        'trang_thai',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    public function vaiTro(): BelongsTo
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    public function bacSi(): HasOne
    {
        return $this->hasOne(BacSi::class, 'tai_khoan_id');
    }

    public function tokens(): MorphMany
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    /**
     * Tao Bearer Token Sanctum chuan
     */
    public function taoTokenSanctum(string $name = 'auth_token', array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null): string
    {
        $plainTextToken = Str::random(40);

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        return "{$token->id}|{$plainTextToken}";
    }

    /**
     * Kiem tra tai khoan co dang hoat dong khong
     */
    public function dangHoatDong(): bool
    {
        return $this->trang_thai === 'HOAT_DONG' || $this->trang_thai == 1;
    }
}
