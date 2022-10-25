<?php


namespace App\Repositories;


use App\Dtos\CarSaveDto;
use App\Models\Car;
use Carbon\Carbon;
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

    public function findByLockedBy(int $userId)
    {
        return Car::query()->where("locked_by", $userId)->first();
    }

    public function updateEditTime(Carbon $time, int $userId, Car $model)
    {
        $model->locked_at = $time;
        $model->locked_by = $userId;
        $model->save();
        return $model;
    }

    public function clearEditTime(Car $model)
    {
        $model->locked_at = null;
        $model->locked_by = null;
        $model->save();
        return $model;
    }

    public function save(CarSaveDto $params, ?Car $model = null): void
    {
        $model = empty($model) ? new Car() : $model;
        $model->fill((array)$params)
            ->save();
    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {
        Car::query()->where('id', $id)->delete();
    }
}
