<?php

namespace App\Http\Controllers;

use App\Models\BenhNhan;
use App\Models\LichHen;
use App\Notifications\ThongBaoLichHenNotification;
use App\Services\LichHenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LichHenController extends Controller
{
    protected LichHenService $lichHenService;

    public function __construct(LichHenService $lichHenService)
    {
        $this->lichHenService = $lichHenService;
    }

    /**
     * BỘ LỌC VÀ DANH SÁCH LỊCH HẸN
     */
    public function danhSach(Request $request): JsonResponse
    {
        $boLoc = [
            'bac_si_id' => $request->query('bac_si_id'),
            'benh_nhan_id' => $request->query('benh_nhan_id'),
            'tai_khoan_id' => $request->query('tai_khoan_id'),
            'ngay_kham' => $request->query('ngay_kham'),
            'trang_thai' => $request->query('trang_thai'),
            'moc_thoi_gian' => $request->query('moc_thoi_gian'),
            'tu_khoa' => $request->query('tu_khoa'),
        ];

        $danhSach = $this->lichHenService->danhSach($boLoc);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    /**
     * XEM CHI TIẾT LỊCH HẸN
     */
    public function chiTiet(int $id): JsonResponse
    {
        $lichHen = $this->lichHenService->chiTiet($id);
        if (!$lichHen) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy lịch hẹn.'
            ], 404);
        }

        return response()->json([
            'thanh_cong' => true,
            'du_lieu' => $lichHen
        ]);
    }

    /**
     * LỊCH SỬ KHÁM CỦA BỆNH NHÂN ĐANG ĐĂNG NHẬP
     */
    public function lichSuCuaToi(Request $request): JsonResponse
    {
        $taiKhoanId = $request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->query('tai_khoan_id');

        if (!$taiKhoanId) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_DANG_NHAP',
                'thong_diep' => 'Vui lòng đăng nhập để xem lịch sử khám của bạn.'
            ], 401);
        }

        $danhSach = $this->lichHenService->danhSach([
            'tai_khoan_id' => (int)$taiKhoanId,
        ]);

        return response()->json([
            'thanh_cong' => true,
            'tong_so' => $danhSach->count(),
            'du_lieu' => $danhSach
        ]);
    }

    /**
     * ĐẶT LỊCH KHÁM TRỰC TUYẾN
     */
    public function datLich(Request $request): JsonResponse
    {
        // 1. Auth Gate: Kiểm tra người dùng bắt buộc đã đăng nhập
        $taiKhoanId = $request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id');
        
        // Nếu không có header xác thực từ Gateway, kiểm tra xem có truyền tai_khoan_id hợp lệ không
        if (empty($taiKhoanId) && !$request->has('tai_khoan_id')) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_DANG_NHAP',
                'thong_bao' => 'Vui lòng đăng nhập tài khoản trước khi thực hiện đặt lịch khám.',
                'thong_diep' => 'Yêu cầu không được xác thực. Thiếu Header X-User-Id.',
            ], 401);
        }

        if (empty($taiKhoanId)) {
            $taiKhoanId = $request->input('tai_khoan_id');
        }

        // 2. Validate dữ liệu đầu vào
        $validator = validator($request->all(), [
            'bac_si_id' => 'required|integer',
            'ngay_kham' => 'required|date',
            'gio_bat_dau' => 'nullable|string',
            'gio_kham' => 'nullable|string',
            'gio_ket_thuc' => 'nullable|string',
            'benh_nhan_id' => 'nullable|integer',
            'ho_ten' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:15',
            'so_cccd' => 'nullable|string|max:20',
            'nhom_mau' => 'nullable|string|in:A,B,AB,O',
            'trieu_chung' => 'nullable|string',
            'ly_do_kham' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'DU_LIEU_KHONG_HOP_LE',
                'thong_diep' => 'Dữ liệu đặt lịch không hợp lệ.',
                'loi' => $validator->errors(),
            ], 422);
        }

        $payload = $request->all();
        if (empty($payload['gio_bat_dau']) && !empty($payload['gio_kham'])) {
            $payload['gio_bat_dau'] = $payload['gio_kham'];
        }

        // Xử lý tệp đính kèm y tế nếu có upload qua multipart form-data
        if ($request->hasFile('tep_dinh_kem')) {
            $targetDir = public_path('uploads/tep_y_te');
            if (!file_exists($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $files = is_array($request->file('tep_dinh_kem')) ? $request->file('tep_dinh_kem') : [$request->file('tep_dinh_kem')];
            $danhSachTep = [];
            foreach ($files as $file) {
                $tenTep = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $tenTep);
                $danhSachTep[] = '/uploads/tep_y_te/' . $tenTep;
            }
            $payload['tep_dinh_kem'] = $danhSachTep;
        }

        // 3. Thực hiện nghiệp vụ đặt lịch qua Service
        $ketQua = $this->lichHenService->datLich($payload, (int)$taiKhoanId);

        if (!$ketQua['thanh_cong']) {
            $status = match ($ketQua['ma_loi'] ?? '') {
                'TRUNG_LICH_KHAM' => 409,
                'NGAY_KHAM_KHONG_HOP_LE' => 422,
                default => 400,
            };
            return response()->json($ketQua, $status);
        }

        // 4. Kích hoạt thông báo ngầm
        try {
            /** @var LichHen $lichHen */
            $lichHen = $ketQua['du_lieu'];
            $benhNhan = $lichHen->benhNhan;
            if ($benhNhan) {
                $benhNhan->notify(new ThongBaoLichHenNotification($lichHen, 'DAT_LICH_THANH_CONG'));
            }
        } catch (\Exception $e) {
            Log::info("Khong the gui thong bao email ngam: " . $e->getMessage());
        }

        return response()->json($ketQua, 201);
    }

    /**
     * BÁC SĨ DUYỆT XÁC NHẬN CA KHÁM (CHO_XAC_NHAN -> DA_XAC_NHAN)
     */
    public function xacNhan(int $id, Request $request): JsonResponse
    {
        $userId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->input('bac_si_id'));
        $vaiTro = $request->header('X-User-Role') ?: $request->header('X-Vai-Tro') ?: $request->input('vai_tro');

        $ketQua = $this->lichHenService->xacNhan($id, $userId ?: null, $vaiTro);

        $status = $ketQua['status'] ?? ($ketQua['thanh_cong'] ? 200 : 400);
        return response()->json($ketQua, $status);
    }

    /**
     * BÁC SĨ GỌI KHÁM (DA_XAC_NHAN -> DANG_KHAM)
     */
    public function batDauKham(int $id, Request $request): JsonResponse
    {
        $userId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->input('bac_si_id'));
        $vaiTro = $request->header('X-User-Role') ?: $request->header('X-Vai-Tro') ?: $request->input('vai_tro');

        $ketQua = $this->lichHenService->batDauKham($id, $userId ?: null, $vaiTro);

        $status = $ketQua['status'] ?? ($ketQua['thanh_cong'] ? 200 : 400);
        return response()->json($ketQua, $status);
    }

    /**
     * HOÀN THÀNH CA KHÁM (DANG_KHAM -> HOAN_THANH)
     */
    public function hoanThanh(int $id, Request $request): JsonResponse
    {
        $userId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->input('bac_si_id'));
        $vaiTro = $request->header('X-User-Role') ?: $request->header('X-Vai-Tro') ?: $request->input('vai_tro');

        $ketQuaKham = [
            'chuan_doan' => $request->input('chuan_doan'),
            'loi_khuyen' => $request->input('loi_khuyen'),
            'ghi_chu_bac_si' => $request->input('ghi_chu_bac_si') ?: $request->input('chuan_doan'),
        ];

        $ketQua = $this->lichHenService->hoanThanh($id, $ketQuaKham, $userId ?: null, $vaiTro);

        $status = $ketQua['status'] ?? ($ketQua['thanh_cong'] ? 200 : 400);
        return response()->json($ketQua, $status);
    }

    /**
     * HỦY LỊCH HẸN (-> DA_HUY)
     */
    public function huy(int $id, Request $request): JsonResponse
    {
        $lyDo = $request->input('ly_do_huy') ?: $request->input('ly_do');
        $userId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id'));
        $vaiTro = $request->header('X-User-Role') ?: $request->header('X-Vai-Tro');

        $ketQua = $this->lichHenService->huy($id, $lyDo, $userId ?: null, $vaiTro);

        $status = $ketQua['status'] ?? ($ketQua['thanh_cong'] ? 200 : 400);
        return response()->json($ketQua, $status);
    }

    /**
     * DỜI LỊCH HẸN KHÁM (RESCHEDULE)
     */
    public function doiLich(int $id, Request $request): JsonResponse
    {
        $userId = (int)($request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id') ?: $request->input('tai_khoan_id'));
        $vaiTro = $request->header('X-User-Role') ?: $request->header('X-Vai-Tro') ?: $request->input('vai_tro');

        $validator = validator($request->all(), [
            'ngay_kham' => 'required|date',
            'gio_bat_dau' => 'nullable|string',
            'gio_kham' => 'nullable|string',
            'gio_ket_thuc' => 'nullable|string',
            'ly_do_doi_lich' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'DU_LIEU_KHONG_HOP_LE',
                'thong_diep' => 'Dữ liệu dời lịch khám không hợp lệ.',
                'loi' => $validator->errors(),
            ], 422);
        }

        $ketQua = $this->lichHenService->doiLich($id, $request->all(), $userId ?: null, $vaiTro);

        $status = $ketQua['status'] ?? ($ketQua['thanh_cong'] ? 200 : 400);
        return response()->json($ketQua, $status);
    }

    /**
     * TẢI TỆP ĐÍNH KÈM Y TẾ BỔ SUNG CHO LỊCH HẸN
     */
    public function taiTepDinhKem(int $id, Request $request): JsonResponse
    {
        $lichHen = LichHen::find($id);
        if (!$lichHen) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy lịch hẹn.',
            ], 404);
        }

        $danhSachTep = $lichHen->tep_dinh_kem ?: [];
        if (!is_array($danhSachTep)) {
            $danhSachTep = json_decode($danhSachTep, true) ?: [$danhSachTep];
        }

        if ($request->hasFile('tep_dinh_kem')) {
            $targetDir = public_path('uploads/tep_y_te');
            if (!file_exists($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $files = is_array($request->file('tep_dinh_kem')) ? $request->file('tep_dinh_kem') : [$request->file('tep_dinh_kem')];
            foreach ($files as $file) {
                $tenTep = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($targetDir, $tenTep);
                $danhSachTep[] = '/uploads/tep_y_te/' . $tenTep;
            }
        } elseif ($request->has('tep_dinh_kem')) {
            $raw = $request->input('tep_dinh_kem');
            if (is_array($raw)) {
                $danhSachTep = array_merge($danhSachTep, $raw);
            } else {
                $danhSachTep[] = $raw;
            }
        }

        $lichHen->update([
            'tep_dinh_kem' => array_values(array_unique($danhSachTep))
        ]);

        return response()->json([
            'thanh_cong' => true,
            'thong_diep' => 'Tải lên hồ sơ/tệp y tế thành công.',
            'du_lieu' => $lichHen->fresh(),
        ], 200);
    }

    /**
     * XEM TRƯỚC THÔNG BÁO EMAIL VÀ SMS
     */
    public function xemTruocThongBao(int $id): JsonResponse
    {
        $ketQua = $this->lichHenService->xemTruocThongBao($id);

        if (!$ketQua['thanh_cong']) {
            return response()->json($ketQua, 404);
        }

        return response()->json($ketQua, 200);
    }
}
