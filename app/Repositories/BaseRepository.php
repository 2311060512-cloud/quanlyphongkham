<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function layTatCa(array $cot = ['*']): Collection
    {
        return $this->model->all($cot);
    }

    public function phanTrang(int $soMoiTrang = 10, array $cot = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($soMoiTrang, $cot);
    }

    public function timTheoId(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function taoMoi(array $duLieu): Model
    {
        return $this->model->create($duLieu);
    }

    public function capNhat(int|string $id, array $duLieu): bool
    {
        $banGhi = $this->timTheoId($id);
        if ($banGhi) {
            return $banGhi->update($duLieu);
        }
        return false;
    }

    public function xoa(int|string $id): bool
    {
        $banGhi = $this->timTheoId($id);
        if ($banGhi) {
            return $banGhi->delete();
        }
        return false;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
