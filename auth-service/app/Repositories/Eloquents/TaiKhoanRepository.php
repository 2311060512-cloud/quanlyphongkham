<?php

namespace App\Repositories\Eloquents;

use App\Models\TaiKhoan;
use App\Repositories\Contracts\TaiKhoanRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class TaiKhoanRepository implements TaiKhoanRepositoryInterface
{
    public function danhSach(): \Illuminate\Database\Eloquent\Collection
    {
        return TaiKhoan::with('vaiTro')->orderBy('id', 'asc')->get();
    }

    public function timTheoTenDangNhapHoacEmail(string $giaTri): ?TaiKhoan
    {
        return TaiKhoan::with('vaiTro')
            ->where('ten_dang_nhap', $giaTri)
            ->orWhere('email', $giaTri)
            ->first();
    }

    public function timTheoId(int $id): ?TaiKhoan
    {
        return TaiKhoan::with('vaiTro')->find($id);
    }

    public function taoMoi(array $duLieu): TaiKhoan
    {
        return TaiKhoan::create($duLieu);
    }

    public function capNhat(int $id, array $duLieu): ?TaiKhoan
    {
        $tk = TaiKhoan::find($id);
        if ($tk) {
            $tk->update($duLieu);
            return $tk->fresh();
        }
        return null;
    }

    public function doiTrangThai(int $id, string $trangThai): bool
    {
        $tk = TaiKhoan::find($id);
        if ($tk) {
            $tk->trang_thai = $trangThai;
            return $tk->save();
        }
        return false;
    }

    public function doiMatKhau(int $id, string $matKhauMoi): bool
    {
        $tk = TaiKhoan::find($id);
        if ($tk) {
            $tk->mat_khau = Hash::make($matKhauMoi);
            return $tk->save();
        }
        return false;
    }

    public function xoa(int $id): bool
    {
        $tk = TaiKhoan::find($id);
        return $tk ? (bool)$tk->delete() : false;
    }
}
