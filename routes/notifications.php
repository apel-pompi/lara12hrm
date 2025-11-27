<?php

use App\Http\Controllers\Default\{
    NotificationController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth','isBanned','UserActivity'])->group(function () {
    //Notification Route
   Route::controller(NotificationController::class)
        ->prefix('notifications')
        ->as('notifications.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/unread-count', 'unreadCount')->name('unreadCount');
            Route::post('/{id}/read', 'markAsRead')->name('markAsRead');
            Route::post('/mark-all-read', 'markAllAsRead')->name('markAllAsRead');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::delete('/', 'clearAll')->name('clearAll');
            Route::post('/', 'store')->name('store');
        });
    
    
});
