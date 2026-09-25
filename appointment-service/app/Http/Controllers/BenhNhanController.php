<?php

namespace App\Http\Controllers;

use App\Services\BenhNhanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BenhNhanController extends Controller
{
    protected BenhNhanService $benhNhanService;

    public function __construct(BenhNhanService $benhNhanService)
    {
        $this->benhNhanService = $benhNhanService;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $tuKhoa = $request->query('tu_khoa');
        $danhSach = $this->benhNhanService->danhSach($tuKhoa);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    public function chiTiet(int $id): JsonResponse
    {
        $benhNhan = $this->benhNhanService->chiTiet($id);
        if (!$benhNhan) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy hồ sơ bệnh nhân.'
            ], 404);
        }

        return response()->json([
            'thanh_cong' => true,
            'du_lieu' => $benhNhan
        ]);
    }

    public function hoSoCuaToi(Request $request): JsonResponse
    {
        $taiKhoanId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->query('tai_khoan_id'));
        if (!$taiKhoanId) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_DANG_NHAP',
                'thong_diep' => 'Vui lòng đăng nhập để xem hồ sơ cá nhân.'
            ], 401);
        }

        $benhNhan = $this->benhNhanService->chiTietTheoTaiKhoan($taiKhoanId);
        if (!$benhNhan) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_CO_HO_SO',
                'thong_diep' => 'Tài khoản chưa có hồ sơ bệnh án điện tử.',
                'du_lieu' => null
            ], 404);
        }

        return response()->json([
            'thanh_cong' => true,
            'du_lieu' => $benhNhan
        ]);
    }

    public function taoMoi(Request $request): JsonResponse
    {
        $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:15',
            'so_cccd' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|in:NAM,NU,KHAC',
            'dia_chi' => 'nullable|string|max:255',
            'nhom_mau' => 'nullable|string|in:A,B,AB,O',
            'tien_su_di_ung' => 'nullable|string',
            'tien_su_benh' => 'nullable|string',
            'nguoi_lien_he_khan_cap' => 'nullable|string|max:100',
            'sdt_khan_cap' => 'nullable|string|max:15',
            'tai_khoan_id' => 'nullable|integer',
        ]);

        $taiKhoanId = $request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->input('tai_khoan_id');
        $payload = $request->all();
        if ($taiKhoanId) {
            $payload['tai_khoan_id'] = (int)$taiKhoanId;
        }

        $benhNhan = $this->benhNhanService->taoMoi($payload);

        return response()->json([
            'thanh_cong' => true,
            'thong_diep' => 'Tạo hồ sơ bệnh án điện tử thành công.',
            'du_lieu' => $benhNhan
        ], 201);
    }

    /**
     * LẤY DANH SÁCH HỒ SƠ GIA ĐÌNH CỦA TÀI KHOẢN ĐANG ĐĂNG NHẬP
     */
    public function hoSoGiaDinh(Request $request): JsonResponse
    {
        $taiKhoanId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->query('tai_khoan_id'));
        if (!$taiKhoanId) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_DANG_NHAP',
                'thong_diep' => 'Vui lòng đăng nhập để xem danh sách hồ sơ gia đình.'
            ], 401);
        }

        $danhSach = $this->benhNhanService->hoSoGiaDinh($taiKhoanId);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    /**
     * TẠO MỚI HỒ SƠ NGƯỜI THÂN (CON CÁI, BỐ MẸ...)
     */
    public function taoHoSoNguoiThan(Request $request): JsonResponse
    {
        $taiKhoanId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->input('tai_khoan_id'));
        if (!$taiKhoanId) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_DANG_NHAP',
                'thong_diep' => 'Vui lòng đăng nhập để tạo hồ sơ người thân.'
            ], 401);
        }

        $request->validate([
            'ho_ten' => 'required|string|max:100',
            'quan_he_chu_tai_khoan' => 'required|string|in:BAN_THAN,CON,CHA_ME,VO_CHONG,NGUOI_THAN',
            'so_dien_thoai' => 'nullable|string|max:15',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|in:NAM,NU,KHAC',
            'nhom_mau' => 'nullable|string|in:A,B,AB,O',
        ]);

        $payload = $request->all();
        if (empty($payload['so_dien_thoai'])) {
            $payload['so_dien_thoai'] = '09' . str_pad((string)mt_rand(10000000, 99999999), 8, '0');
        }

        $hoSo = $this->benhNhanService->taoHoSoNguoiThan($taiKhoanId, $payload);

        return response()->json([
            'thanh_cong' => true,
            'thong_diep' => 'Tạo hồ sơ người thân thành công.',
            'du_lieu' => $hoSo
        ], 201);
    }

}
