<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function () {
    return User::all();
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('add-car',[CarController::class, 'create'])
        ->name('add.car');
    Route::post('save-car',[CarController::class, 'save'])
        ->name('save.car');
    Route::post('show-cars',[CarController::class, 'index'])
        ->name('show.car');
    Route::post('delete-car/{id}',[CarController::class, 'delete'])
        ->name('delete.car');
    Route::post('edit-car/{id}',[CarController::class, 'edit'])
        ->name('edit.car');

});


Route::post("login",[UserController::class,'index']);
