<?php


namespace App\Repositories;


use App\Dtos\CarSaveDto;
use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;

class CarRepository
{
    public function getAll(): Collection|array
    {
        return Car::all();
    }

    public function findById(int $id)
    {
        return Car::query()->find($id);
    }

    public function save(CarSaveDto $params, ?Car $model = null): void
    {
        $model = empty($model) ? new Car() : $model;
        $model->fill((array)$params)
            ->save();
    }

    /**
     * @param integer[] $ids
     */
    public function remove(array $ids): void
    {
        Car::query()->whereIn('id', $ids)->delete();
    }
}
