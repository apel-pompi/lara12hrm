<?php


use App\Http\Controllers\{
    BranchController,RoleController
};

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['verified', 'auth'])->group(function () {
    Route::controller(BranchController::class)
                ->prefix('branch')
                ->group(function(){
                    Route::get('/', 'index')->name('branch.index');
                    Route::post('/store', 'store')->name('branch.store');
                    Route::get('/{branch}', 'show')->name('branch.show');
                    Route::delete('/show/{branch}', 'destroy')->name('branch.destroy');
                    Route::get('/{branch}/edit', 'edit')->name('branch.edit');
                    Route::put('/{branch}', 'update')->name('branch.update');
                }
    );

    // Roles routes
    Route::controller(RoleController::class)
        ->prefix('/roles')
        ->group(function () {
            Route::get('/', 'index')->name('roles.index');
            Route::post('/store', 'store')->name('roles.store');
            Route::get('/{roles}', 'show')->name('roles.show');
            Route::delete('/show/{roles}', 'destroy')->name('roles.destroy');
            Route::get('/{roles}/edit', 'edit')->name('roles.edit');
            Route::put('/{roles}', 'update')->name('roles.update');
        }
    );

});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
