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
    StudentCheckLogController,
    StudentReportController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth', 'isBanned', 'UserActivity'])->group(function () {

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
                Route::get('/lead', 'lead')->name('student.lead');
                Route::get('/pending', 'pending')->name('student.pending');
                Route::get('/prospect', 'prospect')->name('student.prospect');
                Route::get('/onBoard', 'onBoard')->name('student.onBoard');
                Route::get('/archive', 'archive')->name('student.archive');
                Route::get('/create', 'create')->name('student.create');
                Route::post('/store', 'store')->name('student.store');
                Route::put('/{student}/status', 'updateStatus')->name('student.updateStatus');
                Route::get('/{student}/edit', 'edit')->name('student.edit');
                Route::put('/{student}/update', 'update')->name('student.update');
                Route::delete('/show/{student}', 'destroy')->name('student.destroy');

                Route::get('/search', 'Search')->name('student.search');
                Route::get('/SearchPending', 'SearchPending')->name('student.SearchPending');
                Route::get('/SearchLead', 'SearchLead')->name('student.SearchLead');
                Route::get('/SearchProspect', 'SearchProspect')->name('student.SearchProspect');
                Route::get('/SearchOnBoard', 'SearchOnBoard')->name('student.SearchOnBoard');
                Route::get('/SearchArchive', 'SearchArchive')->name('student.SearchArchive');
                Route::get('/SearchInactive', 'SearchInactive')->name('student.SearchInactive');

                // Inactive Lead routes
                Route::get('/inactive/1month', 'inactiveLeads1Month')->name('student.inactive1month');
                Route::get('/inactive/3month', 'inactiveLeads3Month')->name('student.inactive3month');
                Route::get('/inactive/6month', 'inactiveLeads6Month')->name('student.inactive6month');
                Route::post('/inactive/transfer', 'transferInactiveLeads')->name('student.transferInactiveLeads');
                Route::get('/inactive/transfer-logs', 'transferInactiveLeadLogs')->name('student.transferInactiveLeadLogs');
            }
        );

    // Student Activities
    Route::controller(StudentActivitiesController::class)
        ->prefix('student/activities/{student}/allactivities')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentActivities.index');
                Route::put('/status/archive', 'updateArchive')->name('studentActivities.updateArchive');
                Route::put('/status/confirmTransfer', 'confirmTransfer')->name('studentActivities.confirmTransfer');
                Route::put('/status/confirmonBoard', 'confirmonBoard')->name('studentActivities.confirmonBoard');
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
            Route::get('{studentInService}/editApplication', 'editApplication')->name('studentInService.editApplication');
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
                Route::post('/store', 'store')->name('studentAppointements.store');
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
                Route::get('/{product}', 'fetchData')->name('studentQuotations.fetchData');
                Route::post('/general', 'store')->name('studentQuotations.store');
                Route::put('/{product}/confirm', 'confirm')->name('studentQuotations.confirm');
                Route::post('/{product}/destory', 'destory')->name('studentQuotations.destory');
                Route::get('/{product}/{quoatation}/exportPdfGeneral', 'exportPdfGeneral')->name('studentQuotations.exportPdfGeneral');
                Route::get('/{product}/{quoatation}/exportPdfApproved', 'exportPdfApproved')->name('studentQuotations.exportPdfApproved');
            }
        );
    // Student Accounts
    Route::controller(StudentAccounts::class)
        ->prefix('student/activities/{student}/accounts')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentAccounts.index');
                Route::get('/return', 'return')->name('studentAccounts.return');

                Route::get('/{quotation}/create', 'create')->name('studentAccounts.create');

                Route::get('/{mrid}/fetchMR', 'fetchMR')->name('studentAccounts.fetchMR');
                Route::post('/storeReturn', 'storeReturn')->name('studentAccounts.storeReturn');
                Route::get('/{srid}/fetchSR', 'fetchSR')->name('studentAccounts.fetchSR');
                Route::post('/{confirm}/returnCancel', 'returnCancel')->name('studentAccounts.returnCancel');
                Route::post('/{confirm}/returnConfirm', 'returnConfirm')->name('studentAccounts.returnConfirm');

                Route::post('/{quotation}/store', 'store')->name('studentAccounts.store');
                Route::get('/{confirm}/onView', 'onView')->name('studentAccounts.onView');
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
                Route::post('/store', 'store')->name('studentConversations.store');
                Route::get('/{conversation}/fetchData', 'fetchData')->name('studentConversations.fetchData');
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
    Route::controller(StudentCheckLogController::class)
        ->prefix('student/activities/{student}/checkin')
        ->group(
            function () {
                Route::get('/', 'index')->name('studentCheckin.index');
                Route::post('/store', 'store')->name('studentCheckin.store');
                Route::post('/checkOut', 'checkOut')->name('studentCheckin.checkOut');
            }
        );
    // lead reports
    Route::controller(StudentReportController::class)
        ->prefix('leadreports')
        ->group(
            function () {
                Route::get('/', 'index')->name('leadreports.index');

                Route::get('/transaction', 'studentTransaction')->name('leadreports.studentTransaction');
                Route::get('/transaction/{student}', 'studentTransactionReport')->name('leadreports.studentTransactionReport');

                Route::get('/ledger', 'studentLedger')->name('leadreports.studentLedger');
                Route::get('/ledger/{student}', 'studentLedgerReport')->name('leadreports.studentLedgerReport');

                Route::get('/revenue', 'studentRevenue')->name('leadreports.studentRevenue');
                Route::get('/revenue/{formdate}/{todate}/{isAdmin}/{employee?}', 'studentRevenueReport')->name('leadreports.studentRevenueReport');

                Route::get('/refund', 'studentRefund')->name('leadreports.studentRefund');
                Route::get('/refund/{formdate}/{todate}/{isAdmin}/{employee?}', 'studentRefundReport')->name('leadreports.studentRefundReport');

                Route::get('/emp/{formdate}/{todate}/{isAdmin}/{employee?}', 'MonthlyEmpLeadReport')->name('leadreports.MonthlyEmpLeadReport');
            }
        );
});
