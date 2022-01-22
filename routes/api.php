<?php

use App\Http\Controllers\DiscountCardController;
use App\Http\Controllers\OccupancyController;
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

Route::get('/getFreeSpots', [OccupancyController::class, 'getFreeSpots']);
Route::match(['get', 'post'], '/getCurrentBillAmount', [OccupancyController::class, 'getCurrentBillAmount']);
Route::post('/checkoutVehicle', [OccupancyController::class, 'checkoutVehicle']);
Route::post('/enterVehicle', [OccupancyController::class, 'enterVehicle']);

Route::post('/issueCard', [DiscountCardController::class, 'issueCard']);
Route::match(['get', 'post'], '/checkCardValidity', [DiscountCardController::class, 'checkCardValidity']);
Route::post('/invalidateCard', [DiscountCardController::class, 'invalidateCard']);
