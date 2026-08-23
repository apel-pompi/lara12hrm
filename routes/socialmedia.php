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
    FollowUpReminderController,
    FollowUpTimelineController,
    FollowUpNotificationController
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
            //Route::get('/admindashboard', 'admindashboard')->name('admindashboard');
            Route::get('/{activity}', 'show')->name('show');
            Route::get('/{activity}/timeline', 'timeline')->name('timeline');
            Route::delete('/{followUpActivity}', 'destroy')->name('destroy');
        });

    Route::controller(FollowUpReminderController::class)
        ->prefix('follow-up-reminders')
        ->as('follow-up-reminders.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/pending', 'pending')->name('pending');
            Route::get('/today', 'today')->name('today');
            Route::get('/due', 'due')->name('due');
            Route::get('/overdue', 'overdue')->name('overdue');
            Route::get('/upcoming', 'upcoming')->name('upcoming');
            Route::post('/{reminder}/mark-as-sent', 'markAsSent')->name('markAsSent');
            Route::post('/{reminder}/mark-as-read', 'markAsRead')->name('markAsRead');
            Route::post('/{reminder}/snooze', 'snooze')->name('snooze');
            Route::delete('/{reminder}', 'destroy')->name('destroy');
            Route::post('/scheduler/run', 'runScheduler')->name('scheduler.run');
        });
    //FollowUp Timeline Route
    Route::controller(FollowUpTimelineController::class)
        ->prefix('follow-up-timeline')
        ->as('follow-up-timeline.')
        ->group(function () {
            Route::get('/{followUpActivity}/timeline', 'timeline')->name('timeline');
            Route::get('/student/{student}', 'student')->name('student');
        });
    //FollowUp notification list
    Route::controller(FollowUpNotificationController::class)
        ->prefix('follow-up-notifications')
        ->as('follow-up-notifications.')
        ->group(function () {
            Route::get('/user/{userId}', 'index')->name('index');
            Route::get('/all/{userId}', 'all')->name('all');
            Route::get('/admindashboard', 'admindashboard')->name('admindashboard');
            Route::get('/user/{userId}/unread-count', 'unreadCount')->name('unreadCount');
            Route::get('/user/{userId}/dashboard', 'dashboard')->name('dashboard');
            Route::post('/{notification}/read/{userId}', 'markAsRead')->name('markAsRead');
            Route::post('/user/{userId}/read-all', 'markAllAsRead')->name('markAllAsRead');
        });
});
