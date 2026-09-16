<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use App\Services\DichVuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DichVuController extends Controller
{
    protected DichVuService $dichVuService;

    public function __construct(DichVuService $dichVuService)
    {
        $this->dichVuService = $dichVuService;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $loaiDichVu = $request->query('loai_dich_vu');
        $tuKhoa = $request->query('tu_khoa');

        $danhSach = $this->dichVuService->danhSachDichVu($loaiDichVu, $tuKhoa);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    public function themMoi(Request $request): JsonResponse
    {
        $request->validate([
            'ma_dich_vu' => 'required|string|unique:dich_vu,ma_dich_vu',
            'ten_dich_vu' => 'required|string|max:255',
            'loai_dich_vu' => 'required|string|in:XET_NGHIEM,CHUP_XQUANG,SIEU_AM,NOI_SOI,KHAC',
            'don_gia' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string',
        ]);

        $dichVu = DichVu::create([
            'ma_dich_vu' => $request->ma_dich_vu,
            'ten_dich_vu' => $request->ten_dich_vu,
            'loai_dich_vu' => $request->loai_dich_vu,
            'don_gia' => $request->don_gia,
            'mo_ta' => $request->mo_ta,
            'trang_thai' => 1,
        ]);

        return response()->json([
            'thanh_cong' => true,
            'thong_diep' => 'Them dich vu moi thanh cong.',
            'du_lieu' => $dichVu
        ], 201);
    }

    public function chiDinh(Request $request): JsonResponse
    {
        $request->validate([
            'lich_hen_id' => 'required|integer',
            'benh_nhan_id' => 'required|integer',
            'bac_si_id' => 'required|integer',
            'danh_sach_dich_vu_id' => 'required|array|min:1',
            'danh_sach_dich_vu_id.*' => 'integer|exists:dich_vu,id',
        ]);

        $ketQua = $this->dichVuService->chiDinhDichVu($request->all());

        if (!$ketQua['thanh_cong']) {
            return response()->json($ketQua, 422);
        }

        return response()->json($ketQua, 201);
    }

    public function capNhatKetQua(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'ket_qua' => 'required|string',
            'ghi_chu' => 'nullable|string',
            'file_ket_qua' => 'nullable|string',
        ]);

        $ketQua = $this->dichVuService->capNhatKetQua($id, $request->all());

        if (!$ketQua['thanh_cong']) {
            return response()->json($ketQua, 404);
        }

        return response()->json($ketQua, 200);
    }

    public function danhSachTheoLichHen(int $lichHenId): JsonResponse
    {
        $danhSach = $this->dichVuService->danhSachTheoLichHen($lichHenId);

        return response()->json([
            'thanh_cong' => true,
            'lich_hen_id' => $lichHenId,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }
}
