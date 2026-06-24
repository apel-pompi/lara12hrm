<?php

use App\Http\Controllers\Default\{
    ExcelImportController,
    TransactionController,
    ApprovalRequestController,
    FacebookController,
    SocialMediaSetupController,
    UserWiseFormController
};
use Illuminate\Support\Facades\Route;


Route::middleware(['verified', 'auth', 'isBanned', 'UserActivity'])->group(function () {
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
                Route::post('/studentTransfer/{student}', 'studentTransfer')->name('approval.studentTransfer');
                Route::post('/studentOnBoard/{student}', 'studentOnBoard')->name('approval.studentOnBoard');
                Route::post('/{leave}/leaveRequest', 'leaveRequest')->name('approval.leaveRequest');
                Route::post('/{leave}/leaveApproved', 'leaveApproved')->name('approval.leaveApproved');
                Route::post('/{leave}/leaveCancel', 'leaveCancel')->name('approval.leaveCancel');
                Route::get('/{quotation}/QuoattionView', 'QuoattionView')->name('approval.QuoattionView');
                Route::put('/{quotation}/QuoattionConfirm', 'QuoattionConfirm')->name('approval.QuoattionConfirm');
                Route::put('/{quotation}/QuoattionCancel', 'QuoattionCancel')->name('approval.QuoattionCancel');
                Route::put('/{return}/ReturnConfirm', 'ReturnConfirm')->name('approval.ReturnConfirm');
                Route::put('/{return}/ReturnCancel', 'ReturnCancel')->name('approval.ReturnCancel');
            }
        );
    //Facebook Route
    Route::controller(FacebookController::class)
        ->prefix('facebook')
        ->group(function () {
            Route::get('facebookForm', 'facebookForm')->name('facebook.facebookForm');
            Route::get('/sync', 'syncFacebookForms')->name('facebook.syncFacebookForms');
            Route::delete('/show/{formId}', 'deleteFacebookForm')->name('facebook.deleteFacebookForm');
        });

    //social media setup Route
    Route::controller(SocialMediaSetupController::class)
        ->prefix('social-media-setup')
        ->group(
            function () {
                Route::get('/', 'index')->name('social-media-setup.index');
                Route::post('/store', 'store')->name('social-media-setup.store');
                Route::put('/{socialMediaSetup}/update', 'update')->name('social-media-setup.update');
                Route::delete('/{socialMediaSetup}/destroy', 'destroy')->name('social-media-setup.destroy');
            }
        );
    //user wise form Route
    Route::controller(UserWiseFormController::class)
        ->prefix('userwise-form')
        ->group(
            function () {
                Route::get('/', 'index')->name('userwise-form.index');
                Route::get('/create', 'create')->name('userwise-form.create');
                Route::post('/store', 'store')->name('userwise-form.store');
                Route::get('/{userWiseForm}', 'show')->name('userwise-form.show');
                Route::get('/{userWiseForm}/edit', 'edit')->name('userwise-form.edit');
                Route::put('/{userWiseForm}', 'update')->name('userwise-form.update');
                Route::delete('/{userWiseForm}', 'destroy')->name('userwise-form.destroy');
            }
        );
});
