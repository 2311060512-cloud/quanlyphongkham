<?php

namespace App\Services;

use App\Models\DichVu;
use App\Models\SuDungDichVu;
use Illuminate\Support\Facades\DB;

class DichVuService
{
    public function danhSachDichVu(?string $loaiDichVu = null, ?string $tuKhoa = null)
    {
        $query = DichVu::where('trang_thai', 1);

        if ($loaiDichVu) {
            $query->where('loai_dich_vu', $loaiDichVu);
        }

        if ($tuKhoa) {
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ten_dich_vu', 'like', "%{$tuKhoa}%")
                  ->orWhere('ma_dich_vu', 'like', "%{$tuKhoa}%");
            });
        }

        return $query->get();
    }

    public function chiDinhDichVu(array $data): array
    {
        $lichHenId = (int)$data['lich_hen_id'];
        $benhNhanId = (int)$data['benh_nhan_id'];
        $bacSiId = (int)$data['bac_si_id'];
        $danhSachDichVuIds = $data['danh_sach_dich_vu_id']; // Mang cac ID dich vu

        $dichVuList = DichVu::whereIn('id', $danhSachDichVuIds)->where('trang_thai', 1)->get();
        if ($dichVuList->isEmpty()) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Khong tim thay dich vu y te hop le de chi dinh.'
            ];
        }

        $chanDoan = $data['chan_doan_so_bo'] ?? null;
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

    public function capNhatKetQua(int $id, array $data): array
    {
        $suDung = SuDungDichVu::with('dichVu')->find($id);
        if (!$suDung) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Khong tim thay ban ghi chi dinh dich vu.'
            ];
        }

        $suDung->update([
            'ket_qua' => $data['ket_qua'] ?? $suDung->ket_qua,
            'ghi_chu' => $data['ghi_chu'] ?? $suDung->ghi_chu,
            'file_ket_qua' => $data['file_ket_qua'] ?? $suDung->file_ket_qua,
            'trang_thai' => 'DA_CO_KET_QUA',
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Cap nhat ket qua xet nghiem / can lam sang thanh cong.',
            'du_lieu' => $suDung
        ];
    }

    public function danhSachTheoLichHen(int $lichHenId)
    {
        return SuDungDichVu::with('dichVu')
            ->where('lich_hen_id', $lichHenId)
            ->where('trang_thai', '!=', 'DA_HUY')
            ->get();
    }
}
