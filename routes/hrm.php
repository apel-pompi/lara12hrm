<?php

use App\Http\Controllers\HRM\{
    CompanyInfoController,
    ZktecoController,
    BranchController,
    DepartmentController,
    DesignationController,
    LeaveplanController,
    WorkHourSetupController,
    AttenSettingController,
    AttenDeductController,
    SalaryTypeController,
    HolidayHdController,
    HolidayDtController,
    PersonalInfoController,
    LeaveController,
    AttendanceStatusController,
    HRreportsController
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth', 'isBanned', 'UserActivity'])->group(function () {
    //Company Inforamation Route
    Route::controller(CompanyInfoController::class)
        ->prefix('/companyinfo')
        ->group(
            function () {
                Route::get('/', 'edit')->name('company.index');
                Route::put('/{companyInfo}', 'update')->name('company.update');
            }
        );
    //Zkteco Device Sync Route
    Route::controller(ZktecoController::class)
        ->prefix('/zkteco')
        ->group(
            function () {
                Route::get('/', 'index')->name('zkteco.index');
                Route::post('/connect', 'connect')->name('zkteco.connect');
                Route::post('/sync', 'sync')->name('zkteco.sync');
                Route::post('/sync-range', 'syncRange')->name('zkteco.sync-range');
                Route::get('/status', 'checkStatus')->name('zkteco.checkStatus');
                Route::get('/stats', 'getStats')->name('zkteco.getStats');
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

    //Working Hours Route
    Route::controller(WorkHourSetupController::class)
        ->prefix('workhour')
        ->group(
            function () {
                Route::get('/', 'index')->name('workhour.index');
                Route::post('/store', 'store')->name('workhour.store');
                Route::get('/{workHourSetup}', 'show')->name('workhour.show');
                Route::delete('/show/{workHourSetup}', 'destroy')->name('workhour.destroy');
                Route::get('/{workHourSetup}/edit', 'edit')->name('workhour.edit');
                Route::put('/{workHourSetup}', 'update')->name('workhour.update');
                Route::put('/{workHourSetup}/status', 'updateStatus')->name('workhour.updateStatus');
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

    //Attendance Setting Route
    Route::controller(AttenDeductController::class)
        ->prefix('attendeduct')
        ->group(
            function () {
                Route::get('/', 'index')->name('attendeduct.index');
                Route::post('/store', 'store')->name('attendeduct.store');
                Route::get('/{attendeduct}', 'show')->name('attendeduct.show');
                Route::delete('/show/{attendeduct}', 'destroy')->name('attendeduct.destroy');
                Route::get('/{attendeduct}/edit', 'edit')->name('attendeduct.edit');
                Route::put('/{attendeduct}', 'update')->name('attendeduct.update');
            }
        );
    //Attendance Setting Route
    Route::controller(AttendanceStatusController::class)
        ->prefix('attendanceStatus')
        ->group(
            function () {

                Route::get('/', 'index')->name('attendanceStatus.index');
                Route::get('/create', 'create')->name('attendanceStatus.create');

                Route::post('/store', 'store')->name('attendanceStatus.store');

                Route::get('/{attendanceStatus}/edit', 'edit')->name('attendanceStatus.edit');
                Route::put('/{attendanceStatus}', 'update')->name('attendanceStatus.update');
                Route::put('/{attendanceStatus}/status', 'updateStatus')->name('attendanceStatus.updateStatus');

                Route::get('/{attendanceStatus}', 'show')->name('attendanceStatus.show');
                Route::delete('/show/{attendanceStatus}', 'destroy')->name('attendanceStatus.destroy');
            }
        );
    //Salary Type Setup
    Route::controller(SalaryTypeController::class)
        ->prefix('salarytype')
        ->group(
            function () {
                Route::get('/', 'index')->name('salarytype.index');
                Route::post('/store', 'store')->name('salarytype.store');
                Route::get('/{salaryType}', 'show')->name('salarytype.show');
                Route::delete('/show/{salaryType}', 'destroy')->name('salarytype.destroy');
                Route::get('/{salaryType}/edit', 'edit')->name('salarytype.edit');
                Route::put('/{salaryType}', 'update')->name('salarytype.update');
                Route::put('/{salaryType}/status', 'updateStatus')->name('salarytype.updateStatus');
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
                Route::get('/show/{leave}', 'show')->name('leave.show');
                Route::get('/{leave}', 'exportPdf')->name('leave.exportPdf');
                Route::delete('/show/{leave}', 'destroy')->name('leave.destroy');
                Route::get('/{leave}/edit', 'edit')->name('leave.edit');
                Route::put('/{leave}', 'update')->name('leave.update');
                Route::post('/confirm/{leave}', 'confirm')->name('leave.confirm');
                Route::get('/{leave}/{empid}/fetchUserLeave', 'fetchUserLeave')->name('leave.fetchUserLeave');
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
                Route::get('/MonthlyAttendance', 'MonthlyAttendance')->name('hrreports.MonthlyAttendance');
                Route::get('/MonthlyAttendanceReport', 'MonthlyAttendanceReport')->name('hrreports.MonthlyAttendanceReport');
            }
        );
});
