<?php


use App\Http\Controllers\Default\{
    CountryController,
    StateController,
};

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');


Route::middleware(['verified', 'auth'])->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/countries/{country}/states', [CountryController::class, 'states']);
    Route::get('/states/{state}/cities', [StateController::class, 'cities']);

});



require __DIR__ . '/users.php';
require __DIR__ . '/hrm.php';
require __DIR__ . '/partner.php';
require __DIR__ . '/student.php';
require __DIR__ . '/product.php';
require __DIR__ . '/default.php';
require __DIR__ . '/agencysetting.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
