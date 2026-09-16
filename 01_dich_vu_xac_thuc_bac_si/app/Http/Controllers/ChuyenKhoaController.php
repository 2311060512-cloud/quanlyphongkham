<?php

namespace App\Http\Controllers;

use App\Services\ChuyenKhoaService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChuyenKhoaController extends Controller
{
    use TraVeDuLieuTrait;

    protected ChuyenKhoaService $chuyenKhoaService;

    public function __construct(ChuyenKhoaService $chuyenKhoaService)
    {
        $this->chuyenKhoaService = $chuyenKhoaService;
    }

    public function danhSach(): JsonResponse
    {
        $ketQua = $this->chuyenKhoaService->danhSach();
        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    public function themMoi(Request $request): JsonResponse
    {
        $duLieu = $request->validate([
            'ma_khoa' => 'nullable|string|max:50',
            'ma_chuyen_khoa' => 'nullable|string|max:50',
            'ten_khoa' => 'nullable|string|max:150',
            'ten_chuyen_khoa' => 'nullable|string|max:150',
            'mo_ta' => 'nullable|string',
            'hinh_anh' => 'nullable|string|max:255',
        ]);

        $ketQua = $this->chuyenKhoaService->themMoi($duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 422);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep'], 201);
    }
}
