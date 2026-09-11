<?php

namespace App\Modules\BacSi\Services;

use App\Modules\BacSi\Repositories\ChuyenKhoaRepositoryInterface;
use App\Modules\BacSi\Models\ChuyenKhoa;
use Illuminate\Database\Eloquent\Collection;

class ChuyenKhoaService
{
    protected ChuyenKhoaRepositoryInterface $chuyenKhoaRepo;

    public function __construct(ChuyenKhoaRepositoryInterface $chuyenKhoaRepo)
    {
        $this->chuyenKhoaRepo = $chuyenKhoaRepo;
    }

    /**
     * Lấy toàn bộ danh sách chuyên khoa
     */
    public function danhSachChuyenKhoa(bool $chiHoatDong = false): Collection
    {
        if ($chiHoatDong) {
            return $this->chuyenKhoaRepo->layDanhSachHoatDong();
        }
        return $this->chuyenKhoaRepo->layTatCa();
    }

    /**
     * Lấy chi tiết chuyên khoa kèm danh sách bác sĩ thuộc khoa
     */
    public function chiTietChuyenKhoa(int $id): ?ChuyenKhoa
    {
        return $this->chuyenKhoaRepo->timTheoId($id)?->load('bacSi');
    }

    /**
     * Thêm mới chuyên khoa
     */
    public function taoChuyenKhoa(array $duLieu): ChuyenKhoa
    {
        return $this->chuyenKhoaRepo->taoMoi([
            'ma_khoa' => strtoupper(trim($duLieu['ma_khoa'])),
            'ten_khoa' => trim($duLieu['ten_khoa']),
            'mo_ta' => $duLieu['mo_ta'] ?? null,
            'hinh_anh' => $duLieu['hinh_anh'] ?? null,
            'trang_thai' => $duLieu['trang_thai'] ?? 'HOAT_DONG',
        ]);
    }

    /**
     * Cập nhật thông tin chuyên khoa
     */
    public function capNhatChuyenKhoa(int $id, array $duLieu): ChuyenKhoa
    {
        $chuyenKhoa = $this->chuyenKhoaRepo->timTheoId($id);
        if (!$chuyenKhoa) {
            throw new \Exception('Không tìm thấy chuyên khoa cần cập nhật.');
        }

        $this->chuyenKhoaRepo->capNhat($id, $duLieu);
        return $chuyenKhoa->fresh();
    }

    /**
     * Xóa chuyên khoa (nếu chưa có bác sĩ trực thuộc)
     */
    public function xoaChuyenKhoa(int $id): bool
    {
        $chuyenKhoa = $this->chuyenKhoaRepo->timTheoId($id);
        if (!$chuyenKhoa) {
            throw new \Exception('Không tìm thấy chuyên khoa cần xóa.');
        }

        if ($chuyenKhoa->bacSi()->count() > 0) {
            throw new \Exception('Không thể xóa chuyên khoa này vì đang có bác sĩ trực thuộc. Vui lòng chuyển công tác bác sĩ trước.');
        }

        return $this->chuyenKhoaRepo->xoa($id);
    }
}
