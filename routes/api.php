<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialMedia\MetaWebhookController;

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

//Meta Route
Route::controller(MetaWebhookController::class)
    ->prefix('meta')
    ->group(function () {
        Route::post('/webhook', 'handle');
        Route::get('/webhook', 'verify');
    });
