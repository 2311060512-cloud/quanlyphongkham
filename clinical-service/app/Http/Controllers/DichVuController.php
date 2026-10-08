<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use App\Services\DichVuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DichVuController extends Controller
{
    protected DichVuService $dichVuService;

    public function __construct(DichVuService $dichVuService)
    {
        $this->dichVuService = $dichVuService;
    }

    /**
     * 1. GET /api/dich-vu
     */
    public function danhSach(Request $request): JsonResponse
    {
        $loaiDichVu = $request->query('loai_dich_vu');
        $tuKhoa = $request->query('tu_khoa');
        $chiHoatDong = $request->boolean('chi_hoat_dong', false);

        $danhSach = $this->dichVuService->danhSachDichVu($loaiDichVu, $tuKhoa, $chiHoatDong);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    /**
     * 2. POST /api/dich-vu
     */
    public function themMoi(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ma_dich_vu' => 'required|string|unique:dich_vu,ma_dich_vu',
            'ten_dich_vu' => 'required|string|max:255',
            'loai_dich_vu' => 'required|string|in:XET_NGHIEM,CHUP_XQUANG,SIEU_AM,NOI_SOI,THU_THUAT,KHAC',
            'don_gia' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $dichVu = $this->dichVuService->themMoiDichVu($request->all());

        return response()->json([
            'thanh_cong' => true,
            'thong_diep' => 'Thêm mới dịch vụ y tế thành công.',
            'du_lieu' => $dichVu
        ], 201);
    }

    /**
     * 3. PUT /api/dich-vu/{id}
     */
    public function capNhat(int $id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten_dich_vu' => 'nullable|string|max:255',
            'loai_dich_vu' => 'nullable|string|in:XET_NGHIEM,CHUP_XQUANG,SIEU_AM,NOI_SOI,THU_THUAT,KHAC',
            'don_gia' => 'nullable|numeric|min:0',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $res = $this->dichVuService->capNhatDichVu($id, $request->all());
        return response()->json($res, $res['thanh_cong'] ? 200 : 404);
    }

    /**
     * 4. PATCH /api/dich-vu/{id}/toggle-status
     */
    public function toggleStatus(int $id): JsonResponse
    {
        $res = $this->dichVuService->batTatTrangThai($id);
        return response()->json($res, $res['thanh_cong'] ? 200 : 404);
    }

    /**
     * 5. POST /api/kham-benh/chi-dinh (hoặc /api/dich-vu/chi-dinh)
     */
    public function chiDinh(Request $request): JsonResponse
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'lich_hen_id' => 'required|integer',
            'benh_nhan_id' => 'nullable|integer',
            'bac_si_id' => 'nullable|integer',
            'danh_sach_dich_vu_id' => 'required|array|min:1',
            'danh_sach_dich_vu_id.*' => 'integer|exists:dich_vu,id',
            'chan_doan_so_bo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $data['benh_nhan_id'] = $data['benh_nhan_id'] ?? 1;
        $data['bac_si_id'] = $data['bac_si_id'] ?? ((int)$request->header('X-User-Id') ?: 1);

        $ketQua = $this->dichVuService->chiDinhDichVu($data);

        return response()->json($ketQua, $ketQua['thanh_cong'] ? 201 : 422);
    }

    /**
     * 6. GET /api/kham-benh/{lichHenId}/dich-vu (hoặc /api/dich-vu/lich-hen/{lichHenId})
     */
    public function danhSachTheoLichHen(int $lichHenId): JsonResponse
    {
        $data = $this->dichVuService->danhSachTheoLichHen($lichHenId);

        return response()->json([
            'thanh_cong' => true,
            'lich_hen_id' => $lichHenId,
            'tong_so' => $data['danh_sach']->count(),
            'tong_tien_cls' => $data['tong_tien_cls'],
            'du_lieu' => $data['danh_sach']
        ]);
    }

    /**
     * 7. DELETE /api/kham-benh/chi-dinh/{id}
     */
    public function huyChiDinh(int $id): JsonResponse
    {
        $res = $this->dichVuService->huyChiDinh($id);
        return response()->json($res, $res['thanh_cong'] ? 200 : 422);
    }

    /**
     * 8. GET /api/can-lam-sang/danh-sach-cho
     */
    public function danhSachCho(Request $request): JsonResponse
    {
        $loaiDichVu = $request->query('loai_dich_vu');
        $tuKhoa = $request->query('tu_khoa');

        $danhSach = $this->dichVuService->danhSachChoCanLamSang($loaiDichVu, $tuKhoa);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    /**
     * 9. PUT /api/can-lam-sang/{id}/ket-qua (hoặc /api/dich-vu/ket-qua/{id})
     */
    public function capNhatKetQua(int $id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ket_qua' => 'required|string',
            'ghi_chu' => 'nullable|string',
            'file_ket_qua' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $ketQua = $this->dichVuService->capNhatKetQua($id, $request->all());

        return response()->json($ketQua, $ketQua['thanh_cong'] ? 200 : 404);
    }

    /**
     * 10. POST /api/kham-benh/{lichHenId}/hoan-thanh
     */
    public function hoanThanh(int $lichHenId, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'chan_doan' => 'required|string',
            'trieu_chung' => 'nullable|string',
            'don_thuoc' => 'nullable|array',
            'loi_dan_bac_si' => 'nullable|string',
            'ngay_tai_kham' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $ketQua = $this->dichVuService->hoanThanhKhamBenh($lichHenId, $request->all());

        return response()->json($ketQua, 200);
    }

    /**
     * 11. GET /api/kham-benh/{lichHenId}/ho-so
     */
    public function hoSo(int $lichHenId): JsonResponse
    {
        $res = $this->dichVuService->layHoSoKhamBenh($lichHenId);
        return response()->json($res, $res['thanh_cong'] ? 200 : 404);
    }
}
