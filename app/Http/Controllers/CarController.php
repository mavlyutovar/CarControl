<?php

namespace App\Http\Controllers;

use App\Services\CarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    protected CarService $service;

    public function __construct(CarService $carService)
    {
        $this->service = $carService;
    }


    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'reasons' => $this->service->getCars()
        ]);
    }

    public function create(/*CarRequest*/Request $request)
    {
        $this->service->createCar($request->all());
    }

    public function update(/*CarRequest*/Request $request, $id)
    {
        $this->service->updateCar($request->all(), $id);
    }

    public function delete(Request $request)
    {
        $ids = $request->post('ids');
        if (empty($ids)) {
            return;
        }
        $this->service->deleteCarsByIds($ids);
    }
}
