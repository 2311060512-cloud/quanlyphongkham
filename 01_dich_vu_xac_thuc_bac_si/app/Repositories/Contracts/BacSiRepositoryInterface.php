<?php

namespace App\Repositories\Contracts;

use App\Models\BacSi;
use Illuminate\Database\Eloquent\Collection;

interface BacSiRepositoryInterface
{
    public function danhSach(array $boLoc = []): Collection;
    public function timTheoId(int $id): ?BacSi;
    public function taoMoi(array $duLieu): BacSi;
    public function capNhat(int $id, array $duLieu): ?BacSi;
}
