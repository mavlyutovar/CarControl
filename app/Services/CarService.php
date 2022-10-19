<?php


namespace App\Services;

use App\Dtos\CarSaveDto;
use App\Models\Car;
use App\Repositories\CarRepository;
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

    public function updateCar(array $params, int $id)
    {
        /** @var Car $reason */
        $reason = $this->repository->findById($id);

        if (empty($reason)) {
            //Exception
            return false;
        }

        $this->repository->save(
            $this->createSaveDto(collect($params)),
            $reason
        );
    }

    public function deleteCarsByIds(array $ids): void
    {
        $this->repository->remove($ids);
    }

    #[Pure]
    protected function createSaveDto(SupportCollection $params): CarSaveDto
    {
        $reason                 = new CarSaveDto();
        $reason->name           = $params->get('car_name');
        $reason->model          = $params->get('car_model');
        $reason->price          = $params->get('car_price');
        $reason->description    = $params->get('reason_description');
        return $reason;
    }
}
