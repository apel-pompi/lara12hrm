<?php

use App\Http\Controllers\Users\{
    RoleController,
    UserPermissionController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth'])->group(function () {
    //User Permission Route
    Route::controller(UserPermissionController::class)
        ->prefix('/userpermission')
        ->group(
            function () {
                Route::get('/', 'index')->name('userpermission.index');
                Route::post('/store', 'store')->name('userpermission.store');
                Route::delete('/show/{id}', 'destroy')->name('userpermission.destroy');
                Route::get('/{id}/edit', 'edit')->name('userpermission.edit');
                Route::put('/{id}', 'update')->name('userpermission.update');
            }
        );

     // Roles routes
    Route::controller(RoleController::class)
        ->prefix('/roles')
        ->group(
            function () {
                Route::get('/', 'index')->name('roles.index');
                Route::post('/store', 'store')->name('roles.store');
                Route::get('/{roles}', 'show')->name('roles.show');
                Route::delete('/show/{roles}', 'destroy')->name('roles.destroy');
                Route::get('/{roles}/edit', 'edit')->name('roles.edit');
                Route::put('/{roles}', 'update')->name('roles.update');
            }
        );

});
