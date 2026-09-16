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
                'thong_diep' => 'Khong tim thay ho so benh nhan.'
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
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|in:NAM,NU,KHAC',
            'dia_chi' => 'nullable|string',
            'tien_su_benh' => 'nullable|string',
            'tai_khoan_id' => 'nullable|integer',
        ]);

        $benhNhan = $this->benhNhanService->taoMoi($request->all());

        return response()->json([
            'thanh_cong' => true,
            'thong_diep' => 'Tao ho so benh nhan thanh cong.',
            'du_lieu' => $benhNhan
        ], 201);
    }
}
