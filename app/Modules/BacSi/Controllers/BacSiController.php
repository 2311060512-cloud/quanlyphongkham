<?php

namespace App\Modules\BacSi\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BacSi\Services\BacSiService;
use App\Modules\BacSi\Requests\TaoBacSiRequest;
use App\Modules\BacSi\Requests\CapNhatBacSiRequest;
use App\Modules\BacSi\Requests\DoiTrangThaiBacSiRequest;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BacSiController extends Controller
{
    use TraVeDuLieuTrait;

    protected BacSiService $bacSiService;

    public function __construct(BacSiService $bacSiService)
    {
        $this->bacSiService = $bacSiService;
    }

    /**
     * API xem danh sách bác sĩ có phân trang, tìm kiếm theo tên, lọc theo chuyên khoa
     */
    public function danhSach(Request $request): JsonResponse
    {
        $chuyenKhoaId = $request->query('chuyen_khoa_id') ? (int) $request->query('chuyen_khoa_id') : null;
        $tuKhoa = $request->query('tu_khoa');
        $trangThai = $request->query('trang_thai');

        // Nếu có tham số phân trang
        if ($request->has('page') || $request->has('per_page')) {
            $soMoiTrang = (int) $request->query('per_page', 10);
            $danhSach = $this->bacSiService->danhSachBacSiPhanTrang($soMoiTrang, $chuyenKhoaId, $tuKhoa, $trangThai);
            return $this->thanhCongResponse($danhSach, 'Danh sách bác sĩ');
        }

        // Mặc định trả về danh sách đầy đủ (hỗ trợ lọc khoa, từ khóa)
        $danhSach = $this->bacSiService->danhSachBacSi($chuyenKhoaId, $tuKhoa);
        return $this->thanhCongResponse($danhSach, 'Danh sách bác sĩ');
    }

    /**
     * API lấy chi tiết bác sĩ kèm thông tin chuyên khoa và lịch sử công tác
     */
    public function chiTiet(int $id): JsonResponse
    {
        $bacSi = $this->bacSiService->chiTietBacSi($id);
        if (!$bacSi) {
            return $this->thatBaiResponse('Không tìm thấy bác sĩ trong hệ thống', 404);
        }
        return $this->thanhCongResponse($bacSi, 'Chi tiết thông tin bác sĩ');
    }

    /**
     * API Thêm bác sĩ mới (Dành cho ADMIN)
     * Tự động tạo luôn tài khoản đăng nhập với role BAC_SI và mật khẩu mặc định
     */
    public function taoMoi(TaoBacSiRequest $request): JsonResponse
    {
        try {
            $bacSi = $this->bacSiService->taoBacSiMoi($request->validated());
            return $this->thanhCongResponse($bacSi, 'Thêm bác sĩ và tạo tài khoản đăng nhập thành công', 201);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    /**
     * API Cập nhật thông tin bác sĩ (chỉnh sửa giá khám, ảnh đại diện, đổi phòng làm việc, ca làm việc, học vị, kinh nghiệm)
     * Dành cho: ADMIN (cập nhật bất kỳ bác sĩ nào) hoặc BAC_SI (chỉ được cập nhật hồ sơ của chính mình)
     */
    public function capNhat(CapNhatBacSiRequest $request, int $id): JsonResponse
    {
        try {
            $user = $request->user();
            $vaiTro = $user?->vaiTro?->ma_vai_tro;

            // Nếu người dùng là BAC_SI, kiểm tra xem ID cập nhật có phải của chính bác sĩ này không
            if ($vaiTro === 'BAC_SI') {
                $bacSiHienTai = \App\Modules\BacSi\Models\BacSi::where('tai_khoan_id', $user->id)->first();
                if (!$bacSiHienTai || $bacSiHienTai->id !== $id) {
                    return $this->thatBaiResponse('Bạn chỉ có quyền cập nhật thông tin hồ sơ của chính mình.', 403);
                }
            } elseif ($vaiTro !== 'ADMIN') {
                return $this->thatBaiResponse('Bạn không có quyền thực hiện chức năng này.', 403);
            }

            $bacSi = $this->bacSiService->capNhatBacSi($id, $request->validated());
            return $this->thanhCongResponse($bacSi, 'Cập nhật thông tin bác sĩ thành công');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    /**
     * API Đổi trạng thái bác sĩ (DANG_LAM_VIEC / NGHI_PHEP / NGHI_VIEC) (Dành cho ADMIN)
     */
    public function doiTrangThai(DoiTrangThaiBacSiRequest $request, int $id): JsonResponse
    {
        try {
            $trangThaiMoi = $request->input('trang_thai');
            $bacSi = $this->bacSiService->doiTrangThaiBacSi($id, $trangThaiMoi);
            return $this->thanhCongResponse($bacSi, "Đã chuyển trạng thái bác sĩ sang {$trangThaiMoi}");
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }
}
