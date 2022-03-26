<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResTableController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::post('tables/store', [ResTableController::class, 'store']);
    Route::get('tables/getAll', [ResTableController::class, 'getAll']);
    Route::delete('tables/delete/{id}', [ResTableController::class, 'delete']);

    Route::post('reservation/reservation-available', [ReservationController::class, 'checkReservationAvailable']);
    Route::post('reservation/new', [ReservationController::class, 'newReservation']);
    Route::post('reservation', [ReservationController::class, 'getAll']);
    Route::delete('reservation/delete/{id}', [ReservationController::class, 'delete']);

});
