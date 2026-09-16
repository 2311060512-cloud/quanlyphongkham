<?php

namespace App\Http\Controllers;

use App\Services\LichHenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LichHenController extends Controller
{
    protected LichHenService $lichHenService;

    public function __construct(LichHenService $lichHenService)
    {
        $this->lichHenService = $lichHenService;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $boLoc = [
            'bac_si_id' => $request->query('bac_si_id'),
            'benh_nhan_id' => $request->query('benh_nhan_id'),
            'ngay_kham' => $request->query('ngay_kham'),
            'trang_thai' => $request->query('trang_thai'),
        ];

        $danhSach = $this->lichHenService->danhSach($boLoc);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    public function chiTiet(int $id): JsonResponse
    {
        $lichHen = $this->lichHenService->chiTiet($id);
        if (!$lichHen) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => 'Khong tim thay lich hen.'
            ], 404);
        }

        return response()->json([
            'thanh_cong' => true,
            'du_lieu' => $lichHen
        ]);
    }

    public function datLich(Request $request): JsonResponse
    {
        $taiKhoanId = $request->header('X-Nguoi-Dung-Id') ?: $request->input('tai_khoan_id');
        $benhNhanId = $request->input('benh_nhan_id');
        $hoTen = $request->input('ho_ten_benh_nhan') ?: $request->input('ho_ten');
        $sdt = $request->input('so_dien_thoai');

        if (!$benhNhanId) {
            $benhNhan = null;
            if ($taiKhoanId) {
                $benhNhan = \App\Models\BenhNhan::where('tai_khoan_id', $taiKhoanId)->first();
            }
            if (!$benhNhan && $sdt) {
                $benhNhan = \App\Models\BenhNhan::where('so_dien_thoai', $sdt)->first();
            }
            if (!$benhNhan && $hoTen) {
                $benhNhan = \App\Models\BenhNhan::create([
                    'tai_khoan_id' => $taiKhoanId,
                    'ma_benh_nhan' => 'BN' . strtoupper(uniqid()),
                    'ho_ten' => $hoTen,
                    'so_dien_thoai' => $sdt ?: '0901234567',
                ]);
            }
            if ($benhNhan) {
                $benhNhanId = $benhNhan->id;
                $request->merge(['benh_nhan_id' => $benhNhanId]);
            }
        }

        $request->validate([
            'benh_nhan_id' => 'required|integer|exists:benh_nhan,id',
            'bac_si_id' => 'required|integer',
            'ngay_kham' => 'required|date',
            'gio_bat_dau' => 'required|string',
            'gio_ket_thuc' => 'nullable|string',
            'ly_do_kham' => 'nullable|string',
        ]);

        $ketQua = $this->lichHenService->datLich($request->all());

        if (!$ketQua['thanh_cong']) {
            $status = ($ketQua['ma_loi'] ?? '') === 'TRUNG_LICH_KHAM' ? 409 : 422;
            return response()->json($ketQua, $status);
        }

        return response()->json($ketQua, 201);
    }

    public function hoanThanh(int $id, Request $request): JsonResponse
    {
        $ketQua = $this->lichHenService->hoanThanh($id, $request->input('ghi_chu_bac_si'));

        if (!$ketQua['thanh_cong']) {
            return response()->json($ketQua, 404);
        }

        return response()->json($ketQua, 200);
    }

    public function huy(int $id, Request $request): JsonResponse
    {
        $ketQua = $this->lichHenService->huy($id, $request->input('ly_do'));

        if (!$ketQua['thanh_cong']) {
            return response()->json($ketQua, 404);
        }

        return response()->json($ketQua, 200);
    }
}
