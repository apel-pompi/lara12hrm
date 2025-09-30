<?php

use App\Http\Controllers\AgencySetting\{
    DriveController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth'])->group(function () {
    //google drive Route
    Route::controller(DriveController::class)
        ->prefix('gdrive')
        ->group(
            function () {
                Route::get('/folders', 'listDriveFolders')->name('drive.folders');
                Route::post('/upload', 'uploadFile')->name('drive.upload');
            }
        );
    
});


