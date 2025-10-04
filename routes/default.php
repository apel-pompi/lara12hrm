<?php

use App\Http\Controllers\Default\{
    TransactionNameController,
    TransactionController
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

    
});
