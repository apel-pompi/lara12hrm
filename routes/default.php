<?php

use App\Http\Controllers\Default\{
    TransactionNameController,
    TransactionController,
    FeesController,
    InstallmentController,
    AcademicController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth'])->group(function () {
    //transaction Route
    Route::controller(TransactionController::class)
        ->prefix('transaction')
        ->group(
            function () {
                Route::get('/', 'index')->name('transaction.index');
                Route::post('/store', 'store')->name('transaction.store');
                Route::put('/{transaction}/status', 'updateStatus')->name('transaction.updateStatus');
                Route::get('/{transaction}', 'show')->name('transaction.show');
                Route::delete('/show/{transaction}', 'destroy')->name('transaction.destroy');
                Route::get('/{transaction}/edit', 'edit')->name('transaction.edit');
                Route::put('/{transaction}', 'update')->name('transaction.update');
            }
        );
    
    //transaction name Route
    Route::controller(TransactionNameController::class)
        ->prefix('transactionName')
        ->group(
            function () {
                Route::get('/', 'index')->name('transactionName.index');
                Route::post('/store', 'store')->name('transactionName.store');
                Route::put('/{transactionName}/status', 'updateStatus')->name('transactionName.updateStatus');
                Route::delete('/show/{transactionName}', 'destroy')->name('transactionName.destroy');
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
});
