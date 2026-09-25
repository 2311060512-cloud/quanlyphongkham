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

    public function capNhat(int $id, array $duLieu): array
    {
        $ck = $this->chuyenKhoaRepo->timTheoId($id);
        if (!$ck) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'CHUYEN_KHOA_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy chuyên khoa cần cập nhật.'
            ];
        }

        $duLieuCapNhat = [];
        if (!empty($duLieu['ten_khoa']) || !empty($duLieu['ten_chuyen_khoa'])) {
            $duLieuCapNhat['ten_khoa'] = $duLieu['ten_khoa'] ?? $duLieu['ten_chuyen_khoa'];
        }
        if (!empty($duLieu['ma_khoa']) || !empty($duLieu['ma_chuyen_khoa'])) {
            $duLieuCapNhat['ma_khoa'] = strtoupper($duLieu['ma_khoa'] ?? $duLieu['ma_chuyen_khoa']);
        }
        if (isset($duLieu['mo_ta'])) {
            $duLieuCapNhat['mo_ta'] = $duLieu['mo_ta'];
        }
        if (isset($duLieu['trang_thai'])) {
            $duLieuCapNhat['trang_thai'] = $duLieu['trang_thai'];
        }

        $chuyenKhoaMoi = $this->chuyenKhoaRepo->capNhat($id, $duLieuCapNhat);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Cập nhật thông tin chuyên khoa thành công.',
            'du_lieu' => $chuyenKhoaMoi
        ];
    }

    public function xoa(int $id): array
    {
        $ck = $this->chuyenKhoaRepo->timTheoId($id);
        if (!$ck) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'CHUYEN_KHOA_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy chuyên khoa cần xóa.'
            ];
        }

        // Kiểm tra xem có bác sĩ nào đang thuộc khoa này không
        $soBacSi = \App\Models\BacSi::where('chuyen_khoa_id', $id)->count();
        if ($soBacSi > 0) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'DANG_CO_BAC_SI',
                'thong_diep' => "Không thể xóa chuyên khoa [{$ck->ten_khoa}] vì đang có {$soBacSi} bác sĩ trực thuộc. Vui lòng chuyển hoặc xóa bác sĩ trước!"
            ];
        }

        $this->chuyenKhoaRepo->xoa($id);

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đã xóa chuyên khoa [{$ck->ten_khoa}] thành công."
        ];
    }
}
