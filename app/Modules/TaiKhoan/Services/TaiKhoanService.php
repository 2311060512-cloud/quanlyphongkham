<?php

namespace App\Modules\TaiKhoan\Services;

use App\Modules\TaiKhoan\Repositories\TaiKhoanRepositoryInterface;
use App\Modules\TaiKhoan\Models\TaiKhoan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TaiKhoanService
{
    protected TaiKhoanRepositoryInterface $taiKhoanRepo;

    public function __construct(TaiKhoanRepositoryInterface $taiKhoanRepo)
    {
        $this->taiKhoanRepo = $taiKhoanRepo;
    }

    /**
     * Xem danh sách tài khoản kèm phân trang và lọc vai trò
     */
    public function danhSachTaiKhoan(int $soMoiTrang = 10, ?int $vaiTroId = null, ?string $tuKhoa = null): LengthAwarePaginator
    {
        return $this->taiKhoanRepo->layDanhSachCoPhanTrang($soMoiTrang, $vaiTroId, $tuKhoa);
    }

    /**
     * Khóa hoặc mở khóa tài khoản (HOAT_DONG <-> KHOA)
     */
    public function khoaMoKhoaTaiKhoan(int $id, ?int $currentUserId = null): TaiKhoan
    {
        if ($currentUserId && $id === $currentUserId) {
            throw new \Exception('Không thể tự khóa tài khoản của chính mình.');
        }

        $taiKhoan = $this->taiKhoanRepo->chuyenTrangThai($id);
        if (!$taiKhoan) {
            throw new \Exception('Không tìm thấy tài khoản để thao tác.');
        }

        // Nếu tài khoản bị khóa, thu hồi toàn bộ token đăng nhập
        if ($taiKhoan->trang_thai === 'KHOA' || $taiKhoan->trang_thai === 'TAM_KHOA') {
            $taiKhoan->tokens()->delete();
        }

        return $taiKhoan;
    }

    /**
     * Đổi mật khẩu cho người dùng hiện tại (bác sĩ, bệnh nhân, admin)
     */
    public function doiMatKhau(TaiKhoan $taiKhoan, string $matKhauCu, string $matKhauMoi): bool
    {
        if (!Hash::check($matKhauCu, $taiKhoan->mat_khau)) {
            throw ValidationException::withMessages([
                'mat_khau_cu' => ['Mật khẩu hiện tại không chính xác.'],
            ]);
        }

        $matKhauHash = Hash::make($matKhauMoi);
        $thanhCong = $this->taiKhoanRepo->doiMatKhau($taiKhoan->id, $matKhauHash);

        if (!$thanhCong) {
            throw new \Exception('Không thể cập nhật mật khẩu mới.');
        }

        return true;
    }

    /**
     * Quên mật khẩu: Khôi phục lại mật khẩu thông qua email
     */
    public function quenMatKhau(string $email, ?string $matKhauMoi = null): array
    {
        $taiKhoan = $this->taiKhoanRepo->timTheoEmail($email);
        if (!$taiKhoan) {
            throw new \Exception('Không tìm thấy tài khoản liên kết với email này.');
        }

        if ($taiKhoan->trang_thai === 'KHOA' || $taiKhoan->trang_thai === 'TAM_KHOA') {
            throw new \Exception('Tài khoản này đang bị khóa. Vui lòng liên hệ Quản trị viên phòng khám.');
        }

        $matKhauThietLap = $matKhauMoi ?: 'MatKhau@123';
        $matKhauHash = Hash::make($matKhauThietLap);
        $this->taiKhoanRepo->doiMatKhau($taiKhoan->id, $matKhauHash);

        // Thu hồi toàn bộ token cũ
        $taiKhoan->tokens()->delete();

        return [
            'email' => $taiKhoan->email,
            'ho_ten' => $taiKhoan->ho_ten,
            'mat_khau_moi_mac_dinh' => $matKhauMoi ? null : $matKhauThietLap,
            'thong_bao' => $matKhauMoi ? 'Đặt lại mật khẩu mới thành công.' : 'Mật khẩu đã được khôi phục về mặc định: ' . $matKhauThietLap,
        ];
    }
}
