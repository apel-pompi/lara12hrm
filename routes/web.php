<?php


use App\Http\Controllers\Default\{
    DashboardController,
    CountryController,
    StateController,
};

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');


Route::middleware(['verified', 'auth', 'isBanned', 'UserActivity'])->group(function () {
    // Dashboard Route
    Route::controller(DashboardController::class)
        ->prefix('/dashboard')
        ->group(
            function () {
                Route::get('/ArchiveRequest', 'ArchiveRequest')->name('dashboard.ArchiveRequest');
                Route::get('/TransferRequest', 'TransferRequest')->name('dashboard.TransferRequest');
                Route::get('/onBoardRequest', 'onBoardRequest')->name('dashboard.onBoardRequest');
                Route::get('/LeaveRequest', 'LeaveRequest')->name('dashboard.LeaveRequest');
                Route::get('/QuotationRequest', 'QuotationRequest')->name('dashboard.QuotationRequest');
                Route::get('/ReturnRequest', 'ReturnRequest')->name('dashboard.ReturnRequest');
                Route::get('/Calender', 'Calender')->name('dashboard.Calender');
            }
        );

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/users/list', [CountryController::class, 'userlist'])->name('users.list');
    Route::get('/countries/{country}/states', [CountryController::class, 'states']);
    Route::get('/states/{state}/cities', [StateController::class, 'cities']);
});



require __DIR__ . '/socialmedia.php';
require __DIR__ . '/notifications.php';
require __DIR__ . '/users.php';
require __DIR__ . '/hrm.php';
require __DIR__ . '/partner.php';
require __DIR__ . '/student.php';
require __DIR__ . '/product.php';
require __DIR__ . '/default.php';
require __DIR__ . '/agencysetting.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/accounts.php';
require __DIR__ . '/auth.php';
