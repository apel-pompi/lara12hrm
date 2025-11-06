<?php

use App\Http\Controllers\Accounts\{
    AccountsController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth','isBanned','UserActivity'])->group(function () {
    //Accounts Route
    Route::controller(AccountsController::class)
        ->prefix('accounts')
        ->group(
            function () {
                Route::get('/', 'index')->name('accounts.index');
                Route::get('/{insid}/createmr/{sid}', 'createMR')->name('accounts.createMR');
                Route::post('/{insnumber}/storeMR/{student}', 'storeMR')->name('accounts.storeMR');
                Route::get('/{confirm}/onView', 'onView')->name('accounts.onView');
                Route::post('/{confirm}/onCancel', 'onCancel')->name('accounts.onCancel');
                Route::post('/{confirm}/onConfirm', 'onConfirm')->name('accounts.onConfirm');
                Route::get('/{onReport}/onReport', 'onReport')->name('accounts.onReport');
            }
        );
    
    
});
