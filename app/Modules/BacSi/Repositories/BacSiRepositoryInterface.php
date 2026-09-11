<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepositoryInterface;
use App\Modules\BacSi\Models\BacSi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BacSiRepositoryInterface extends BaseRepositoryInterface
{
    public function layDanhSachPhanTrang(int $soMoiTrang = 10, ?int $chuyenKhoaId = null, ?string $tuKhoa = null, ?string $trangThai = null): LengthAwarePaginator;
    public function layDanhSachTheoKhoa(?int $chuyenKhoaId = null, ?string $tuKhoa = null): Collection;
    public function chiTietKemQuanHe(int $id): ?BacSi;
    public function timTheoTaiKhoanId(int $taiKhoanId): ?BacSi;
    public function doiTrangThai(int $id, string $trangThai): ?BacSi;
}
