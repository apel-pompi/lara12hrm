<?php

use App\Http\Controllers\SocialMedia\{
    InboxController,
    SocialMediaConversationController,
    SocialMediaMessageController
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
});
