<?php

use App\Http\Controllers\SocialMedia\{
    InboxController,
    SocialMediaConversationController,
    SocialMediaMessageController
};
use App\Http\Controllers\SocialMedia\FollowUp\{
    FollowUpMasterController,
    FollowUpStatusController,
    FollowUpActivityController,
    FollowUpReminderController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth', 'isBanned', 'UserActivity'])->group(function () {
    Route::controller(InboxController::class)
        ->prefix('metachat')
        ->as('metachat.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });

    Route::controller(SocialMediaConversationController::class)
        ->prefix('conversations')
        ->as('conversations.')
        ->group(function () {
            Route::get('/', 'index')->name('conversations.index');
            Route::get('/{conversation}/messages', 'messages')->name('conversations.messages');
            Route::get('/{conversation}/channels', 'contactChannels')->name('conversations.contactChannels');
        });

    Route::controller(SocialMediaMessageController::class)
        ->prefix('messages')
        ->group(function () {
            Route::post('/send', 'send')->name('messages.send');
        });

    Route::controller(FollowUpMasterController::class)
        ->prefix('follow-up-masters')
        ->as('follow-up-masters.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/active', 'active')->name('active');
            Route::post('/', 'store')->name('store');
            Route::get('/{followUpMaster}', 'show')->name('show');
            Route::put('/{followUpMaster}', 'update')->name('update');
            Route::delete('/{followUpMaster}', 'destroy')->name('destroy');
        });
    Route::controller(FollowUpStatusController::class)
        ->prefix('follow-up-statuses')
        ->as('follow-up-statuses.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/active', 'active')->name('active');
            Route::post('/', 'store')->name('store');
            Route::get('/{followUpStatus}', 'show')->name('show');
            Route::put('/{followUpStatus}', 'update')->name('update');
            Route::delete('/{followUpStatus}', 'destroy')->name('destroy');
        });

    Route::controller(FollowUpActivityController::class)
        ->prefix('follow-up-activities')
        ->as('follow-up-activities.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/student/{studentId}', 'student')->name('student');
            Route::get('/{followUpActivity}', 'show')->name('show');
            Route::delete('/{followUpActivity}', 'destroy')->name('destroy');
        });

    Route::controller(FollowUpReminderController::class)
        ->prefix('follow-up-reminders')
        ->as('follow-up-reminders.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/pending', 'pending')->name('pending');
            Route::get('/today', 'today')->name('today');
            Route::post('/', 'store')->name('store');
            Route::get('/{followUpReminder}', 'show')->name('show');
            Route::put('/{followUpReminder}', 'update')->name('update');
            Route::put('/{followUpReminder}/complete', 'complete')->name('complete');
            Route::put('/{followUpReminder}/snooze', 'snooze')->name('snooze');
            Route::put('/{followUpReminder}/cancel', 'cancel')->name('cancel');
            Route::delete('/{followUpReminder}', 'destroy')->name('destroy');
        });
});
