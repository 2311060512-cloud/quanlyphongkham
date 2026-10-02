<?php

namespace App\Services;

use App\Models\BenhNhan;
use App\Models\LichHen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LichHenService
{
    /**
     * THUẬT TOÁN CHỐNG TRÙNG LỊCH BÁC SĨ (Giao thoa 30 phút)
     * Điều kiện trùng:
     * bac_si_id = :bacSiId
     * AND ngay_kham = :ngayKham
     * AND trang_thai != 'DA_HUY'
     * AND (gio_bat_dau < :gioKetThucMoi AND gio_ket_thuc > :gioBatDauMoi)
     */
    public function kiemTraTrungLich(int $bacSiId, string $ngayKham, string $gioBatDau, string $gioKetThuc, ?int $loaiTruLichHenId = null): bool
    {
        $query = LichHen::where('bac_si_id', $bacSiId)
            ->where('ngay_kham', $ngayKham)
            ->where('trang_thai', '!=', 'DA_HUY')
            ->where(function ($q) use ($gioBatDau, $gioKetThuc) {
                $q->where('gio_bat_dau', '<', $gioKetThuc)
                  ->where('gio_ket_thuc', '>', $gioBatDau);
            });

        if ($loaiTruLichHenId) {
            $query->where('id', '!=', $loaiTruLichHenId);
        }

        return $query->exists();
    }

    /**
     * ĐẶT LỊCH HẸN KHÁM MỚI
     */
    public function datLich(array $data, ?int $taiKhoanId = null): array
    {
        $bacSiId = (int)$data['bac_si_id'];
        $ngayKham = $data['ngay_kham'];
        $gioBatDau = $data['gio_bat_dau'] ?? ($data['gio_kham'] ?? '08:00:00');

        // 1. Kiểm tra chặn ngày khám trong quá khứ
        $today = Carbon::today()->toDateString();
        if ($ngayKham < $today) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'NGAY_KHAM_KHONG_HOP_LE',
                'thong_bao' => 'Không thể đặt lịch khám trong quá khứ. Vui lòng chọn ngày hiện tại hoặc tương lai.',
                'thong_diep' => 'Ngày khám không hợp lệ (thuộc về quá khứ).',
            ];
        }

        // Tự động tính giờ kết thúc (mặc định ca 30 phút)
        $gioKetThuc = $data['gio_ket_thuc'] ?? Carbon::parse($gioBatDau)->addMinutes(30)->format('H:i:s');

        // 2. Kiểm tra thuật toán chống trùng lịch
        if ($this->kiemTraTrungLich($bacSiId, $ngayKham, $gioBatDau, $gioKetThuc)) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TRUNG_LICH_KHAM',
                'thong_bao' => 'Bác sĩ đã có lịch khám trong khung giờ này. Vui lòng chọn khung giờ khác.',
                'thong_diep' => "Bác sĩ đã có lịch khám trong khung giờ từ {$gioBatDau} đến {$gioKetThuc} ngày {$ngayKham}. Vui lòng chọn khung giờ khác.",
            ];
        }

        return DB::transaction(function () use ($data, $bacSiId, $ngayKham, $gioBatDau, $gioKetThuc, $taiKhoanId) {
            // 3. Xử lý hồ sơ bệnh nhân
            $benhNhanId = $data['benh_nhan_id'] ?? null;
            $benhNhan = null;

            if ($benhNhanId) {
                $benhNhan = BenhNhan::find($benhNhanId);
            }

            if (!$benhNhan && $taiKhoanId) {
                $benhNhan = BenhNhan::where('tai_khoan_id', $taiKhoanId)->first();
            }

            $sdt = $data['so_dien_thoai'] ?? ($benhNhan?->so_dien_thoai ?? null);
            if (!$benhNhan && $sdt) {
                $benhNhan = BenhNhan::where('so_dien_thoai', $sdt)->first();
            }

            // Tạo mới hoặc cập nhật hồ sơ bệnh nhân mở rộng
            $hoTen = $data['ho_ten'] ?? ($data['ho_ten_benh_nhan'] ?? ($benhNhan?->ho_ten ?? 'Bệnh nhân mới'));

            if (!$benhNhan) {
                $countBn = BenhNhan::count();
                $maBn = 'BN' . str_pad($countBn + 1, 4, '0', STR_PAD_LEFT);
                while (BenhNhan::where('ma_benh_nhan', $maBn)->exists()) {
                    $countBn++;
                    $maBn = 'BN' . str_pad($countBn + 1, 4, '0', STR_PAD_LEFT);
                }

                $benhNhan = BenhNhan::create([
                    'tai_khoan_id' => $taiKhoanId ?? ($data['tai_khoan_id'] ?? null),
                    'ma_benh_nhan' => $maBn,
                    'ho_ten' => $hoTen,
                    'so_dien_thoai' => $sdt ?: ($data['so_dien_thoai'] ?? ''),
                    'so_cccd' => $data['so_cccd'] ?? null,
                    'ngay_sinh' => $data['ngay_sinh'] ?? null,
                    'gioi_tinh' => $data['gioi_tinh'] ?? 'NAM',
                    'dia_chi' => $data['dia_chi'] ?? null,
                    'nhom_mau' => $data['nhom_mau'] ?? null,
                    'tien_su_di_ung' => $data['tien_su_di_ung'] ?? null,
                    'tien_su_benh' => $data['tien_su_benh'] ?? null,
                    'nguoi_lien_he_khan_cap' => $data['nguoi_lien_he_khan_cap'] ?? null,
                    'sdt_khan_cap' => $data['sdt_khan_cap'] ?? null,
                ]);
            } else {
                // Cập nhật thông tin mở rộng nếu có gửi lên
                $capNhat = [];
                foreach (['so_cccd', 'nhom_mau', 'tien_su_di_ung', 'tien_su_benh', 'nguoi_lien_he_khan_cap', 'sdt_khan_cap', 'dia_chi', 'ngay_sinh', 'gioi_tinh'] as $truong) {
                    if (isset($data[$truong]) && !empty($data[$truong])) {
                        $capNhat[$truong] = $data[$truong];
                    }
                }
                if ($taiKhoanId && empty($benhNhan->tai_khoan_id)) {
                    $capNhat['tai_khoan_id'] = $taiKhoanId;
                }
                if (!empty($capNhat)) {
                    $benhNhan->update($capNhat);
                }
            }

            // 4. Sinh mã lịch hẹn tự động LKxxxx duy nhất
            $countLh = LichHen::count();
            $maLichHen = 'LK' . str_pad($countLh + 1, 4, '0', STR_PAD_LEFT);
            while (LichHen::where('ma_lich_hen', $maLichHen)->exists()) {
                $countLh++;
                $maLichHen = 'LK' . str_pad($countLh + 1, 4, '0', STR_PAD_LEFT);
            }

            $lyDoKham = $data['trieu_chung'] ?? ($data['ly_do_kham'] ?? 'Khám sức khỏe tổng quát');

            // Xử lý tệp đính kèm y tế (đơn thuốc cũ, kết quả xét nghiệm)
            $tepDinhKem = null;
            if (isset($data['tep_dinh_kem'])) {
                if (is_array($data['tep_dinh_kem'])) {
                    $tepDinhKem = $data['tep_dinh_kem'];
                } elseif (is_string($data['tep_dinh_kem'])) {
                    $decoded = json_decode($data['tep_dinh_kem'], true);
                    $tepDinhKem = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$data['tep_dinh_kem']];
                }
            }

            $lichHen = LichHen::create([
                'ma_lich_hen' => $maLichHen,
                'benh_nhan_id' => $benhNhan->id,
                'bac_si_id' => $bacSiId,
                'ngay_kham' => $ngayKham,
                'gio_bat_dau' => $gioBatDau,
                'gio_ket_thuc' => $gioKetThuc,
                'ly_do_kham' => $lyDoKham,
                'trang_thai' => 'CHO_XAC_NHAN',
                'tep_dinh_kem' => $tepDinhKem,
                'so_lan_doi_lich' => 0,
                'ly_do_doi_lich' => null,
                'chuan_doan' => null,
                'loi_khuyen' => null,
                'ghi_chu_bac_si' => null,
                'ly_do_huy' => null,
            ]);

            return [
                'thanh_cong' => true,
                'thong_diep' => 'Đặt lịch hẹn khám bệnh thành công.',
                'thong_bao' => 'Đặt lịch khám thành công! Vui lòng lưu lại Mã lịch hẹn để tra cứu.',
                'du_lieu' => $lichHen->load('benhNhan'),
            ];
        });
    }

    /**
     * DỜI LỊCH HẸN KHÁM (RESCHEDULE)
     * Cho phép bệnh nhân hoặc bác sĩ đổi ngày/giờ khám thông minh
     */
    public function doiLich(int $id, array $data, ?int $nguoiThucHienId = null, ?string $vaiTro = null): array
    {
        $lichHen = LichHen::with('benhNhan')->find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_TIM_THAY_LICH_HEN',
                'thong_diep' => 'Không tìm thấy lịch hẹn để dời lịch.',
                'status' => 404,
            ];
        }

        // 1. Chỉ cho phép dời khi trạng thái là CHO_XAC_NHAN hoặc DA_XAC_NHAN
        if (!in_array($lichHen->trang_thai, ['CHO_XAC_NHAN', 'DA_XAC_NHAN'])) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_THE_DOI_LICH',
                'thong_diep' => "Không thể dời lịch ca khám có trạng thái '{$lichHen->trang_thai}'. Chỉ áp dụng cho lịch Chờ xác nhận hoặc Đã xác nhận.",
                'status' => 422,
            ];
        }

        // 2. Kiểm tra phân quyền: ADMIN, Bác sĩ phụ trách, hoặc chính Bệnh nhân sở hữu lịch hẹn
        if ($vaiTro !== 'ADMIN' && $nguoiThucHienId !== null) {
            $laBacSiPhuTrach = ((int)$lichHen->bac_si_id === (int)$nguoiThucHienId);
            $laBenhNhan = ($lichHen->benhNhan && (int)$lichHen->benhNhan->tai_khoan_id === (int)$nguoiThucHienId);
            if (!$laBacSiPhuTrach && !$laBenhNhan) {
                return [
                    'thanh_cong' => false,
                    'ma_loi' => 'KHONG_CO_QUYEN',
                    'thong_diep' => 'Bạn không có quyền dời lịch cho ca khám này.',
                    'status' => 403,
                ];
            }
        }

        // 3. Kiểm tra ngày khám mới hợp lệ
        $ngayKhamMoi = $data['ngay_kham'] ?? $lichHen->ngay_kham;
        $today = Carbon::today()->toDateString();
        if ($ngayKhamMoi < $today) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'NGAY_KHAM_KHONG_HOP_LE',
                'thong_diep' => 'Ngày dời lịch không thể ở trong quá khứ. Vui lòng chọn ngày từ hôm nay trở đi.',
                'status' => 422,
            ];
        }

        // 4. Khung giờ mới
        $gioBatDauMoi = $data['gio_bat_dau'] ?? ($data['gio_kham'] ?? $lichHen->gio_bat_dau);
        $gioKetThucMoi = $data['gio_ket_thuc'] ?? Carbon::parse($gioBatDauMoi)->addMinutes(30)->format('H:i:s');

        // 5. Kiểm tra thuật toán chống trùng lịch bác sĩ (loại trừ chính lịch hẹn này)
        if ($this->kiemTraTrungLich($lichHen->bac_si_id, $ngayKhamMoi, $gioBatDauMoi, $gioKetThucMoi, $id)) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'TRUNG_LICH_KHAM',
                'thong_diep' => "Bác sĩ đã có lịch khám trong khung giờ từ {$gioBatDauMoi} đến {$gioKetThucMoi} ngày {$ngayKhamMoi}. Vui lòng chọn giờ khác.",
                'status' => 409,
            ];
        }

        $lyDoDoiLich = $data['ly_do_doi_lich'] ?? ($data['ly_do'] ?? 'Bệnh nhân đề nghị dời giờ khám');
        $trangThaiMoi = ($vaiTro === 'BAC_SI' || $vaiTro === 'ADMIN') ? $lichHen->trang_thai : 'CHO_XAC_NHAN';

        $lichHen->update([
            'ngay_kham' => $ngayKhamMoi,
            'gio_bat_dau' => $gioBatDauMoi,
            'gio_ket_thuc' => $gioKetThucMoi,
            'so_lan_doi_lich' => ((int)$lichHen->so_lan_doi_lich) + 1,
            'ly_do_doi_lich' => $lyDoDoiLich,
            'trang_thai' => $trangThaiMoi,
            'ghi_chu_bac_si' => $lichHen->ghi_chu_bac_si ? $lichHen->ghi_chu_bac_si . " | Dời lịch: {$lyDoDoiLich}" : "Dời lịch: {$lyDoDoiLich}",
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => "Dời lịch khám thành công sang {$gioBatDauMoi} ngày " . date('d/m/Y', strtotime($ngayKhamMoi)),
            'du_lieu' => $lichHen->fresh(['benhNhan']),
            'status' => 200,
        ];
    }

    /**
     * BÁC SĨ / ADMIN DUYỆT XÁC NHẬN LỊCH HẸN
     * Chỉ ADMIN hoặc Bác sĩ có ID tương ứng mới có quyền duyệt
     */
    public function xacNhan(int $id, ?int $nguoiThucHienId = null, ?string $vaiTro = null): array
    {
        $lichHen = LichHen::with('benhNhan')->find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_TIM_THAY_LICH_HEN',
                'thong_diep' => 'Không tìm thấy lịch hẹn.',
                'status' => 404,
            ];
        }

        // Kiểm tra phân quyền: ADMIN hoặc Bác sĩ phụ trách
        if ($vaiTro !== 'ADMIN' && $nguoiThucHienId !== null) {
            if ((int)$lichHen->bac_si_id !== (int)$nguoiThucHienId) {
                return [
                    'thanh_cong' => false,
                    'ma_loi' => 'KHONG_CO_QUYEN',
                    'thong_diep' => 'Bác sĩ không có quyền xác nhận ca khám của bác sĩ khác.',
                    'status' => 403,
                ];
            }
        }

        $lichHen->update([
            'trang_thai' => 'DA_XAC_NHAN',
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Đã duyệt xác nhận lịch hẹn thành công.',
            'du_lieu' => $lichHen,
            'status' => 200,
        ];
    }

    /**
     * BÁC SĨ GỌI KHÁM (BẮT ĐẦU CA KHÁM)
     */
    public function batDauKham(int $id, ?int $nguoiThucHienId = null, ?string $vaiTro = null): array
    {
        $lichHen = LichHen::with('benhNhan')->find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_TIM_THAY_LICH_HEN',
                'thong_diep' => 'Không tìm thấy lịch hẹn.',
                'status' => 404,
            ];
        }

        if ($vaiTro !== 'ADMIN' && $nguoiThucHienId !== null) {
            if ((int)$lichHen->bac_si_id !== (int)$nguoiThucHienId) {
                return [
                    'thanh_cong' => false,
                    'ma_loi' => 'KHONG_CO_QUYEN',
                    'thong_diep' => 'Bác sĩ không có quyền gọi ca khám của bác sĩ khác.',
                    'status' => 403,
                ];
            }
        }

        $lichHen->update([
            'trang_thai' => 'DANG_KHAM',
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Đã chuyển trạng thái ca khám sang Đang khám.',
            'du_lieu' => $lichHen,
            'status' => 200,
        ];
    }

    /**
     * HOÀN THÀNH CA KHÁM
     */
    public function hoanThanh(int $id, array $ketQuaKham = [], ?int $nguoiThucHienId = null, ?string $vaiTro = null): array
    {
        $lichHen = LichHen::with('benhNhan')->find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_TIM_THAY_LICH_HEN',
                'thong_diep' => 'Không tìm thấy lịch hẹn.',
                'status' => 404,
            ];
        }

        if ($vaiTro !== 'ADMIN' && $nguoiThucHienId !== null) {
            if ((int)$lichHen->bac_si_id !== (int)$nguoiThucHienId) {
                return [
                    'thanh_cong' => false,
                    'ma_loi' => 'KHONG_CO_QUYEN',
                    'thong_diep' => 'Bác sĩ không có quyền hoàn thành ca khám của bác sĩ khác.',
                    'status' => 403,
                ];
            }
        }

        $capNhat = [
            'trang_thai' => 'HOAN_THANH',
        ];

        if (isset($ketQuaKham['chuan_doan'])) {
            $capNhat['chuan_doan'] = $ketQuaKham['chuan_doan'];
        }
        if (isset($ketQuaKham['loi_khuyen'])) {
            $capNhat['loi_khuyen'] = $ketQuaKham['loi_khuyen'];
        }
        if (isset($ketQuaKham['ghi_chu_bac_si'])) {
            $capNhat['ghi_chu_bac_si'] = $ketQuaKham['ghi_chu_bac_si'];
        }

        $lichHen->update($capNhat);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Đã hoàn thành ca khám và ghi nhận kết quả.',
            'du_lieu' => $lichHen,
            'status' => 200,
        ];
    }

    /**
     * HỦY LỊCH HẸN
     * Chỉ cho phép hủy khi đang ở trạng thái CHO_XAC_NHAN hoặc DA_XAC_NHAN
     * Bắt buộc có lý do hủy
     */
    public function huy(int $id, ?string $lyDo = null, ?int $nguoiThucHienId = null, ?string $vaiTro = null): array
    {
        $lichHen = LichHen::with('benhNhan')->find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_TIM_THAY_LICH_HEN',
                'thong_diep' => 'Không tìm thấy lịch hẹn.',
                'status' => 404,
            ];
        }

        if (in_array($lichHen->trang_thai, ['DANG_KHAM', 'HOAN_THANH'])) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_THE_HUY',
                'thong_diep' => 'Không thể hủy ca khám đang diễn ra hoặc đã hoàn thành.',
                'status' => 422,
            ];
        }

        // 2. Chặn bệnh nhân hủy lịch sát giờ (< 2 tiếng / 120 phút trước giờ khám)
        // Quản trị viên (ADMIN) có quyền hủy trong tình huống bất khả kháng
        if ($vaiTro !== 'ADMIN') {
            try {
                $thoiGianBatDau = Carbon::parse("{$lichHen->ngay_kham} {$lichHen->gio_bat_dau}");
                $now = Carbon::now();
                if ($thoiGianBatDau->isFuture()) {
                    $phutConLai = $now->diffInMinutes($thoiGianBatDau, false);
                    if ($phutConLai < 120) {
                        return [
                            'thanh_cong' => false,
                            'ma_loi' => 'KHONG_THE_HUY_SAT_GIO',
                            'thong_bao' => 'Không thể hủy ca khám khi thời gian khám còn dưới 2 tiếng. Vui lòng liên hệ Hotline phòng khám.',
                            'thong_diep' => "Không thể hủy lịch khám khi thời gian hẹn còn dưới 2 tiếng ({$phutConLai} phút). Vui lòng liên hệ hotline phòng khám 1900 6868 để được hỗ trợ dời lịch khẩn cấp.",
                            'status' => 422,
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Lỗi kiểm tra hủy sát giờ: " . $e->getMessage());
            }
        }

        $lyDoHuy = $lyDo ?: 'Bệnh nhân yêu cầu hủy lịch';

        $lichHen->update([
            'trang_thai' => 'DA_HUY',
            'ly_do_huy' => $lyDoHuy,
            'ghi_chu_bac_si' => $lichHen->ghi_chu_bac_si ? $lichHen->ghi_chu_bac_si . " | Hủy: {$lyDoHuy}" : "Hủy: {$lyDoHuy}",
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Hủy lịch hẹn khám thành công.',
            'du_lieu' => $lichHen,
            'status' => 200,
        ];
    }

    /**
     * BỘ LỌC LỊCH HẸN ĐA NĂNG
     */
    public function danhSach(array $boLoc = [])
    {
        $query = LichHen::with('benhNhan')->orderBy('ngay_kham', 'desc')->orderBy('gio_bat_dau', 'asc');

        // 1. Lọc theo Bác sĩ
        if (!empty($boLoc['bac_si_id'])) {
            $query->where('bac_si_id', $boLoc['bac_si_id']);
        }

        // 2. Lọc theo Bệnh nhân
        if (!empty($boLoc['benh_nhan_id'])) {
            $query->where('benh_nhan_id', $boLoc['benh_nhan_id']);
        }

        // 3. Lọc theo Tài khoản ID
        if (!empty($boLoc['tai_khoan_id'])) {
            $query->whereHas('benhNhan', function ($q) use ($boLoc) {
                $q->where('tai_khoan_id', $boLoc['tai_khoan_id']);
            });
        }

        // 4. Lọc theo trạng thái
        if (!empty($boLoc['trang_thai'])) {
            $query->where('trang_thai', $boLoc['trang_thai']);
        }

        // 5. Lọc theo ngày cụ thể
        if (!empty($boLoc['ngay_kham'])) {
            $query->where('ngay_kham', $boLoc['ngay_kham']);
        }

        // 6. Lọc theo mốc thời gian: hom_nay, tuan_nay, thang_nay
        if (!empty($boLoc['moc_thoi_gian'])) {
            switch ($boLoc['moc_thoi_gian']) {
                case 'hom_nay':
                    $query->whereDate('ngay_kham', Carbon::today());
                    break;
                case 'tuan_nay':
                    $query->whereBetween('ngay_kham', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'thang_nay':
                    $query->whereBetween('ngay_kham', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    break;
            }
        }

        // 7. Tìm kiếm theo từ khóa (Mã lịch hẹn, Họ tên, SĐT, CCCD)
        if (!empty($boLoc['tu_khoa'])) {
            $tuKhoa = $boLoc['tu_khoa'];
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ma_lich_hen', 'LIKE', "%{$tuKhoa}%")
                  ->orWhere('ly_do_kham', 'LIKE', "%{$tuKhoa}%")
                  ->orWhereHas('benhNhan', function ($bnQuery) use ($tuKhoa) {
                      $bnQuery->where('ho_ten', 'LIKE', "%{$tuKhoa}%")
                              ->orWhere('so_dien_thoai', 'LIKE', "%{$tuKhoa}%")
                              ->orWhere('so_cccd', 'LIKE', "%{$tuKhoa}%")
                              ->orWhere('ma_benh_nhan', 'LIKE', "%{$tuKhoa}%");
                  });
            });
        }

        return $query->get();
    }

    /**
     * XEM CHI TIẾT LỊCH HẸN
     */
    public function chiTiet(int $id)
    {
        return LichHen::with('benhNhan')->find($id);
    }

    /**
     * TẠO MẪU XEM TRƯỚC THÔNG BÁO (EMAIL HTML & SMS BRANDNAME)
     */
    public function xemTruocThongBao(int $id): array
    {
        $lichHen = LichHen::with('benhNhan')->find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy lịch hẹn để xem thông báo.',
            ];
        }

        $bn = $lichHen->benhNhan;
        $tenBn = $bn ? $bn->ho_ten : 'Quý khách';
        $sdt = $bn ? $bn->so_dien_thoai : '09xxxxxxxx';
        $ngayKham = date('d/m/Y', strtotime($lichHen->ngay_kham));
        $gioKham = substr($lichHen->gio_bat_dau, 0, 5);

        $htmlEmail = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;'>
            <div style='background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; padding: 24px; text-align: center;'>
                <h2 style='margin: 0; font-size: 22px;'>PHÒNG KHÁM ĐA KHOA QUỐC TẾ</h2>
                <p style='margin: 6px 0 0; font-size: 14px; opacity: 0.9;'>Xác nhận lịch hẹn khám bệnh trực tuyến</p>
            </div>
            <div style='padding: 24px; background: #ffffff; color: #334155; line-height: 1.6;'>
                <p>Kính gửi: <strong>{$tenBn}</strong>,</p>
                <p>Hệ thống phòng khám trân trọng thông báo lịch hẹn của bạn đã được tiếp nhận và xử lý với thông tin chi tiết như sau:</p>
                
                <table style='width: 100%; border-collapse: collapse; margin: 16px 0; background: #f8fafc; border-radius: 6px;'>
                    <tr>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b;'>Mã lịch hẹn:</td>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0284c7;'>{$lichHen->ma_lich_hen}</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b;'>Ngày khám:</td>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-weight: bold;'>{$ngayKham}</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b;'>Khung giờ:</td>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-weight: bold;'>{$gioKham} - " . substr($lichHen->gio_ket_thuc, 0, 5) . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b;'>Bác sĩ phụ trách:</td>
                        <td style='padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-weight: bold;'>Bác sĩ #{$lichHen->bac_si_id}</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 14px; color: #64748b;'>Trạng thái:</td>
                        <td style='padding: 10px 14px; font-weight: bold; color: #16a34a;'>{$lichHen->trang_thai}</td>
                    </tr>
                </table>

                <p style='color: #ef4444; font-size: 13px;'>* Vui lòng có mặt trước giờ khám 15 phút tại Quầy tiếp đón bệnh nhân để hoàn tất thủ tục khám.</p>
            </div>
            <div style='background: #f1f5f9; padding: 14px 24px; text-align: center; font-size: 12px; color: #64748b;'>
                Hotline hỗ trợ 24/7: 1900 6868 | Địa chỉ: 123 Đường Sức Khỏe, TP. Hồ Chí Minh
            </div>
        </div>";

        $smsBrandname = "[PHONGKHAM] Xac nhan lich hen {$lichHen->ma_lich_hen} cho BN {$tenBn}. Ngay: {$ngayKham} luc {$gioKham}. Vui long den truoc 15 phut. Hotline: 19006868.";

        return [
            'thanh_cong' => true,
            'lich_hen' => $lichHen,
            'email' => [
                'tieu_de' => "[Phòng Khám Đa Khoa] Xác Nhận Lịch Hẹn Khám - Mã {$lichHen->ma_lich_hen}",
                'nguoi_nhan' => $bn?->so_dien_thoai . '@phongkham.vn',
                'noi_dung_html' => $htmlEmail,
            ],
            'sms' => [
                'brandname' => 'PHONGKHAM',
                'so_dien_thoai' => $sdt,
                'noi_dung' => $smsBrandname,
            ],
        ];
    }

    /**
     * LẤY DANH SÁCH KHUNG GIỜ KHÁM KHẢ DỤNG THEO THỜI GIAN THỰC (Realtime Slots)
     * Trả về danh sách ca khám 30 phút trong ngày, đánh dấu slot nào đã kín hoặc đã qua giờ
     */
    public function laySlotsKhaDung(int $bacSiId, string $ngayKham, ?int $loaiTruLichHenId = null): array
    {
        // 13 ca khám tiêu chuẩn (Sáng 08:00 - 11:30, Chiều 13:30 - 16:30)
        $caKhamMau = [
            ['bat_dau' => '08:00:00', 'ket_thuc' => '08:30:00', 'hien_thi' => '08:00 - 08:30', 'buoi' => 'SANG'],
            ['bat_dau' => '08:30:00', 'ket_thuc' => '09:00:00', 'hien_thi' => '08:30 - 09:00', 'buoi' => 'SANG'],
            ['bat_dau' => '09:00:00', 'ket_thuc' => '09:30:00', 'hien_thi' => '09:00 - 09:30', 'buoi' => 'SANG'],
            ['bat_dau' => '09:30:00', 'ket_thuc' => '10:00:00', 'hien_thi' => '09:30 - 10:00', 'buoi' => 'SANG'],
            ['bat_dau' => '10:00:00', 'ket_thuc' => '10:30:00', 'hien_thi' => '10:00 - 10:30', 'buoi' => 'SANG'],
            ['bat_dau' => '10:30:00', 'ket_thuc' => '11:00:00', 'hien_thi' => '10:30 - 11:00', 'buoi' => 'SANG'],
            ['bat_dau' => '11:00:00', 'ket_thuc' => '11:30:00', 'hien_thi' => '11:00 - 11:30', 'buoi' => 'SANG'],
            ['bat_dau' => '13:30:00', 'ket_thuc' => '14:00:00', 'hien_thi' => '13:30 - 14:00', 'buoi' => 'CHIEU'],
            ['bat_dau' => '14:00:00', 'ket_thuc' => '14:30:00', 'hien_thi' => '14:00 - 14:30', 'buoi' => 'CHIEU'],
            ['bat_dau' => '14:30:00', 'ket_thuc' => '15:00:00', 'hien_thi' => '14:30 - 15:00', 'buoi' => 'CHIEU'],
            ['bat_dau' => '15:00:00', 'ket_thuc' => '15:30:00', 'hien_thi' => '15:00 - 15:30', 'buoi' => 'CHIEU'],
            ['bat_dau' => '15:30:00', 'ket_thuc' => '16:00:00', 'hien_thi' => '15:30 - 16:00', 'buoi' => 'CHIEU'],
            ['bat_dau' => '16:00:00', 'ket_thuc' => '16:30:00', 'hien_thi' => '16:00 - 16:30', 'buoi' => 'CHIEU'],
        ];

        // Lấy toàn bộ ca khám đã có trong ngày của bác sĩ (loại trừ các ca đã hủy)
        $query = LichHen::where('bac_si_id', $bacSiId)
            ->where('ngay_kham', $ngayKham)
            ->where('trang_thai', '!=', 'DA_HUY');

        if ($loaiTruLichHenId) {
            $query->where('id', '!=', $loaiTruLichHenId);
        }

        $lichKhamDaCo = $query->get(['id', 'gio_bat_dau', 'gio_ket_thuc', 'trang_thai']);

        $homNay = Carbon::today()->toDateString();
        $gioHienTai = Carbon::now('Asia/Ho_Chi_Minh')->format('H:i:s');
        $laHomNay = ($ngayKham === $homNay);
        $laQuaKhu = ($ngayKham < $homNay);

        $ketQua = [];
        $soSlotKhaDung = 0;

        foreach ($caKhamMau as $slot) {
            $batDau = $slot['bat_dau'];
            $ketThuc = $slot['ket_thuc'];
            $trangThaiSlot = 'CON_TRONG';
            $khaDung = true;
            $lyDo = 'Có thể đặt hẹn';

            if ($laQuaKhu) {
                $trangThaiSlot = 'QUA_GIO';
                $khaDung = false;
                $lyDo = 'Ngày khám trong quá khứ';
            } elseif ($laHomNay && $batDau <= $gioHienTai) {
                $trangThaiSlot = 'QUA_GIO';
                $khaDung = false;
                $lyDo = 'Đã qua khung giờ khám';
            } else {
                // Kiểm tra xem có trùng với ca nào đã có không
                $trung = $lichKhamDaCo->first(function ($item) use ($batDau, $ketThuc) {
                    return ($item->gio_bat_dau < $ketThuc && $item->gio_ket_thuc > $batDau);
                });

                if ($trung) {
                    $trangThaiSlot = 'DA_DAT';
                    $khaDung = false;
                    $lyDo = 'Bác sĩ đã có ca khám (Đã kín chỗ)';
                }
            }

            if ($khaDung) {
                $soSlotKhaDung++;
            }

            $ketQua[] = [
                'bat_dau' => $batDau,
                'ket_thuc' => $ketThuc,
                'hien_thi' => $slot['hien_thi'],
                'buoi' => $slot['buoi'],
                'kha_dung' => $khaDung,
                'trang_thai' => $trangThaiSlot,
                'ly_do' => $lyDo,
            ];
        }

        return [
            'ngay_kham' => $ngayKham,
            'bac_si_id' => $bacSiId,
            'tong_so_slot' => count($caKhamMau),
            'so_slot_kha_dung' => $soSlotKhaDung,
            'slots' => $ketQua,
        ];
    }

    /**
     * BÁC SĨ CẬP NHẬT KẾT LUẬN KHÁM, LỜI DẶN VÀ TOA THUỐC ĐIỆN TỬ
     */
    public function capNhatKetLuanKham(int $id, array $data, ?int $nguoiThucHienId = null, ?string $vaiTro = null): array
    {
        $lichHen = LichHen::with('benhNhan')->find($id);
        if (!$lichHen) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_TIM_THAY_LICH_HEN',
                'thong_diep' => 'Không tìm thấy lịch hẹn.',
                'status' => 404,
            ];
        }

        $chuanDoan = $data['chuan_doan'] ?? ($data['chan_doan'] ?? $lichHen->chuan_doan);
        $loiKhuyen = $data['loi_khuyen'] ?? ($data['loi_dan_bac_si'] ?? $lichHen->loi_khuyen);
        $toaThuoc = $data['toa_thuoc'] ?? $lichHen->toa_thuoc;
        $ngayTaiKham = $data['ngay_tai_kham'] ?? $lichHen->ngay_tai_kham;
        $ghiChu = $data['ghi_chu_bac_si'] ?? $lichHen->ghi_chu_bac_si;

        // Nếu gửi toa thuốc dạng chuỗi JSON thì decode
        if (is_string($toaThuoc)) {
            $decoded = json_decode($toaThuoc, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $toaThuoc = $decoded;
            }
        }

        $capNhat = [
            'chuan_doan' => $chuanDoan,
            'loi_khuyen' => $loiKhuyen,
            'toa_thuoc' => $toaThuoc,
            'ngay_tai_kham' => $ngayTaiKham,
            'ghi_chu_bac_si' => $ghiChu,
        ];

        // Tùy chọn chuyển trạng thái sang HOAN_THANH
        if (!empty($data['chuyen_hoan_thanh']) || !empty($data['hoan_thanh'])) {
            $capNhat['trang_thai'] = 'HOAN_THANH';
        }

        $lichHen->update($capNhat);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Cập nhật kết luận khám và toa thuốc điện tử thành công.',
            'du_lieu' => $lichHen->fresh(['benhNhan']),
            'status' => 200,
        ];
    }

}
