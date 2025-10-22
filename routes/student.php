<?php

use App\Http\Controllers\Student\{
    StudentStageController,
    StudentSourceController,
    StudentController,
    StudentActivitiesController,
    StudentApplicationController,
    StudentInServiceController,
    StudentDocument,
    StudentAppointements,
    StudentNotes,
    StudentQuotations,
    StudentAccounts,
    StudentConversations,
    StudentTasks,
    StudentEducations,
    StudentCheckin
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth'])->group(function () {

    // Student Stage
    Route::controller(StudentStageController::class)
        ->prefix('studentStage')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentStage.index');
                Route::get('/create', 'create')->name('studentStage.create');
                Route::post('/store', 'store')->name('studentStage.store');
                Route::put('/{studentStage}/status', 'updateStatus')->name('studentStage.updateStatus');
                Route::get('/{studentStage}/edit', 'edit')->name('studentStage.edit');
                Route::put('/{studentStage}', 'update')->name('studentStage.update');
                Route::delete('/show/{studentStage}', 'destroy')->name('studentStage.destroy');
            }
        );

    // Student Source
    Route::controller(StudentSourceController::class)
        ->prefix('studentSource')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentSource.index');
                Route::get('/create', 'create')->name('studentSource.create');
                Route::post('/store', 'store')->name('studentSource.store');
                Route::put('/{studentSource}/status', 'updateStatus')->name('studentSource.updateStatus');
                Route::get('/{studentSource}/edit', 'edit')->name('studentSource.edit');
                Route::put('/{studentSource}', 'update')->name('studentSource.update');
                Route::delete('/show/{studentSource}', 'destroy')->name('studentSource.destroy');
            }
        );

    //student Route
    Route::controller(StudentController::class)
        ->prefix('student')
        ->group(
            function () {
                Route::get('/', 'index')->name('student.index');
                Route::get('/create', 'create')->name('student.create');
                Route::post('/store', 'store')->name('student.store');
                Route::put('/{student}/status', 'updateStatus')->name('student.updateStatus');
                Route::put('/{student}', 'update')->name('student.update');
                Route::delete('/show/{student}', 'destroy')->name('student.destroy');
            }
        );

    // Student Activities
    Route::controller(StudentActivitiesController::class)
        ->prefix('student/activities/{student}/allactivities')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentActivities.index');
                Route::put('/status/archive', 'updateArchive')->name('studentActivities.updateArchive');
                Route::put('/status', 'updateRate')->name('studentActivities.updateRate');
                Route::post('/assignee', 'updateAssignee')->name('studentActivities.updateAssignee');
            }
        );
    
    
    // Student Application
    Route::controller(StudentApplicationController::class)
        ->prefix('student/activities/{student}/application')
        ->group(function () {
            Route::get('/', 'index')->name('studentApplication.index');
            Route::get('/{partner}/partner', 'partner')->name('studentApplication.partner');
            Route::get('/{product}/{partner}/product', 'product')->name('studentApplication.product');
            Route::post('/store', 'store')->name('studentApplication.store');
            Route::get('/{studentApplication}/edit', 'edit')->name('studentApplication.edit');
            Route::put('/{studentApplication}', 'update')->name('studentApplication.update');
            Route::delete('/show/{studentApplication}', 'destroy')->name('studentApplication.destroy');
            Route::get('{studentApplication}/activities', 'appActivities')->name('studentApplication.appActivities');

            // Document routes
            Route::get('{studentApplication}/document', 'documentApplication')->name('studentApplication.documentApplication');
            Route::post('{studentApplication}/document/next', 'documentNextStep')->name('studentApplication.documentNextStep');
            Route::post('{studentApplication}/document/back', 'documentBackStep')->name('studentApplication.documentBackStep');
            Route::post('{studentApplication}/document/checklist', 'updateCheckList')->name('studentApplication.updateCheckList');
            Route::post('{studentApplication}/document/store', 'docAppStore')->name('studentApplication.docAppStore');
            Route::get('{studentApplication}/document/{document}/download', 'downloadAppDocument')->name('studentApplication.docAppDownload');
            Route::delete('{studentApplication}/document/{document}', 'deleteAppDocument')->name('studentApplication.docAppDelete');

            Route::get('{studentApplication}/notes', 'notesApplication')->name('studentApplication.notesApplication');
            Route::get('{studentApplication}/tasks', 'tasksApplication')->name('studentApplication.tasksApplication');
            Route::get('{studentApplication}/payment', 'paymentApplication')->name('studentApplication.paymentApplication');
        });


    // Student Interest Service
    Route::controller(StudentInServiceController::class)
        ->prefix('student/activities/{student}/interestedservice')
        ->group(function () {
            Route::get('/', 'index')->name('studentInService.index');
            Route::get('/{partner}/partner', 'partner')->name('studentInService.partner');
            Route::get('/{product}/{partner}/product', 'product')->name('studentInService.product');
            Route::post('/store', 'store')->name('studentInService.store');
            Route::post('/create', 'create')->name('studentInService.create');
            Route::get('/{studentInService}/edit', 'edit')->name('studentInService.edit');
            Route::put('/{studentInService}', 'update')->name('studentInService.update');
            Route::delete('/show/{studentInService}', 'destroy')->name('studentInService.destroy');
            Route::get('{studentInService}/edit', 'editApplication')->name('studentInService.editApplication');
        });
    
    // Student Documents
    Route::controller(StudentDocument::class)
        ->prefix('student/activities/{student}/document')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentDocument.index');
            }
        );
    // Student Appoinment
    Route::controller(StudentAppointements::class)
        ->prefix('student/activities/{student}/appoinments')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentAppointements.index');
            }
        );
    // Student Notes
    Route::controller(StudentNotes::class)
        ->prefix('student/activities/{student}/notes')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentNotes.index');
            }
        );
    // Student Quotations
    Route::controller(StudentQuotations::class)
        ->prefix('student/activities/{student}/quotations')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentQuotations.index');
                Route::post('/general', 'generalStore')->name('studentQuotations.generalStore');
                Route::put('/{confirm}/generalconfirm', 'confirmGeneral')->name('studentQuotations.confirmGeneral');
                Route::post('/{confirm}/generalDelete', 'generalDelete')->name('studentQuotations.generalDelete');
                Route::get('/{quoatation}/exportPdfGeneral', 'exportPdfGeneral')->name('studentQuotations.exportPdfGeneral');
            }
        );
    // Student Accounts
    Route::controller(StudentAccounts::class)
        ->prefix('student/activities/{student}/accounts')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentAccounts.index');
                Route::get('/{accounts}/create', 'create')->name('studentAccounts.create');
                Route::post('/{accounts}/store', 'store')->name('studentAccounts.store');
                Route::post('/{confirm}/onDelete', 'onDelete')->name('studentAccounts.onDelete');
                Route::post('/{confirm}/onConfirm', 'onConfirm')->name('studentAccounts.onConfirm');
                Route::get('/{confirm}/onReport', 'onReport')->name('studentAccounts.onReport');
            }
        );
    // Student Conversitations
    Route::controller(StudentConversations::class)
        ->prefix('student/activities/{student}/conversations')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentConversations.index');
            }
        );
    // Student Tasks
    Route::controller(StudentTasks::class)
        ->prefix('student/activities/{student}/tasks')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentTasks.index');
            }
        );
    // Student Educations
    Route::controller(StudentEducations::class)
        ->prefix('student/activities/{student}/educations')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentEducations.index');
            }
        );
    // Student Check in Log
    Route::controller(StudentCheckin::class)
        ->prefix('student/activities/{student}/checkin')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentCheckin.index');
            }
        );
});
