<?php

use App\Http\Controllers\HRM\{
    CompanyInfoController,
    BranchController,
    DepartmentController,
    DesignationController,
    LeaveplanController,
    AttenSettingController,
    HolidayHdController,
    HolidayDtController,
    PersonalInfoController,
    LeaveController,
    HRreportsController

};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth','isBanned','UserActivity'])->group(function () {
    //Company Inforamation Route
    Route::controller(CompanyInfoController::class)
        ->prefix('/companyinfo')
        ->group(
            function () {
                Route::get('/', 'edit')->name('company.index');
                Route::put('/{companyInfo}', 'update')->name('company.update');
            }
        );

    //Branch Route
    Route::controller(BranchController::class)
        ->prefix('branch')
        ->group(
            function () {
                Route::get('/', 'index')->name('branch.index');
                Route::post('/store', 'store')->name('branch.store');
                Route::get('/{branch}', 'show')->name('branch.show');
                Route::delete('/show/{branch}', 'destroy')->name('branch.destroy');
                Route::get('/{branch}/edit', 'edit')->name('branch.edit');
                Route::put('/{branch}', 'update')->name('branch.update');
            }
        );
   
    //Department Route
    Route::controller(DepartmentController::class)
        ->prefix('department')
        ->group(
            function () {
                Route::get('/', 'index')->name('department.index');
                Route::post('/store', 'store')->name('department.store');
                Route::get('/{department}', 'show')->name('department.show');
                Route::delete('/show/{department}', 'destroy')->name('department.destroy');
                Route::get('/{department}/edit', 'edit')->name('department.edit');
                Route::put('/{department}', 'update')->name('department.update');
            }
        );

    //Designation Route
    Route::controller(DesignationController::class)
        ->prefix('designation')
        ->group(
            function () {
                Route::get('/', 'index')->name('designation.index');
                Route::post('/store', 'store')->name('designation.store');
                Route::get('/{designation}', 'show')->name('designation.show');
                Route::delete('/show/{designation}', 'destroy')->name('designation.destroy');
                Route::get('/{designation}/edit', 'edit')->name('designation.edit');
                Route::put('/{designation}', 'update')->name('designation.update');
            }
        );

    //Leave Plan Route
    Route::controller(LeaveplanController::class)
        ->prefix('leaveplan')
        ->group(
            function () {
                Route::get('/', 'index')->name('leaveplan.index');
                Route::post('/store', 'store')->name('leaveplan.store');
                Route::get('/{leaveplan}', 'show')->name('leaveplan.show');
                Route::delete('/show/{leaveplan}', 'destroy')->name('leaveplan.destroy');
                Route::get('/{leaveplan}/edit', 'edit')->name('leaveplan.edit');
                Route::put('/{leaveplan}', 'update')->name('leaveplan.update');
            }
        );

    //Attendance Setting Route
    Route::controller(AttenSettingController::class)
        ->prefix('attensetting')
        ->group(
            function () {
                Route::get('/', 'index')->name('attensetting.index');
                Route::post('/store', 'store')->name('attensetting.store');
                Route::get('/{attensetting}', 'show')->name('attensetting.show');
                Route::delete('/show/{attensetting}', 'destroy')->name('attensetting.destroy');
                Route::get('/{attensetting}/edit', 'edit')->name('attensetting.edit');
                Route::put('/{attensetting}', 'update')->name('attensetting.update');
            }
        );

    //Holiday Header Route
    Route::controller(HolidayHdController::class)
        ->prefix('holidayHd')
        ->group(
            function () {
                Route::get('/', 'index')->name('holidayHd.index');
                Route::post('/store', 'store')->name('holidayHd.store');
                Route::put('/{holidayhd}/status', 'updateStatus')->name('holidayhd.updateStatus');
                Route::get('/{holidayHd}', 'show')->name('holidayHd.show');
                Route::delete('/show/{holidayHd}', 'destroy')->name('holidayHd.destroy');
                Route::get('/{holidayHd}/edit', 'edit')->name('holidayHd.edit');
                Route::put('/{holidayHd}', 'update')->name('holidayHd.update');
            }
        );

    //Holiday Details
    Route::controller(HolidayDtController::class)
        ->prefix('/holidaydt')
        ->group(
            function () {
                Route::get('/{id}/create', 'create')->name('holidaydt.create');
                Route::post('/create', 'store')->name('holidaydt.store');
                Route::get('/{holidayDt}/edit', 'edit')->name('holidaydt.edit');
                Route::put('/{holidayDt}/edit', 'update')->name('holidaydt.update');
                Route::delete('/destroy/{holidayDt}', 'destroy')->name('holidaydt.destroy');
            }
        );

    //Personal Information Route
    Route::controller(PersonalInfoController::class)
        ->prefix('personalinfo')
        ->group(
            function () {
                Route::get('/', 'index')->name('personalinfo.index');
                Route::post('/store', 'store')->name('personalinfo.store');
                Route::put('/{PersonalInfo}/status', 'updateStatus')->name('personalinfo.updateStatus');
                Route::get('/{PersonalInfo}', 'show')->name('personalinfo.show');
                Route::delete('/show/{PersonalInfo}', 'destroy')->name('personalinfo.destroy');
                Route::get('/{PersonalInfo}/edit', 'edit')->name('personalinfo.edit');
                Route::put('/{PersonalInfo}', 'update')->name('personalinfo.update');
            }
        );

    //Leave Route
    Route::controller(LeaveController::class)
        ->prefix('leave')
        ->group(
            function () {
                Route::get('/', 'index')->name('leave.index');
                Route::post('/store', 'store')->name('leave.store');
                Route::get('/{leave}', 'show')->name('leave.show');
                Route::get('/{leave}', 'exportPdf')->name('leave.exportPdf');
                Route::delete('/show/{leave}', 'destroy')->name('leave.destroy');
                Route::get('/{leave}/edit', 'edit')->name('leave.edit');
                Route::put('/{leave}', 'update')->name('leave.update');
            }
        );
    
    //Reports
    Route::controller(HRreportsController::class)
        ->prefix('hrreports')
        ->group(
            function () {
                Route::get('/', 'index')->name('hrreports.index');
                Route::get('/empreport', 'EmpInfoReport')->name('hrreports.EmpInfoReport');
                Route::get('/DailyAttendance', 'DailyAttendance')->name('hrreports.DailyAttendance');
                Route::get('/DailyAttendanceReport', 'DailyAttendanceReport')->name('hrreports.DailyAttendanceReport');
                Route::get('/EmployeeAttendance', 'EmployeeAttendance')->name('hrreports.EmployeeAttendance');
                Route::get('/EmployeeAttendanceReport', 'EmployeeAttendanceReport')->name('hrreports.EmployeeAttendanceReport');
            }
        );
});


