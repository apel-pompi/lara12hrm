<?php

use App\Http\Controllers\Student\{
    StudentStageController,
    StudentSourceController,
    StudentController,
    StudentActivitiesController,
    StudentApplicationController,
    StudentInServiceController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth'])->group(function () {

    // Student Stage
    Route::controller(StudentStageController::class)
        ->prefix('studentStage')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentStage.index');
                Route::get('/create', 'create')->name('studentStage.create');
                Route::post('/store', 'store')->name('studentStage.store');
                Route::put('/{studentStage}/status', 'updateStatus')->name('studentStage.updateStatus');
                Route::get('/{studentStage}/edit', 'edit')->name('studentStage.edit');
                Route::put('/{studentStage}', 'update')->name('studentStage.update');
                Route::delete('/show/{studentStage}', 'destroy')->name('studentStage.destroy');
            }
    );

    // Student Source
    Route::controller(StudentSourceController::class)
        ->prefix('studentSource')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentSource.index');
                Route::get('/create', 'create')->name('studentSource.create');
                Route::post('/store', 'store')->name('studentSource.store');
                Route::put('/{studentSource}/status', 'updateStatus')->name('studentSource.updateStatus');
                Route::get('/{studentSource}/edit', 'edit')->name('studentSource.edit');
                Route::put('/{studentSource}', 'update')->name('studentSource.update');
                Route::delete('/show/{studentSource}', 'destroy')->name('studentSource.destroy');
            }
    );

    //student Route
    Route::controller(StudentController::class)
        ->prefix('student')
        ->group(
            function () {
                Route::get('/', 'index')->name('student.index');
                Route::get('/create', 'create')->name('student.create');
                Route::post('/store', 'store')->name('student.store');
                Route::put('/{student}/status', 'updateStatus')->name('student.updateStatus');
                Route::put('/{student}', 'update')->name('student.update');
                Route::delete('/show/{student}', 'destroy')->name('student.destroy');
            }
        );
    
    // Student Activities
    Route::controller(StudentActivitiesController::class)
        ->prefix('student/activities/{student}/allactivities')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentActivities.index');
                Route::put('/status', 'updateRate')->name('studentActivities.updateRate');
            }
    );

    // Student Application
    Route::controller(StudentApplicationController::class)
    ->prefix('student/activities/{student}/application')
    ->group(function () {
        Route::get('/', 'index')->name('studentApplication.index');
        Route::get('/{partner}/partner', 'partner')->name('studentApplication.partner');
        Route::get('/{product}/{partner}/product', 'product')->name('studentApplication.product');
        Route::post('/store', 'store')->name('studentApplication.store');
        Route::get('/{studentApplication}/edit', 'edit')->name('studentApplication.edit');
        Route::put('/{studentApplication}', 'update')->name('studentApplication.update');
        Route::delete('/show/{studentApplication}', 'destroy')->name('studentApplication.destroy');
        Route::get('{studentApplication}/edit', 'editApplication')->name('studentApplication.editApplication');
    });

    // Student Interest Service
    Route::controller(StudentInServiceController::class)
    ->prefix('student/activities/{student}/interestedservice')
    ->group(function () {
        Route::get('/', 'index')->name('studentInService.index');
        Route::get('/{partner}/partner', 'partner')->name('studentInService.partner');
        Route::get('/{product}/{partner}/product', 'product')->name('studentInService.product');
        Route::post('/store', 'store')->name('studentInService.store');
        Route::post('/create', 'create')->name('studentInService.create');
        Route::get('/{studentInService}/edit', 'edit')->name('studentInService.edit');
        Route::put('/{studentInService}', 'update')->name('studentInService.update');
        Route::delete('/show/{studentInService}', 'destroy')->name('studentInService.destroy');
        Route::get('{studentInService}/edit', 'editApplication')->name('studentInService.editApplication');
    });
});
