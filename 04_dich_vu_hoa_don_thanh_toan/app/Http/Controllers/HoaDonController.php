<?php

namespace App\Http\Controllers;

use App\Services\HoaDonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    protected HoaDonService $hoaDonService;

    public function __construct(HoaDonService $hoaDonService)
    {
        $this->hoaDonService = $hoaDonService;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $boLoc = [
            'benh_nhan_id' => $request->query('benh_nhan_id'),
            'trang_thai' => $request->query('trang_thai'),
        ];

        $danhSach = $this->hoaDonService->danhSach($boLoc);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    public function chiTiet(int $id): JsonResponse
    {
        $hoaDon = $this->hoaDonService->chiTiet($id);
        if (!$hoaDon) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => 'Khong tim thay hoa don.'
            ], 404);
        }

        return response()->json([
            'thanh_cong' => true,
            'du_lieu' => $hoaDon
        ]);
    }

    public function taoTuDong(Request $request): JsonResponse
    {
        $request->validate([
            'lich_hen_id' => 'required|integer',
            'giam_gia' => 'nullable|numeric|min:0',
        ]);

        $giamGia = (float)($request->input('giam_gia', 0));
        $ketQua = $this->hoaDonService->taoTuDong((int)$request->lich_hen_id, $giamGia);

        return response()->json($ketQua, 201);
    }

    public function thanhToan(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'phuong_thuc_thanh_toan' => 'required|string|in:TIEN_MAT,CHUYEN_KHOAN,VNPAY,MOMO',
            'ghi_chu' => 'nullable|string',
        ]);

        $ketQua = $this->hoaDonService->thanhToan($id, $request->phuong_thuc_thanh_toan, $request->ghi_chu);

        if (!$ketQua['thanh_cong']) {
            return response()->json($ketQua, 400);
        }

        return response()->json($ketQua, 200);
    }

    public function thongKe(): JsonResponse
    {
        $thongKe = $this->hoaDonService->thongKe();

        return response()->json([
            'thanh_cong' => true,
            'du_lieu' => $thongKe
        ]);
    }
}
