<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\BacSi\Models\BacSi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BacSiRepository extends BaseRepository implements BacSiRepositoryInterface
{
    public function __construct(BacSi $model)
    {
        parent::__construct($model);
    }

    public function layDanhSachPhanTrang(int $soMoiTrang = 10, ?int $chuyenKhoaId = null, ?string $tuKhoa = null, ?string $trangThai = null): LengthAwarePaginator
    {
        $truyVan = $this->model->with(['chuyenKhoa', 'taiKhoan']);

        if ($chuyenKhoaId) {
            $truyVan->where('chuyen_khoa_id', $chuyenKhoaId);
        }

        if ($trangThai) {
            $truyVan->where('trang_thai', $trangThai);
        }

        if ($tuKhoa) {
            $truyVan->where(function ($q) use ($tuKhoa) {
                $q->where('ho_ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('ma_bac_si', 'like', "%{$tuKhoa}%")
                  ->orWhere('hoc_vi', 'like', "%{$tuKhoa}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$tuKhoa}%")
                  ->orWhere('phong_kham', 'like', "%{$tuKhoa}%");
            });
        }

        return $truyVan->latest('id')->paginate($soMoiTrang);
    }

    public function layDanhSachTheoKhoa(?int $chuyenKhoaId = null, ?string $tuKhoa = null): Collection
    {
        $query = $this->model->with('chuyenKhoa')->where('trang_thai', 'DANG_LAM_VIEC');

        if ($chuyenKhoaId) {
            $query->where('chuyen_khoa_id', $chuyenKhoaId);
        }

        if (!empty($tuKhoa)) {
            $tuKhoa = trim($tuKhoa);
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ho_ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('hoc_vi', 'like', "%{$tuKhoa}%")
                  ->orWhere('phong_kham', 'like', "%{$tuKhoa}%")
                  ->orWhere('kinh_nghiem', 'like', "%{$tuKhoa}%")
                  ->orWhereHas('chuyenKhoa', function ($ckQuery) use ($tuKhoa) {
                      $ckQuery->where('ten_khoa', 'like', "%{$tuKhoa}%");
                  });
            });
        }

        return $query->get();
    }

    public function chiTietKemQuanHe(int $id): ?BacSi
    {
        return $this->model->with(['chuyenKhoa', 'taiKhoan'])->find($id);
    }

    public function timTheoTaiKhoanId(int $taiKhoanId): ?BacSi
    {
        return $this->model->with('chuyenKhoa')->where('tai_khoan_id', $taiKhoanId)->first();
    }

    public function doiTrangThai(int $id, string $trangThai): ?BacSi
    {
        $bacSi = $this->timTheoId($id);
        if (!$bacSi) {
            return null;
        }

        $bacSi->trang_thai = $trangThai;
        $bacSi->save();

        return $bacSi->fresh(['chuyenKhoa', 'taiKhoan']);
    }
}
