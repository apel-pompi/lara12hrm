<?php

use App\Http\Controllers\Default\{
    ExcelImportController,
    TransactionController,
    ApprovalRequestController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth','isBanned','UserActivity'])->group(function () {
    //Upload Lead
    Route::controller(ExcelImportController::class)
        ->prefix('imports')
        ->group(
            function () {
                Route::get('/', 'showImportForm')->name('imports.showImportForm');
                Route::post('/import', 'import')->name('imports.import');
                Route::get('/downloadTemplate', 'downloadTemplate')->name('imports.downloadTemplate');
            }
        );
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
    //approval request Route
    Route::controller(ApprovalRequestController::class)
        ->prefix('approval')
        ->group(
            function () {
                Route::post('/studentArchive/{student}', 'studentArchive')->name('approval.studentArchive');
            }
        );
});
