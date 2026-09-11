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

    public function layDanhSachCoPhanTrang(int $soMoiTrang = 10, ?int $vaiTroId = null, ?string $tuKhoa = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        $truyVan = $this->model->with('vaiTro');

        if ($vaiTroId) {
            $truyVan->where('vai_tro_id', $vaiTroId);
        }

        if ($tuKhoa) {
            $truyVan->where(function ($q) use ($tuKhoa) {
                $q->where('ten_dang_nhap', 'like', "%{$tuKhoa}%")
                  ->orWhere('ho_ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('email', 'like', "%{$tuKhoa}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$tuKhoa}%");
            });
        }

        return $truyVan->latest('id')->paginate($soMoiTrang);
    }

    public function chuyenTrangThai(int $id): ?TaiKhoan
    {
        $taiKhoan = $this->timTheoId($id);
        if (!$taiKhoan) {
            return null;
        }

        $taiKhoan->trang_thai = ($taiKhoan->trang_thai === 'HOAT_DONG') ? 'TAM_KHOA' : 'HOAT_DONG';
        $taiKhoan->save();

        return $taiKhoan->fresh('vaiTro');
    }

    public function doiMatKhau(int $id, string $matKhauMoiHash): bool
    {
        $taiKhoan = $this->timTheoId($id);
        if (!$taiKhoan) {
            return false;
        }

        $taiKhoan->mat_khau = $matKhauMoiHash;
        return $taiKhoan->save();
    }
}
