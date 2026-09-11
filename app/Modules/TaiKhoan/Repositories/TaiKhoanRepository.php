<?php

namespace App\Modules\TaiKhoan\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\TaiKhoan\Models\TaiKhoan;

class TaiKhoanRepository extends BaseRepository implements TaiKhoanRepositoryInterface
{
    public function __construct(TaiKhoan $model)
    {
        parent::__construct($model);
    }

    public function timTheoTenDangNhap(string $tenDangNhap): ?TaiKhoan
    {
        return $this->model->with('vaiTro')->where('ten_dang_nhap', $tenDangNhap)->first();
    }

    public function timTheoEmail(string $email): ?TaiKhoan
    {
        return $this->model->with('vaiTro')->where('email', $email)->first();
    }
}
