<?php

use App\Http\Controllers\Partner\{
    PartnerBranchController,
    PartnerController,
    PartnerActivities
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth','isBanned','UserActivity'])->group(function () {

    //partner Branch Setting
    Route::controller(PartnerBranchController::class)
        ->prefix('partnerbranch')
        ->group(
            function () {
                Route::get('/', 'index')->name('partnerbranch.index');
                Route::get('/create', 'create')->name('partnerbranch.create');
                Route::post('/store', 'store')->name('partnerbranch.store');
                Route::post('/PartnerBranch', 'PartnerBranch')->name('partnerbranch.partnerBranch');
                Route::put('/{PartnerBranch}/status', 'updateStatus')->name('partnerbranch.updateStatus');
                Route::get('/{PartnerBranch}/edit', 'edit')->name('partnerbranch.edit');
                Route::put('/{PartnerBranch}', 'update')->name('partner.partnerbranch');
                Route::delete('/show/{PartnerBranch}', 'destroy')->name('partnerbranch.destroy');
            }
        );

    //Partner
    Route::controller(PartnerController::class)
        ->prefix('partner')
        ->group(
            function () {
                Route::get('/', 'index')->name('partner.index');
                Route::get('/create', 'create')->name('partner.create');
                Route::post('/store', 'store')->name('partner.store');
                Route::put('/{partner}/status', 'updateStatus')->name('partner.updateStatus');
                Route::get('/{partner}/edit', 'edit')->name('partner.edit');
                Route::put('/{partner}', 'update')->name('partner.update');
                Route::delete('/show/{partner}', 'destroy')->name('partner.destroy');
            }
        );
    
    // Student Activities
    Route::controller(PartnerActivities::class)
        ->prefix('partner/activities/{partner}/')
        ->group(
            function () {
                Route::get('/aplication', 'aplication')->name('PartnerActivities.application');
                Route::get('/product', 'product')->name('PartnerActivities.product');
                Route::get('/branch', 'branch')->name('PartnerActivities.branch');
                
                Route::post('/branch', 'branchStore')->name('PartnerActivities.branchStore');
                Route::delete('/branch/show/{partnerBranch}', 'branchDelete')->name('PartnerActivities.branchDelete');

                Route::get('/aggrements', 'aggrements')->name('PartnerActivities.aggrements');
                Route::get('/contacts', 'contacts')->name('PartnerActivities.contacts');
                Route::get('/notes', 'notes')->name('PartnerActivities.notes');
                Route::get('/documents', 'documents')->name('PartnerActivities.documents');
                Route::get('/appoinments', 'appoinments')->name('PartnerActivities.appoinments');
                Route::get('/accounts', 'accounts')->name('PartnerActivities.accounts');
                Route::get('/conversations', 'conversations')->name('PartnerActivities.conversations');
                Route::get('/tasks', 'tasks')->name('PartnerActivities.tasks');
                Route::get('/others', 'others')->name('PartnerActivities.others');
                Route::get('/promotions', 'promotions')->name('PartnerActivities.promotions');
            }
    );
    
});
