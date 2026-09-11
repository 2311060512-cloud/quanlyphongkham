<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function layTatCa(array $cot = ['*']): Collection;
    public function phanTrang(int $soMoiTrang = 10, array $cot = ['*']): LengthAwarePaginator;
    public function timTheoId(int|string $id): ?Model;
    public function taoMoi(array $duLieu): Model;
    public function capNhat(int|string $id, array $duLieu): bool;
    public function xoa(int|string $id): bool;
}
