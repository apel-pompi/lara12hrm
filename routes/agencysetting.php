<?php

use App\Http\Controllers\AgencySetting\{
    GeneralController,
    FeesController,
    InstallmentController,
    AcademicController,
    WorkflowController,
    WDocumentCheckController,
    WDocumentTypeController,
    DriveController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth'])->group(function () {
    //General Master
    Route::controller(GeneralController::class)
        ->prefix('general')
        ->group(
            function () {
                // master category
                Route::get('/', 'index')->name('general.index');
                Route::get('/patnersetup', 'patnersetup')->name('general.patnersetup');
                Route::get('/productsetup', 'productsetup')->name('general.productsetup');
                Route::post('/store', 'store')->name('general.store');
                Route::get('/{general}/edit', 'edit')->name('general.edit');
                Route::put('/{general}', 'update')->name('general.update');
                Route::put('/{general}/status', 'updateStatus')->name('general.updateStatus');
                Route::get('/{general}', 'show')->name('general.show');
                Route::delete('/show/{general}', 'destroy')->name('general.destroy');
                // partner type setup

                Route::post('/patnersetup', 'patnersetupstore')->name('general.patnersetupstore');
                Route::put('/{patnersetup}/patnersetupstatus', 'patnersetupUpdateStatus')->name('general.patnersetupUpdateStatus');
                Route::delete('patnersetup/show/{patnersetup}', 'patnersetupdestroy')->name('patnersetup.patnersetupdestroy');
                // product type setup
                Route::post('/productsetup', 'productsetuppstore')->name('general.productsetuppstore');
                Route::put('/{productsetup}/producttypeupstatus', 'producttypeUpdateStatus')->name('general.producttypeUpdateStatus');
                Route::delete('productsetup/show/{productsetup}', 'productsetupdestroy')->name('productsetup.productsetupdestroy');
            }
        );
    
     //Workflows Route
    Route::controller(WorkflowController::class)
        ->prefix('workflow')
        ->group(
            function () {
                Route::get('/', 'index')->name('workflow.index');
                Route::post('/store', 'store')->name('workflow.store');
                Route::put('/{workflow}/status', 'updateStatus')->name('workflow.updateStatus');
                Route::get('/{workflow}', 'show')->name('workflow.show');
                Route::delete('/show/{workflow}', 'destroy')->name('workflow.destroy');
                Route::get('/{workflow}/edit', 'edit')->name('workflow.edit');
                Route::put('/{workflow}', 'update')->name('workflow.update');
            }
        );

    //Workflows document list
    Route::controller(WDocumentCheckController::class)
        ->prefix('documentlist')
        ->group(
            function () {
                Route::get('/{id}', 'index')->name('documentlist.index');
                Route::post('/store', 'store')->name('documentlist.store');
                Route::get('/{id}/adddoctype', 'adddoctype')->name('documenttype.adddoctype');
            }
        );
    //Workflows document type
    Route::controller(WDocumentTypeController::class)
        ->prefix('documenttype')
        ->group(
            function () {
                Route::get('/', 'index')->name('documenttype.index');
                Route::post('/store', 'store')->name('documenttype.store');
                Route::put('/{id}/status', 'updateStatus')->name('documenttype.updateStatus');
                Route::get('/{id}/edit', 'edit')->name('documenttype.edit');
                Route::put('/{id}', 'update')->name('documenttype.update');
            }
        );
    Route::controller(FeesController::class)
        ->prefix('fees')
        ->group(
            function () {
                Route::get('/', 'index')->name('fees.index');
                Route::post('/store', 'store')->name('fees.store');
                Route::put('/{fees}/status', 'updateStatus')->name('fees.updateStatus');
                Route::get('/{fees}', 'show')->name('fees.show');
                Route::delete('/show/{fees}', 'destroy')->name('fees.destroy');
                Route::get('/{fees}/edit', 'edit')->name('fees.edit');
                Route::put('/{fees}', 'update')->name('fees.update');
            }
        );
    
    Route::controller(InstallmentController::class)
        ->prefix('installment')
        ->group(
            function () {
                Route::get('/', 'index')->name('installment.index');
                Route::post('/store', 'store')->name('installment.store');
                Route::put('/{installment}/status', 'updateStatus')->name('installment.updateStatus');
                Route::get('/{installment}', 'show')->name('installment.show');
                Route::delete('/show/{installment}', 'destroy')->name('installment.destroy');
                Route::get('/{installment}/edit', 'edit')->name('installment.edit');
                Route::put('/{installment}', 'update')->name('installment.update');
            }
        );
    //Academic route
    Route::controller(AcademicController::class)
        ->prefix('academics')
        ->group(
            function () {
                Route::get('/', 'index')->name('academic.index');
                Route::post('/store', 'store')->name('academic.store');
                Route::put('/{academic}/status', 'updateStatus')->name('academic.updateStatus');
                Route::get('/{academic}', 'show')->name('academic.show');
                Route::delete('/show/{academic}', 'destroy')->name('academic.destroy');
                Route::get('/{academic}/edit', 'edit')->name('academic.edit');
                Route::put('/{academic}', 'update')->name('academic.update');
            }
        );
    //google drive Route
    Route::controller(DriveController::class)
        ->prefix('gdrive')
        ->group(
            function () {
                Route::get('/folders', 'listDriveFolders')->name('drive.folders');
                Route::post('/upload', 'uploadFile')->name('drive.upload');
            }
        );
    
});


