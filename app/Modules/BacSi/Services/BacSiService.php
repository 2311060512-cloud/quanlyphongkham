<?php

namespace App\Modules\BacSi\Services;

use App\Modules\BacSi\Repositories\BacSiRepositoryInterface;
use App\Modules\BacSi\Repositories\ChuyenKhoaRepositoryInterface;
use App\Modules\TaiKhoan\Repositories\TaiKhoanRepositoryInterface;
use App\Modules\TaiKhoan\Models\VaiTro;
use App\Modules\BacSi\Models\BacSi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BacSiService
{
    protected BacSiRepositoryInterface $bacSiRepo;
    protected ChuyenKhoaRepositoryInterface $chuyenKhoaRepo;
    protected TaiKhoanRepositoryInterface $taiKhoanRepo;

    public function __construct(
        BacSiRepositoryInterface $bacSiRepo,
        ChuyenKhoaRepositoryInterface $chuyenKhoaRepo,
        TaiKhoanRepositoryInterface $taiKhoanRepo
    ) {
        $this->bacSiRepo = $bacSiRepo;
        $this->chuyenKhoaRepo = $chuyenKhoaRepo;
        $this->taiKhoanRepo = $taiKhoanRepo;
    }

    /**
     * Lấy danh sách bác sĩ có phân trang, tìm kiếm theo tên, lọc theo chuyên khoa
     */
    public function danhSachBacSiPhanTrang(
        int $soMoiTrang = 10,
        ?int $chuyenKhoaId = null,
        ?string $tuKhoa = null,
        ?string $trangThai = null
    ): LengthAwarePaginator {
        return $this->bacSiRepo->layDanhSachPhanTrang($soMoiTrang, $chuyenKhoaId, $tuKhoa, $trangThai);
    }

    /**
     * Lấy danh sách toàn bộ bác sĩ đang làm việc (dành cho chọn khám nhanh)
     */
    public function danhSachBacSi(?int $chuyenKhoaId = null, ?string $tuKhoa = null): Collection
    {
        return $this->bacSiRepo->layDanhSachTheoKhoa($chuyenKhoaId, $tuKhoa);
    }

    /**
     * Lấy chi tiết thông tin bác sĩ kèm chuyên khoa và tài khoản
     */
    public function chiTietBacSi(int $id): ?BacSi
    {
        return $this->bacSiRepo->chiTietKemQuanHe($id);
    }

    /**
     * Thêm bác sĩ mới (Dành cho ADMIN)
     * Tự động tạo luôn tài khoản đăng nhập với role BAC_SI và mật khẩu mặc định
     */
    public function taoBacSiMoi(array $duLieu): BacSi
    {
        // 1. Kiểm tra chuyên khoa hợp lệ
        $chuyenKhoa = $this->chuyenKhoaRepo->timTheoId($duLieu['chuyen_khoa_id']);
        if (!$chuyenKhoa) {
            throw new \Exception('Chuyên khoa không tồn tại trong hệ thống.');
        }

        // 2. Tạo tài khoản đăng nhập cho Bác sĩ nếu chưa có
        $taiKhoanId = $duLieu['tai_khoan_id'] ?? null;
        $vaiTroBacSi = VaiTro::where('ma_vai_tro', 'BAC_SI')->first();

        if (!$taiKhoanId) {
            // Tạo tên đăng nhập tự động nếu không truyền: ví dụ bs_tuan, bs_12345
            $tenDangNhap = $duLieu['ten_dang_nhap'] ?? null;
            if (!$tenDangNhap) {
                $tenKhongDau = Str::slug($duLieu['ho_ten'], '');
                $tenDangNhap = 'bs_' . substr($tenKhongDau, 0, 15) . rand(100, 999);
            }

            $email = $duLieu['email'] ?? ($tenDangNhap . '@phongkham.vn');
            $matKhauMacDinh = $duLieu['mat_khau'] ?? '123456';

            $taiKhoan = $this->taiKhoanRepo->taoMoi([
                'ten_dang_nhap' => $tenDangNhap,
                'email' => $email,
                'mat_khau' => Hash::make($matKhauMacDinh),
                'ho_ten' => $duLieu['ho_ten'],
                'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
                'vai_tro_id' => $vaiTroBacSi ? $vaiTroBacSi->id : 2,
                'trang_thai' => 'HOAT_DONG',
            ]);
            $taiKhoanId = $taiKhoan->id;
        }

        // 3. Tự động sinh mã bác sĩ (BS + 4 số ngẫu nhiên)
        do {
            $maBacSi = 'BS' . rand(1000, 9999);
            $trungMa = BacSi::where('ma_bac_si', $maBacSi)->exists();
        } while ($trungMa);

        // 4. Lưu thông tin hồ sơ bác sĩ
        return $this->bacSiRepo->taoMoi([
            'tai_khoan_id' => $taiKhoanId,
            'chuyen_khoa_id' => $duLieu['chuyen_khoa_id'],
            'ma_bac_si' => $maBacSi,
            'ho_ten' => $duLieu['ho_ten'],
            'hinh_anh' => $duLieu['hinh_anh'] ?? null,
            'hoc_vi' => $duLieu['hoc_vi'] ?? 'Bác sĩ Chuyên khoa',
            'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
            'email' => $duLieu['email'] ?? null,
            'gia_kham' => $duLieu['gia_kham'] ?? 200000,
            'phong_kham' => $duLieu['phong_kham'] ?? 'P101',
            'ca_lam_viec' => $duLieu['ca_lam_viec'] ?? 'CA_NGAY',
            'kinh_nghiem' => $duLieu['kinh_nghiem'] ?? 'Kinh nghiệm khám và điều trị chuyên sâu',
            'trang_thai' => 'DANG_LAM_VIEC',
        ])->load(['chuyenKhoa', 'taiKhoan']);
    }

    /**
     * Cập nhật thông tin bác sĩ (chỉnh sửa giá khám, số phòng, học vị, ảnh đại diện, ca làm việc, kinh nghiệm làm việc)
     */
    public function capNhatBacSi(int $id, array $duLieu): BacSi
    {
        $bacSi = $this->bacSiRepo->timTheoId($id);
        if (!$bacSi) {
            throw new \Exception('Không tìm thấy bác sĩ cần cập nhật.');
        }

        // Chỉ cập nhật các trường được phép
        $duLieuCapNhat = array_filter([
            'ho_ten' => $duLieu['ho_ten'] ?? null,
            'chuyen_khoa_id' => $duLieu['chuyen_khoa_id'] ?? null,
            'hoc_vi' => $duLieu['hoc_vi'] ?? null,
            'so_dien_thoai' => $duLieu['so_dien_thoai'] ?? null,
            'email' => $duLieu['email'] ?? null,
            'gia_kham' => isset($duLieu['gia_kham']) ? (float)$duLieu['gia_kham'] : null,
            'phong_kham' => $duLieu['phong_kham'] ?? null,
            'hinh_anh' => array_key_exists('hinh_anh', $duLieu) ? $duLieu['hinh_anh'] : null,
            'ca_lam_viec' => $duLieu['ca_lam_viec'] ?? null,
            'kinh_nghiem' => $duLieu['kinh_nghiem'] ?? null,
        ], fn($val) => !is_null($val));

        $this->bacSiRepo->capNhat($id, $duLieuCapNhat);

        // Đồng bộ cập nhật họ tên và sđt vào bảng tài khoản nếu có
        if ($bacSi->tai_khoan_id && (isset($duLieu['ho_ten']) || isset($duLieu['so_dien_thoai']))) {
            $capNhatTaiKhoan = [];
            if (isset($duLieu['ho_ten'])) $capNhatTaiKhoan['ho_ten'] = $duLieu['ho_ten'];
            if (isset($duLieu['so_dien_thoai'])) $capNhatTaiKhoan['so_dien_thoai'] = $duLieu['so_dien_thoai'];
            $this->taiKhoanRepo->capNhat($bacSi->tai_khoan_id, $capNhatTaiKhoan);
        }

        return $bacSi->fresh(['chuyenKhoa', 'taiKhoan']);
    }

    /**
     * Đổi trạng thái bác sĩ (DANG_LAM_VIEC / NGHI_PHEP / NGHI_VIEC)
     */
    public function doiTrangThaiBacSi(int $id, string $trangThai): BacSi
    {
        $trangThaiHopLe = ['DANG_LAM_VIEC', 'NGHI_PHEP', 'NGHI_VIEC'];
        if (!in_array($trangThai, $trangThaiHopLe)) {
            throw new \Exception('Trạng thái không hợp lệ. Chỉ chấp nhận: ' . implode(', ', $trangThaiHopLe));
        }

        $bacSi = $this->bacSiRepo->doiTrangThai($id, $trangThai);
        if (!$bacSi) {
            throw new \Exception('Không tìm thấy bác sĩ để đổi trạng thái.');
        }

        // Nếu bác sĩ nghỉ việc, khóa tài khoản tương ứng
        if ($trangThai === 'NGHI_VIEC' && $bacSi->tai_khoan_id) {
            $this->taiKhoanRepo->capNhat($bacSi->tai_khoan_id, ['trang_thai' => 'KHOA']);
        } elseif ($trangThai === 'DANG_LAM_VIEC' && $bacSi->tai_khoan_id) {
            $this->taiKhoanRepo->capNhat($bacSi->tai_khoan_id, ['trang_thai' => 'HOAT_DONG']);
        }

        return $bacSi;
    }
}
