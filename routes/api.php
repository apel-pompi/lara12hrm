<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialMedia\MetaWebhookController;
use App\Http\Controllers\SocialMedia\FollowUp\FollowUpTimelineController;

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

//FollowUp Timeline Route
Route::get(
    'follow-up-activities/{followUpActivity}/timeline',
    [FollowUpTimelineController::class, 'index']
)->name('follow-up-activities.timeline');

Route::get(
    'students/{student}/follow-up-timeline',
    [FollowUpTimelineController::class, 'student']
)->name('students.follow-up-timeline');
