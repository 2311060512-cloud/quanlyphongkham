<?php

namespace App\Services;

use App\Models\DichVu;
use App\Models\HoSoKhamBenh;
use App\Models\SuDungDichVu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DichVuService
{
    /**
     * 1. Lấy danh sách dịch vụ kỹ thuật (có bộ lọc và tìm kiếm)
     */
    public function danhSachDichVu(?string $loaiDichVu = null, ?string $tuKhoa = null, bool $chiHoatDong = false)
    {
        $query = DichVu::query();

        if ($chiHoatDong) {
            $query->where('trang_thai', 1);
        }

        if ($loaiDichVu) {
            $query->where('loai_dich_vu', $loaiDichVu);
        }

        if ($tuKhoa) {
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ten_dich_vu', 'like', "%{$tuKhoa}%")
                  ->orWhere('ma_dich_vu', 'like', "%{$tuKhoa}%")
                  ->orWhere('mo_ta', 'like', "%{$tuKhoa}%");
            });
        }

        return $query->orderBy('loai_dich_vu')->orderBy('ten_dich_vu')->get();
    }

    /**
     * 2. Thêm mới dịch vụ y tế
     */
    public function themMoiDichVu(array $data): DichVu
    {
        return DichVu::create([
            'ma_dich_vu' => $data['ma_dich_vu'],
            'ten_dich_vu' => $data['ten_dich_vu'],
            'loai_dich_vu' => $data['loai_dich_vu'],
            'don_gia' => $data['don_gia'],
            'mo_ta' => $data['mo_ta'] ?? null,
            'trang_thai' => $data['trang_thai'] ?? 1,
        ]);
    }

    /**
     * 3. Cập nhật dịch vụ y tế
     */
    public function capNhatDichVu(int $id, array $data): array
    {
        $dv = DichVu::find($id);
        if (!$dv) {
            return ['thanh_cong' => false, 'thong_diep' => 'Không tìm thấy dịch vụ y tế yêu cầu.'];
        }

        $dv->update([
            'ten_dich_vu' => $data['ten_dich_vu'] ?? $dv->ten_dich_vu,
            'loai_dich_vu' => $data['loai_dich_vu'] ?? $dv->loai_dich_vu,
            'don_gia' => $data['don_gia'] ?? $dv->don_gia,
            'mo_ta' => array_key_exists('mo_ta', $data) ? $data['mo_ta'] : $dv->mo_ta,
            'trang_thai' => array_key_exists('trang_thai', $data) ? $data['trang_thai'] : $dv->trang_thai,
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Cập nhật thông tin dịch vụ thành công.',
            'du_lieu' => $dv
        ];
    }

    /**
     * 4. Bật / tắt trạng thái hoạt động dịch vụ
     */
    public function batTatTrangThai(int $id): array
    {
        $dv = DichVu::find($id);
        if (!$dv) {
            return ['thanh_cong' => false, 'thong_diep' => 'Không tìm thấy dịch vụ y tế yêu cầu.'];
        }

        $dv->trang_thai = $dv->trang_thai ? 0 : 1;
        $dv->save();

        $trangThaiStr = $dv->trang_thai ? 'KÍCH HOẠT' : 'TẠM NGƯNG';

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đã chuyển trạng thái dịch vụ {$dv->ten_dich_vu} sang: {$trangThaiStr}.",
            'du_lieu' => $dv
        ];
    }

    /**
     * 5. Bác sĩ kê chỉ định cận lâm sàng cho ca khám
     */
    public function chiDinhDichVu(array $data): array
    {
        $lichHenId = (int)$data['lich_hen_id'];
        $benhNhanId = (int)$data['benh_nhan_id'];
        $bacSiId = (int)$data['bac_si_id'];
        $danhSachDichVuIds = (array)$data['danh_sach_dich_vu_id'];

        $dichVuList = DichVu::whereIn('id', $danhSachDichVuIds)->where('trang_thai', 1)->get();
        if ($dichVuList->isEmpty()) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy dịch vụ y tế hợp lệ để chỉ định.'
            ];
        }

        $chanDoan = $data['chan_doan_so_bo'] ?? ($data['ghi_chu'] ?? null);
        $ketQuaTao = [];

        DB::transaction(function () use ($dichVuList, $lichHenId, $benhNhanId, $bacSiId, $chanDoan, &$ketQuaTao) {
            foreach ($dichVuList as $dv) {
                $item = SuDungDichVu::create([
                    'lich_hen_id' => $lichHenId,
                    'benh_nhan_id' => $benhNhanId,
                    'bac_si_id' => $bacSiId,
                    'dich_vu_id' => $dv->id,
                    'so_luong' => 1,
                    'don_gia' => $dv->don_gia,
                    'ket_qua' => null,
                    'ghi_chu' => $chanDoan,
                    'file_ket_qua' => null,
                    'trang_thai' => 'CHO_THUC_HIEN',
                ]);
                $ketQuaTao[] = $item->load('dichVu');
            }
        });

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Bác sĩ đã kê chỉ định ' . count($ketQuaTao) . ' dịch vụ cận lâm sàng thành công.',
            'du_lieu' => $ketQuaTao
        ];
    }

    /**
     * 6. Hủy chỉ định dịch vụ nếu chưa thực hiện
     */
    public function huyChiDinh(int $id): array
    {
        $suDung = SuDungDichVu::find($id);
        if (!$suDung) {
            return ['thanh_cong' => false, 'thong_diep' => 'Không tìm thấy bản ghi chỉ định dịch vụ.'];
        }

        if ($suDung->trang_thai === 'DA_CO_KET_QUA') {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Không thể hủy dịch vụ đã có kết quả thực hiện cận lâm sàng.'
            ];
        }

        $suDung->update(['trang_thai' => 'DA_HUY']);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Hủy chỉ định dịch vụ cận lâm sàng thành công.',
            'du_lieu' => $suDung
        ];
    }

    /**
     * 7. Hàng đợi chờ thực hiện CLS cho kỹ thuật viên
     */
    public function danhSachChoCanLamSang(?string $loaiDichVu = null, ?string $tuKhoa = null)
    {
        $query = SuDungDichVu::with('dichVu')
            ->where('trang_thai', 'CHO_THUC_HIEN');

        if ($loaiDichVu) {
            $query->whereHas('dichVu', function ($q) use ($loaiDichVu) {
                $q->where('loai_dich_vu', $loaiDichVu);
            });
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    /**
     * 8. Kỹ thuật viên nhập kết quả cận lâm sàng
     */
    public function capNhatKetQua(int $id, array $data): array
    {
        $suDung = SuDungDichVu::with('dichVu')->find($id);
        if (!$suDung) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy bản ghi chỉ định dịch vụ.'
            ];
        }

        $suDung->update([
            'ket_qua' => $data['ket_qua'] ?? $suDung->ket_qua,
            'ghi_chu' => array_key_exists('ghi_chu', $data) ? $data['ghi_chu'] : $suDung->ghi_chu,
            'file_ket_qua' => array_key_exists('file_ket_qua', $data) ? $data['file_ket_qua'] : $suDung->file_ket_qua,
            'trang_thai' => 'DA_CO_KET_QUA',
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Cập nhật kết quả cận lâm sàng / xét nghiệm thành công.',
            'du_lieu' => $suDung
        ];
    }

    /**
     * 9. Lấy danh sách dịch vụ theo lịch hẹn và tổng tiền (dùng cho Người 4)
     */
    public function danhSachTheoLichHen(int $lichHenId)
    {
        $items = SuDungDichVu::with('dichVu')
            ->where('lich_hen_id', $lichHenId)
            ->where('trang_thai', '!=', 'DA_HUY')
            ->get();

        $tongTienCls = $items->sum(fn($i) => (float)$i->don_gia * (int)$i->so_luong);

        return [
            'danh_sach' => $items,
            'tong_tien_cls' => $tongTienCls,
        ];
    }

    /**
     * 10. Bác sĩ chẩn đoán, kê đơn thuốc và hoàn tất ca khám
     */
    public function hoanThanhKhamBenh(int $lichHenId, array $data): array
    {
        $benhNhanId = $data['benh_nhan_id'] ?? null;
        $bacSiId = $data['bac_si_id'] ?? null;

        // Nếu chưa có, cố gắng lấy từ appointment-service
        if (!$benhNhanId || !$bacSiId) {
            try {
                $urlLichHen = config('services.dich_vu_lich_hen', env('DICH_VU_LICH_HEN_URL', 'http://127.0.0.1:8002'));
                $resp = Http::timeout(3)->get("{$urlLichHen}/api/lich-hen/{$lichHenId}");
                if ($resp->successful() && isset($resp['du_lieu'])) {
                    $lh = $resp['du_lieu'];
                    $benhNhanId = $benhNhanId ?: ($lh['benh_nhan_id'] ?? 1);
                    $bacSiId = $bacSiId ?: ($lh['bac_si_id'] ?? 1);
                }
            } catch (\Exception $e) {
                // Fallback
            }
        }

        $hoSo = HoSoKhamBenh::updateOrCreate(
            ['lich_hen_id' => $lichHenId],
            [
                'benh_nhan_id' => $benhNhanId ?: 1,
                'bac_si_id' => $bacSiId ?: 1,
                'trieu_chung' => $data['trieu_chung'] ?? null,
                'chan_doan' => $data['chan_doan'] ?? 'Chưa xác định',
                'don_thuoc' => $data['don_thuoc'] ?? [],
                'loi_dan_bac_si' => $data['loi_dan_bac_si'] ?? ($data['loi_khuyen'] ?? null),
                'ngay_tai_kham' => $data['ngay_tai_kham'] ?? null,
                'trang_thai' => 'HOAN_THANH',
            ]
        );

        // Báo sang appointment-service để cập nhật trạng thái lịch hẹn nếu có
        try {
            $urlLichHen = config('services.dich_vu_lich_hen', env('DICH_VU_LICH_HEN_URL', 'http://127.0.0.1:8002'));
            Http::timeout(3)->put("{$urlLichHen}/api/lich-hen/{$lichHenId}/hoan-thanh", [
                'chuan_doan' => $hoSo->chan_doan,
                'loi_khuyen' => $hoSo->loi_dan_bac_si,
                'toa_thuoc' => $hoSo->don_thuoc,
                'ngay_tai_kham' => $hoSo->ngay_tai_kham,
            ]);
        } catch (\Exception $e) {
            // Không ngắt luồng chính nếu appointment service bận
        }

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Hoàn tất khám bệnh, chẩn đoán và lưu hồ sơ bệnh án thành công.',
            'du_lieu' => $hoSo
        ];
    }

    /**
     * 11. Xem hồ sơ bệnh án của ca khám
     */
    public function layHoSoKhamBenh(int $lichHenId): array
    {
        $hoSo = HoSoKhamBenh::where('lich_hen_id', $lichHenId)->first();
        $dichVuCls = $this->danhSachTheoLichHen($lichHenId);

        if (!$hoSo) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Chưa có hồ sơ khám bệnh cho ca khám này.',
                'can_lam_sang' => $dichVuCls['danh_sach'],
                'tong_tien_cls' => $dichVuCls['tong_tien_cls']
            ];
        }

        return [
            'thanh_cong' => true,
            'du_lieu' => [
                'ho_so' => $hoSo,
                'can_lam_sang' => $dichVuCls['danh_sach'],
                'tong_tien_cls' => $dichVuCls['tong_tien_cls']
            ]
        ];
    }
}
