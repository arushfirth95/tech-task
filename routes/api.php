<?php

use App\Http\Controllers\CarParkBookingController;
use App\Http\Middleware\ValidateAmendBookingEndpoint;
use App\Http\Middleware\ValidateCancelBookingEndpoint;
use App\Http\Middleware\ValidateCheckAvailabilityEndpoint;
use App\Http\Middleware\ValidateCheckPriceEndpoint;
use App\Http\Middleware\ValidateCreateBookingEndpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/check-availability', [CarParkBookingController::class, 'checkAvailability'])->middleware(ValidateCheckAvailabilityEndpoint::class);
Route::post('/check-price', [CarParkBookingController::class, 'checkPrice'])->middleware(ValidateCheckPriceEndpoint::class);
Route::post('/create-booking', [CarParkBookingController::class, 'createBooking'])->middleware(ValidateCreateBookingEndpoint::class);
Route::post('/cancel-booking', [CarParkBookingController::class, 'cancelBooking'])->middleware(ValidateCancelBookingEndpoint::class);
Route::post('/amend-booking', [CarParkBookingController::class, 'amendBooking'])->middleware(ValidateAmendBookingEndpoint::class);
