<?php


namespace App\Services;

use App\Dtos\CarSaveDto;
use App\Models\Car;
use App\Repositories\CarRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use JetBrains\PhpStorm\Pure;
use Illuminate\Support\Collection as SupportCollection;

class CarService
{

    public function __construct(
        protected CarRepository $repository,
    ){}

    public function getCars(): Collection|array {
        return $this->repository->getAll();
    }

    public function createCar($params): void
    {
        $this->repository->save(
            $this->createSaveDto(collect($params)),
        );
    }

    public function updateCar(array $params, $userId)
    {
        /** @var Car $userEditCar */
        $userEditCar = $this->repository->findByLockedBy($userId);
        if (empty($userEditCar)) {
            //Exception
            return false;
        }
        if($params['locked'] == 'save') {
            $this->repository->save(
                $this->createSaveDto(collect($params)),
                $userEditCar
            );
        }
        $this->repository->clearEditTime($userEditCar);
    }

    public function editCar(int $id, int $userId)
    {
        /** @var Car $car */
        $car = $this->repository->findById($id);

        if (isset($car)) {
            if(isset($car->locked_by)) {
                if($car->locked_at < Carbon::now()){
                    $this->repository->clearEditTime($car);
                }
                else {
                    if($car->locked_by != $userId){
                        return response()->json([
                            'message' => "Автомобиль редактируется другим пользователем"
                        ]);
                    }
                }
            }
        }
        else {return false;}

        /** @var Car $userEditCar */
        $userEditCar = $this->repository->findByLockedBy($userId);
        if(isset($userEditCar)) {
            if ($userEditCar->locked_by != $car->locked_by) {
                if($userEditCar->locked_at < Carbon::now()){
                    $this->repository->clearEditTime($userEditCar);
                }
                else {
                    return response()->json([
                        'message' => "Пользователь уже редактирует один из автомобилей"
                    ]);
                }
            }
        }
        return $this->repository->updateEditTime(Carbon::now()->addMinutes(3), $userId, $car);
    }

    public function deleteCarsById(int $id): void
    {
        $this->repository->remove($id);
    }

    #[Pure]
    protected function createSaveDto(SupportCollection $params): CarSaveDto
    {
        $car                 = new CarSaveDto();
        $car->name           = $params->get('car_name');
        $car->model          = $params->get('car_model');
        $car->price          = (int)$params->get('car_price');
        $car->description    = $params->get('car_description');
        return $car;
    }
}
