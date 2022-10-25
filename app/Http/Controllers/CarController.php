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
            'cars' => $this->service->getCars()
        ]);
    }

    public function create(/*CarRequest*/Request $request)
    {
        $this->service->createCar($request->all());
        return response()->json([
            'cars' => $this->service->getCars()
        ]);
    }

    public function save(/*CarRequest*/Request $request)
    {
        $userId = $request->user()->id;
        $this->service->updateCar($request->all(), $userId);
    }

    public function edit(/*CarRequest*/Request $request, $id)
    {
        $userId = $request->user()->id;
        return $this->service->editCar($id, $userId);
    }

    public function delete(Request $request, $id)
    {
        if (empty($id)) {
            return;
        }
        $this->service->deleteCarsById($id);
    }
}
