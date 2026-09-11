<?php

namespace App\Modules\LichHen\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\LichHen\Models\LichHen;
use Illuminate\Database\Eloquent\Collection;

class LichHenRepository extends BaseRepository implements LichHenRepositoryInterface
{
    public function __construct(LichHen $model)
    {
        parent::__construct($model);
    }

    public function kiemTraTrungLich(int $bacSiId, string $ngayKham, string $gioKham, ?int $boQuaId = null): bool
    {
        $xungDot = $this->kiemTraTrungLichNangCao($bacSiId, $ngayKham, $gioKham, $boQuaId, 30);
        return $xungDot !== null;
    }

    public function kiemTraTrungLichNangCao(int $bacSiId, string $ngayKham, string $gioKham, ?int $boQuaId = null, int $khoangCachPhut = 30): ?array
    {
        $query = $this->model
            ->where('bac_si_id', $bacSiId)
            ->where('ngay_kham', $ngayKham)
            ->whereNotIn('trang_thai', ['DA_HUY']);

        if ($boQuaId) {
            $query->where('id', '!=', $boQuaId);
        }

        $danhSachCa = $query->get();

        $targetParts = explode(':', trim($gioKham));
        if (count($targetParts) < 2) {
            return ['xung_dot' => true, 'ly_do' => 'Định dạng giờ khám không hợp lệ (cần định dạng HH:mm).'];
        }
        $targetMinutes = (int)$targetParts[0] * 60 + (int)$targetParts[1];

        foreach ($danhSachCa as $ca) {
            $caParts = explode(':', trim($ca->gio_kham));
            if (count($caParts) < 2) continue;
            $caMinutes = (int)$caParts[0] * 60 + (int)$caParts[1];

            $diff = abs($targetMinutes - $caMinutes);
            if ($diff < $khoangCachPhut) {
                return [
                    'xung_dot' => true,
                    'gio_da_dat' => $ca->gio_kham,
                    'ma_lich_hen' => $ca->ma_lich_hen,
                    'chenh_lech' => $diff,
                    'khoang_cach_yeu_cau' => $khoangCachPhut,
                    'ly_do' => "Bác sĩ đã có lịch khám lúc {$ca->gio_kham}. Mỗi ca khám cần cách nhau tối thiểu {$khoangCachPhut} phút để đảm bảo chất lượng thăm khám."
                ];
            }
        }

        return null;
    }

    public function layMaLichHenTiepTheo(): string
    {
        $maxId = (int) $this->model->max('id');
        $nextNumber = $maxId + 1;
        return 'LK' . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function layTheoBenhNhan(int $benhNhanId, ?string $tuKhoa = null): Collection
    {
        return $this->layDanhSachCoLoc([
            'benh_nhan_id' => $benhNhanId,
            'tu_khoa' => $tuKhoa,
        ]);
    }

    public function layTheoBacSi(int $bacSiId, ?string $ngayKham = null): Collection
    {
        return $this->layDanhSachCoLoc([
            'bac_si_id' => $bacSiId,
            'ngay_kham' => $ngayKham,
        ]);
    }

    public function layDanhSachCoLoc(array $boLoc): Collection
    {
        $query = $this->model->with(['benhNhan', 'bacSi.chuyenKhoa', 'hoaDon', 'suDungDichVu.dichVu']);

        if (!empty($boLoc['benh_nhan_id'])) {
            $query->where('benh_nhan_id', $boLoc['benh_nhan_id']);
        }

        if (!empty($boLoc['bac_si_id'])) {
            $query->where('bac_si_id', $boLoc['bac_si_id']);
        }

        if (!empty($boLoc['trang_thai']) && $boLoc['trang_thai'] !== 'TAT_CA') {
            if (is_array($boLoc['trang_thai'])) {
                $query->whereIn('trang_thai', $boLoc['trang_thai']);
            } else {
                $query->where('trang_thai', $boLoc['trang_thai']);
            }
        }

        if (!empty($boLoc['ngay_kham'])) {
            $query->where('ngay_kham', $boLoc['ngay_kham']);
        }

        if (!empty($boLoc['tu_ngay'])) {
            $query->where('ngay_kham', '>=', $boLoc['tu_ngay']);
        }

        if (!empty($boLoc['den_ngay'])) {
            $query->where('ngay_kham', '<=', $boLoc['den_ngay']);
        }

        if (!empty($boLoc['moc_thoi_gian'])) {
            $today = date('Y-m-d');
            switch ($boLoc['moc_thoi_gian']) {
                case 'hom_nay':
                    $query->where('ngay_kham', $today);
                    break;
                case 'tuan_nay':
                    $query->whereBetween('ngay_kham', [
                        now()->startOfWeek()->format('Y-m-d'),
                        now()->endOfWeek()->format('Y-m-d')
                    ]);
                    break;
                case 'thang_nay':
                    $query->whereBetween('ngay_kham', [
                        now()->startOfMonth()->format('Y-m-d'),
                        now()->endOfMonth()->format('Y-m-d')
                    ]);
                    break;
            }
        }

        if (!empty($boLoc['tu_khoa'])) {
            $tuKhoa = trim($boLoc['tu_khoa']);
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ma_lich_hen', 'like', "%{$tuKhoa}%")
                  ->orWhere('trieu_chung', 'like', "%{$tuKhoa}%")
                  ->orWhere('chuan_doan', 'like', "%{$tuKhoa}%")
                  ->orWhereHas('bacSi', function ($bsQuery) use ($tuKhoa) {
                      $bsQuery->where('ho_ten', 'like', "%{$tuKhoa}%")
                              ->orWhereHas('chuyenKhoa', function ($ckQuery) use ($tuKhoa) {
                                  $ckQuery->where('ten_khoa', 'like', "%{$tuKhoa}%");
                              });
                  })
                  ->orWhereHas('benhNhan', function ($bnQuery) use ($tuKhoa) {
                      $bnQuery->where('ho_ten', 'like', "%{$tuKhoa}%")
                              ->orWhere('so_dien_thoai', 'like', "%{$tuKhoa}%")
                              ->orWhere('so_cccd', 'like', "%{$tuKhoa}%");
                  });
            });
        }

        return $query->orderBy('ngay_kham', 'desc')
            ->orderBy('gio_kham', 'desc')
            ->get();
    }
}
