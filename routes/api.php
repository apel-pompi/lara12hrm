<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\HRM\DeviceController;

Route::controller(DeviceController::class)
    ->prefix('attendances')
    ->group(function () {
        Route::get('/', 'syncData')->name('device.syncData');
        Route::post('/store', 'store');
    });
