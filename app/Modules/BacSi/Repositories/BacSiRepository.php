<?php

namespace App\Modules\BacSi\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\BacSi\Models\BacSi;
use Illuminate\Database\Eloquent\Collection;

class BacSiRepository extends BaseRepository implements BacSiRepositoryInterface
{
    public function __construct(BacSi $model)
    {
        parent::__construct($model);
    }

    public function layDanhSachTheoKhoa(?int $chuyenKhoaId = null, ?string $tuKhoa = null): Collection
    {
        $query = $this->model->with('chuyenKhoa')->where('trang_thai', 'DANG_LAM_VIEC');

        if ($chuyenKhoaId) {
            $query->where('chuyen_khoa_id', $chuyenKhoaId);
        }

        if (!empty($tuKhoa)) {
            $tuKhoa = trim($tuKhoa);
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('ho_ten', 'like', "%{$tuKhoa}%")
                  ->orWhere('hoc_vi', 'like', "%{$tuKhoa}%")
                  ->orWhere('phong_kham', 'like', "%{$tuKhoa}%")
                  ->orWhere('kinh_nghiem', 'like', "%{$tuKhoa}%")
                  ->orWhereHas('chuyenKhoa', function ($ckQuery) use ($tuKhoa) {
                      $ckQuery->where('ten_khoa', 'like', "%{$tuKhoa}%");
                  });
            });
        }

        return $query->get();
    }

    public function timTheoTaiKhoanId(int $taiKhoanId)
    {
        return $this->model->with('chuyenKhoa')->where('tai_khoan_id', $taiKhoanId)->first();
    }
}
