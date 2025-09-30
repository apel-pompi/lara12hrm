<?php

use App\Http\Controllers\Product\{
    ProductController,
    ProductActivities
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth'])->group(function () {

    //product
    Route::controller(ProductController::class)
        ->prefix('product')
        ->group(
            function () {
                Route::get('/', 'index')->name('product.index');
                Route::get('/create', 'create')->name('product.create');
                Route::post('/store', 'store')->name('product.store');
                Route::put('/{product}/status', 'updateStatus')->name('product.updateStatus');
                Route::get('/{product}/edit', 'edit')->name('product.edit');
                Route::put('/{product}', 'update')->name('product.update');
                Route::delete('/show/{product}', 'destroy')->name('product.destroy');
            }
        );


    // Product Activities
    Route::controller(ProductActivities::class)
        ->prefix('product/activities/{product}/')
        ->group(
            function () {
                Route::get('/aplication', 'aplication')->name('productActivities.application');
                Route::get('/documents', 'documents')->name('productActivities.documents');

                Route::get('/fees', 'fees')->name('productActivities.fees');
                Route::post('/fees', 'storefess')->name('productActivities.storefess');

                Route::get('/requirement', 'requirement')->name('productActivities.requirement');

                Route::get('/requirement/{requirement}/edit', 'editRequirement')->name('productActivities.editRequirement');
                Route::post('/requirement', 'storeRequirement')->name('productActivities.storeRequirement');

                Route::get('/requirementEng/{requirementEng}/edit', 'editRequirementEng')->name('productActivities.editRequirementEng');
                Route::post('/requirementEng', 'storeRequirementEng')->name('productActivities.storeRequirementEng');

                Route::get('/requirementOthers/{requirementOthers}/edit', 'editRequirementOthers')->name('productActivities.editRequirementOthers');
                Route::post('/requirementOthers', 'storeRequirementOthers')->name('productActivities.storeRequirementOthers');
                
                Route::get('/others', 'others')->name('productActivities.others');
                Route::get('/promotions', 'promotions')->name('productActivities.promotions');
            }
    );

});
