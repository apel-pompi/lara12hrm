<?php

use App\Http\Controllers\Accounts\{
    AccountsSetup,
    GroupOneController,
    GroupTwoController,
    GroupThreeController,
    ChartOfAccountController,
    AccountsController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth','isBanned','UserActivity'])->group(function () {
    //Accounts Setting
    Route::controller(AccountsSetup::class)
        ->prefix('accountssetting')
        ->group(
            function () {
                // Group One index Route
                Route::get('/', 'index')->name('accsetting.GroupOne');
                // Group Tow index Route
                Route::get('/{GroupOne}/Grouptwo', 'Grouptwo')->name('accsetting.GroupTwo');
                // Group Three index Route
                Route::get('/{GroupOne}/{GroupTwo}/Groupthree', 'Groupthree')->name('accsetting.GroupThree');
            }
        );
    
    //Group One Route
    Route::controller(GroupOneController::class)
        ->prefix('groupOne')
        ->group(
            function () {
                Route::post('/store', 'store')->name('GroupOne.store');

                Route::get('/{groupOne}/edit', 'edit')->name('GroupOne.edit');
                Route::put('/{groupOne}', 'update')->name('GroupOne.update');
                Route::put('/{groupOne}/status', 'updateStatus')->name('GroupOne.updateStatus');

                Route::get('/{groupOne}', 'show')->name('GroupOne.show');
                Route::delete('/show/{groupOne}', 'destroy')->name('GroupOne.destroy');
            }
        );
    
    //Group Two Route
    Route::controller(GroupTwoController::class)
        ->prefix('Grouptwo')
        ->group(
            function () {
                Route::post('/store', 'store')->name('GroupTwo.store');
                
                Route::get('/{groupTwo}/edit', 'edit')->name('GroupTwo.edit');
                Route::put('/{groupTwo}', 'update')->name('GroupTwo.update');
                Route::put('/{groupTwo}/status', 'updateStatus')->name('GroupTwo.updateStatus');

                Route::get('/{groupTwo}', 'show')->name('GroupTwo.show');
                Route::delete('/show/{groupTwo}', 'destroy')->name('GroupTwo.destroy');
            }
        );
    
    //Group Three Route
    Route::controller(GroupThreeController::class)
        ->prefix('Groupthree')
        ->group(
            function () {
                Route::post('/store', 'store')->name('GroupThree.store');
                
                Route::get('/{groupThree}/edit', 'edit')->name('GroupThree.edit');
                Route::put('/{groupThree}', 'update')->name('GroupThree.update');
                Route::put('/{groupThree}/status', 'updateStatus')->name('GroupThree.updateStatus');

                Route::get('/{groupThree}', 'show')->name('GroupThree.show');
                Route::delete('/show/{groupThree}', 'destroy')->name('GroupThree.destroy');
            }
        );
    //chart Of Account Route
    Route::controller(ChartOfAccountController::class)
        ->prefix('chartOfAccount')
        ->group(
            function () {

                Route::get('/', 'index')->name('chartOfAccount.index');
                Route::get('/getGroupTwo/{GroupOne}', 'getGroupTwo')->name('chartOfAccount.getGroupTwo');
                Route::get('/getGroupThree/{GroupOne}/{GroupTwo}', 'getGroupThree')->name('chartOfAccount.getGroupThree');
                Route::get('/generateAccountCode/{groupthree}', 'generateCode')->name('chartOfAccount.generateCode');

                Route::post('/store', 'store')->name('chartOfAccount.store');
                               
                Route::get('/{chartOfAccount}/edit', 'edit')->name('chartOfAccount.edit');
                Route::put('/{chartOfAccount}', 'update')->name('chartOfAccount.update');
                Route::put('/{chartOfAccount}/status', 'updateStatus')->name('chartOfAccount.updateStatus');

                Route::get('/{chartOfAccount}', 'show')->name('chartOfAccount.show');
                Route::delete('/show/{chartOfAccount}', 'destroy')->name('chartOfAccount.destroy');
            }
        );
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
