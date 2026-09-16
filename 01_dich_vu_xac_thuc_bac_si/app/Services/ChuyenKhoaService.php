<?php

namespace App\Services;

use App\Repositories\Contracts\ChuyenKhoaRepositoryInterface;

class ChuyenKhoaService
{
    protected ChuyenKhoaRepositoryInterface $chuyenKhoaRepo;

    public function __construct(ChuyenKhoaRepositoryInterface $chuyenKhoaRepo)
    {
        $this->chuyenKhoaRepo = $chuyenKhoaRepo;
    }

    public function danhSach(): array
    {
        $danhSach = $this->chuyenKhoaRepo->danhSach();
        return [
            'thanh_cong' => true,
            'thong_diep' => 'Lấy danh sách chuyên khoa thành công.',
            'du_lieu' => $danhSach
        ];
    }

    public function themMoi(array $duLieu): array
    {
        $maKhoa = strtoupper($duLieu['ma_khoa'] ?? ($duLieu['ma_chuyen_khoa'] ?? ''));
        $tenKhoa = $duLieu['ten_khoa'] ?? ($duLieu['ten_chuyen_khoa'] ?? '');

        if (!$maKhoa || !$tenKhoa) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'THIEU_THONG_TIN',
                'thong_diep' => 'Vui lòng cung cấp đầy đủ mã khoa và tên chuyên khoa.'
            ];
        }

        $chuyenKhoa = $this->chuyenKhoaRepo->taoMoi([
            'ma_khoa' => $maKhoa,
            'ten_khoa' => $tenKhoa,
            'mo_ta' => $duLieu['mo_ta'] ?? null,
            'hinh_anh' => $duLieu['hinh_anh'] ?? null,
            'trang_thai' => $duLieu['trang_thai'] ?? 'HOAT_DONG',
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Thêm chuyên khoa mới thành công.',
            'du_lieu' => $chuyenKhoa
        ];
    }
}
