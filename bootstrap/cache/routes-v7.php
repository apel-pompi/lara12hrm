<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sanctum.csrf-cookie',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6VAQc8yMFZGcRuPE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/attendances' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'device.syncData',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/attendances/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hOWPzKSZzhEv5ufH',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Zsz0AvE5gAwpY3J0',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/ArchiveRequest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.ArchiveRequest',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/TransferRequest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.TransferRequest',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/onBoardRequest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.onBoardRequest',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/LeaveRequest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.LeaveRequest',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/QuotationRequest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.QuotationRequest',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/ReturnRequest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.ReturnRequest',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/Calender' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.Calender',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/users/list' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.list',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/userpermission' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userpermission.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/userpermission/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userpermission.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/roles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/roles/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/companyinfo' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'company.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/branch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'branch.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/branch/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'branch.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/department' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'department.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/department/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'department.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/designation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'designation.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/designation/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'designation.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/leaveplan' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leaveplan.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/leaveplan/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leaveplan.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/workhour' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workhour.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/workhour/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workhour.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/attensetting' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attensetting.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/attensetting/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attensetting.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/attendeduct' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendeduct.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/attendeduct/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendeduct.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/attendanceStatus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/attendanceStatus/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/attendanceStatus/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/salarytype' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'salarytype.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/salarytype/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'salarytype.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/holidayHd' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidayHd.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/holidayHd/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidayHd.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/holidaydt/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidaydt.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/personalinfo' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'personalinfo.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/personalinfo/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'personalinfo.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/leave' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/leave/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports/empreport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.EmpInfoReport',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports/DailyAttendance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.DailyAttendance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports/DailyAttendanceReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.DailyAttendanceReport',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports/EmployeeAttendance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.EmployeeAttendance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports/EmployeeAttendanceReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.EmployeeAttendanceReport',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports/MonthlyAttendance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.MonthlyAttendance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/hrreports/MonthlyAttendanceReport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hrreports.MonthlyAttendanceReport',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/partnerbranch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partnerbranch.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/partnerbranch/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partnerbranch.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/partnerbranch/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partnerbranch.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/partnerbranch/PartnerBranch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partnerbranch.partnerBranch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/partner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/partner/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/partner/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studentStage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentStage.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studentStage/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentStage.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studentStage/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentStage.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studentSource' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentSource.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studentSource/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentSource.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studentSource/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentSource.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student/lead' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.lead',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student/pending' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.pending',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student/prospect' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.prospect',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student/onBoard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.onBoard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student/archive' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.archive',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/student/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/leadreports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leadreports.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/leadreports/ledger' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leadreports.studentLedger',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/leadreports/revenue' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leadreports.studentRevenue',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/product' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'product.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/product/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'product.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/product/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'product.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/imports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'imports.showImportForm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/imports/import' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'imports.import',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/imports/downloadTemplate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'imports.downloadTemplate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/transaction' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'transaction.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/transaction/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'transaction.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/general' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/general/patnersetup' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.patnersetup',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/general/productsetup' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.productsetup',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/general/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/workflow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workflow.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/workflow/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workflow.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/documenttype' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documenttype.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/documenttype/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documenttype.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/fees' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'fees.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/fees/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'fees.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/installment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'installment.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/installment/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'installment.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/academics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'academic.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/academics/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'academic.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7LTNfkNsJ8zGijpe',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
            'POST' => 2,
            'PUT' => 3,
            'PATCH' => 4,
            'DELETE' => 5,
            'OPTIONS' => 6,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profile.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'profile.destroy',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings/appearance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'appearance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/accountssetting' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accsetting.GroupOne',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/groupOne/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupOne.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/Grouptwo/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupTwo.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/Groupthree/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupThree.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chartOfAccount' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chartOfAccount/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/invoicelist/AllInvoiceList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.AllInvoiceList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/invoicelist/DueInvoiceList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.DueInvoiceList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/invoicelist/MRList' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.MRList',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/vouhcerheader/credit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vouhcerheader.credit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/vouhcerheader/debit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vouhcerheader.debit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/vouhcerheader/reverse' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vouhcerheader.reverse',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9mI0Jip2M9jYMvvr',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rfXuIdjSt7VfWeSl',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/verify-email' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.notice',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/email/verification-notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/confirm-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.confirm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kabiLGFmrwNDHJ33',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/c(?|o(?|untries/([^/]++)/states(*:39)|mpanyinfo/([^/]++)(*:64))|hartOfAccount/(?|ge(?|tGroupT(?|wo/([^/]++)(*:115)|hree/([^/]++)/([^/]++)(*:145))|nerateAccountCode/([^/]++)(*:180))|([^/]++)(?|/(?|edit(*:208)|status(*:222))|(*:231))|show/([^/]++)(*:253)))|/s(?|t(?|ates/([^/]++)/cities(*:292)|udent(?|S(?|tage/(?|([^/]++)(?|/(?|status(*:341)|edit(*:353))|(*:362))|show/([^/]++)(*:384))|ource/(?|([^/]++)(?|/(?|status(*:423)|edit(*:435))|(*:444))|show/([^/]++)(*:466)))|/(?|([^/]++)/(?|status(*:498)|edit(*:510)|update(*:524))|show/([^/]++)(*:546)|activities/([^/]++)/(?|a(?|llactivities(?|(*:596)|/(?|status(?|/(?|archive(*:628)|transfer(*:644))|(*:653))|assignee(*:670)))|pp(?|lication(?|(*:696)|/(?|([^/]++)/(?|partner(*:727)|([^/]++)/product(*:751))|store(*:765)|([^/]++)(?|/edit(*:789)|(*:797))|show/([^/]++)(*:819)|([^/]++)/(?|activities(*:849)|document(?|(*:868)|/(?|next(*:884)|back(*:896)|checklist(*:913)|store(*:926)|([^/]++)(?|/download(*:954)|(*:962))))|notes(*:978)|tasks(*:991)|payment(*:1006))))|oinments(?|(*:1029)|/store(*:1044)))|ccounts(?|(*:1065)|/(?|return(*:1084)|([^/]++)/(?|create(*:1111)|fetchMR(*:1127))|storeReturn(*:1148)|([^/]++)/(?|fetchSR(*:1176)|returnC(?|ancel(*:1200)|onfirm(*:1215))|store(*:1230)|on(?|View(*:1248)|Delete(*:1263)|Confirm(*:1279)|Report(*:1294))))))|interestedservice(?|(*:1328)|/(?|([^/]++)/(?|partner(*:1360)|([^/]++)/product(*:1385))|store(*:1400)|create(*:1415)|([^/]++)(?|/edit(*:1440)|(*:1449))|show/([^/]++)(*:1472)))|document(*:1491)|notes(*:1505)|quotations(?|(*:1527)|/(?|([^/]++)(*:1548)|general(*:1564)|([^/]++)/(?|confirm(*:1592)|destory(*:1608)|([^/]++)/exportPdf(?|General(*:1645)|Approved(*:1662)))))|c(?|onversations(?|(*:1694)|/(?|store(*:1712)|([^/]++)/fetchData(*:1739)))|heckin(?|(*:1759)|/(?|store(*:1777)|checkOut(*:1794))))|tasks(*:1811)|educations(*:1830))))|orage/(.*)(*:1852))|alarytype/(?|([^/]++)(*:1883)|show/([^/]++)(*:1905)|([^/]++)(?|/(?|edit(*:1933)|status(*:1948))|(*:1958))))|/userpermission/(?|show/([^/]++)(*:2002)|active/([^/]++)(*:2026)|([^/]++)(?|/edit(*:2051)|(*:2060)))|/r(?|oles/(?|([^/]++)(*:2092)|show/([^/]++)(*:2114)|([^/]++)(?|/edit(*:2139)|(*:2148)))|eset\\-password/([^/]++)(*:2182))|/branch/(?|([^/]++)(*:2211)|show/([^/]++)(*:2233)|([^/]++)(?|/edit(*:2258)|(*:2267)))|/d(?|e(?|partment/(?|([^/]++)(*:2307)|show/([^/]++)(*:2329)|([^/]++)(?|/edit(*:2354)|(*:2363)))|signation/(?|([^/]++)(*:2395)|show/([^/]++)(*:2417)|([^/]++)(?|/edit(*:2442)|(*:2451))))|ocument(?|list/(?|([^/]++)(*:2489)|store(*:2503)|([^/]++)/adddoctype(*:2531))|type/([^/]++)(?|/(?|status(*:2567)|edit(*:2580))|(*:2590))))|/lea(?|ve(?|plan/(?|([^/]++)(*:2630)|show/([^/]++)(*:2652)|([^/]++)(?|/edit(*:2677)|(*:2686)))|/(?|show/([^/]++)(*:2714)|([^/]++)(*:2731)|show/([^/]++)(*:2753)|([^/]++)(?|/edit(*:2778)|(*:2787))|confirm/([^/]++)(*:2813)|([^/]++)/([^/]++)/fetchUserLeave(*:2854)))|dreports/(?|ledger/([^/]++)(*:2892)|revenue/([^/]++)/([^/]++)/([^/]++)(?:/([^/]++))?(*:2949)|emp/([^/]++)/([^/]++)/([^/]++)(?:/([^/]++))?(*:3002)))|/work(?|hour/(?|([^/]++)(*:3037)|show/([^/]++)(*:3059)|([^/]++)(?|/(?|edit(*:3087)|status(*:3102))|(*:3112)))|flow/(?|([^/]++)(?|/status(*:3149)|(*:3158))|show/([^/]++)(*:3181)|([^/]++)(?|/edit(*:3206)|(*:3215))))|/a(?|tten(?|setting/(?|([^/]++)(*:3258)|show/([^/]++)(*:3280)|([^/]++)(?|/edit(*:3305)|(*:3314)))|d(?|educt/(?|([^/]++)(*:3346)|show/([^/]++)(*:3368)|([^/]++)(?|/edit(*:3393)|(*:3402)))|anceStatus/(?|([^/]++)(?|/(?|edit(*:3446)|status(*:3461))|(*:3471))|show/([^/]++)(*:3494))))|pproval/(?|student(?|Archive/([^/]++)(*:3543)|Transfer/([^/]++)(*:3569)|OnBoard/([^/]++)(*:3594))|([^/]++)/(?|leave(?|Request(*:3631)|Approved(*:3648)|Cancel(*:3663))|Quoattion(?|View(*:3689)|C(?|onfirm(*:3708)|ancel(*:3722)))|ReturnC(?|onfirm(*:3749)|ancel(*:3763))))|c(?|ademics/(?|([^/]++)(?|/status(*:3808)|(*:3817))|show/([^/]++)(*:3840)|([^/]++)(?|/edit(*:3865)|(*:3874)))|countssetting/([^/]++)/(?|Grouptwo(*:3919)|([^/]++)/Groupthree(*:3947))))|/holiday(?|Hd/(?|([^/]++)(?|/status(*:3994)|(*:4003))|show/([^/]++)(*:4026)|([^/]++)(?|/edit(*:4051)|(*:4060)))|dt/(?|([^/]++)/(?|create(*:4095)|edit(?|(*:4111)))|destroy/([^/]++)(*:4138)))|/p(?|ersonalinfo/(?|([^/]++)(?|/status(*:4187)|(*:4196))|show/([^/]++)(*:4219)|([^/]++)(?|/edit(*:4244)|(*:4253)))|artner(?|branch/(?|([^/]++)(?|/(?|status(*:4304)|edit(*:4317))|(*:4327))|show/([^/]++)(*:4350))|/(?|([^/]++)(?|/(?|status(*:4385)|edit(*:4398))|(*:4408))|show/([^/]++)(*:4431)|activities/([^/]++)/(?|a(?|p(?|lication(*:4479)|poinments(*:4497))|ggrements(*:4516)|ccounts(*:4532))|pro(?|duct(*:4552)|motions(*:4568))|branch(?|(*:4587)|/show/([^/]++)(*:4610))|con(?|tacts(*:4631)|versations(*:4650))|notes(*:4665)|documents(*:4683)|tasks(*:4697)|others(*:4712))))|roduct/(?|([^/]++)(?|/(?|status(*:4755)|edit(*:4768))|(*:4778))|show/([^/]++)(*:4801)|activities/([^/]++)/(?|aplication(*:4843)|documents(*:4861)|fees(?|(*:4877)|/(?|([^/]++)(*:4898)|show/([^/]++)(*:4920)))|requirement(?|(*:4945)|/([^/]++)/edit(*:4968)|(*:4977)|Eng(?|/([^/]++)/edit(*:5006)|(*:5015))|Others(?|/([^/]++)/edit(*:5048)|(*:5057)))|others(*:5074)|promotions(*:5093))))|/transaction/(?|([^/]++)(?|/status(*:5139)|(*:5148))|show/([^/]++)(*:5171)|([^/]++)(?|/edit(*:5196)|(*:5205)))|/g(?|eneral/(?|([^/]++)(?|/(?|edit(*:5250)|status(*:5265))|(*:5275))|show/([^/]++)(*:5298)|patnersetup(*:5318)|([^/]++)/patnersetupstatus(*:5353)|p(?|atnersetup/show/([^/]++)(*:5390)|roductsetup(*:5410))|([^/]++)/producttypeupstatus(*:5448)|productsetup/show/([^/]++)(*:5483))|roupOne/(?|([^/]++)(?|/(?|edit(*:5523)|status(*:5538))|(*:5548))|show/([^/]++)(*:5571)))|/fees/(?|([^/]++)(?|/status(*:5609)|(*:5618))|show/([^/]++)(*:5641)|([^/]++)(?|/edit(*:5666)|(*:5675)))|/in(?|stallment/(?|([^/]++)(?|/status(*:5723)|(*:5732))|show/([^/]++)(*:5755)|([^/]++)(?|/edit(*:5780)|(*:5789)))|voicelist/([^/]++)/(?|createmr/([^/]++)(*:5839)|storeMR/([^/]++)(*:5864)|on(?|View(*:5882)|C(?|ancel(*:5900)|onfirm(*:5915))|Report(*:5931))))|/Groupt(?|wo/(?|([^/]++)(?|/(?|edit(*:5978)|status(*:5993))|(*:6003))|show/([^/]++)(*:6026))|hree/(?|([^/]++)(?|/(?|edit(*:6063)|status(*:6078))|(*:6088))|show/([^/]++)(*:6111)))|/verify\\-email/([^/]++)/([^/]++)(*:6154))/?$}sDu',
    ),
    3 => 
    array (
      39 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9Hi10pj6iWU71ldj',
          ),
          1 => 
          array (
            0 => 'country',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      64 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'company.update',
          ),
          1 => 
          array (
            0 => 'companyInfo',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      115 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.getGroupTwo',
          ),
          1 => 
          array (
            0 => 'GroupOne',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      145 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.getGroupThree',
          ),
          1 => 
          array (
            0 => 'GroupOne',
            1 => 'GroupTwo',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      180 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.generateCode',
          ),
          1 => 
          array (
            0 => 'groupthree',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      208 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.edit',
          ),
          1 => 
          array (
            0 => 'chartOfAccount',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      222 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.updateStatus',
          ),
          1 => 
          array (
            0 => 'chartOfAccount',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      231 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.update',
          ),
          1 => 
          array (
            0 => 'chartOfAccount',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.show',
          ),
          1 => 
          array (
            0 => 'chartOfAccount',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      253 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chartOfAccount.destroy',
          ),
          1 => 
          array (
            0 => 'chartOfAccount',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      292 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::r4qUclUarAXS6WY5',
          ),
          1 => 
          array (
            0 => 'state',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      341 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentStage.updateStatus',
          ),
          1 => 
          array (
            0 => 'studentStage',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      353 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentStage.edit',
          ),
          1 => 
          array (
            0 => 'studentStage',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      362 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentStage.update',
          ),
          1 => 
          array (
            0 => 'studentStage',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      384 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentStage.destroy',
          ),
          1 => 
          array (
            0 => 'studentStage',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      423 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentSource.updateStatus',
          ),
          1 => 
          array (
            0 => 'studentSource',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      435 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentSource.edit',
          ),
          1 => 
          array (
            0 => 'studentSource',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      444 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentSource.update',
          ),
          1 => 
          array (
            0 => 'studentSource',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      466 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentSource.destroy',
          ),
          1 => 
          array (
            0 => 'studentSource',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      498 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.updateStatus',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      510 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.edit',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      524 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.update',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      546 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'student.destroy',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      596 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentActivities.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      628 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentActivities.updateArchive',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      644 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentActivities.confirmonBoard',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      653 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentActivities.updateRate',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      670 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentActivities.updateAssignee',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      696 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      727 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.partner',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      751 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.product',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'product',
            2 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      765 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.store',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      789 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.edit',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      797 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.update',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      819 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.destroy',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      849 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.appActivities',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      868 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.documentApplication',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      884 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.documentNextStep',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      896 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.documentBackStep',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      913 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.updateCheckList',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      926 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.docAppStore',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      954 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.docAppDownload',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
            2 => 'document',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      962 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.docAppDelete',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
            2 => 'document',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      978 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.notesApplication',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      991 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.tasksApplication',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1006 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentApplication.paymentApplication',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentApplication',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1029 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAppointements.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1044 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAppointements.store',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1065 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1084 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.return',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.create',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'quotation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1127 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.fetchMR',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'mrid',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1148 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.storeReturn',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1176 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.fetchSR',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'srid',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1200 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.returnCancel',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'confirm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1215 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.returnConfirm',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'confirm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1230 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.store',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'quotation',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1248 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.onView',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'confirm',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1263 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.onDelete',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'confirm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1279 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.onConfirm',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'confirm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1294 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentAccounts.onReport',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'confirm',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1328 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1360 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.partner',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1385 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.product',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'product',
            2 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1400 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.store',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1415 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.create',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1440 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.editApplication',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentInService',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1449 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.update',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentInService',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1472 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentInService.destroy',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'studentInService',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1491 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentDocument.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1505 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentNotes.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1527 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentQuotations.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1548 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentQuotations.fetchData',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1564 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentQuotations.store',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1592 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentQuotations.confirm',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'product',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1608 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentQuotations.destory',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'product',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1645 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentQuotations.exportPdfGeneral',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'product',
            2 => 'quoatation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1662 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentQuotations.exportPdfApproved',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'product',
            2 => 'quoatation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1694 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentConversations.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1712 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentConversations.store',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1739 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentConversations.fetchData',
          ),
          1 => 
          array (
            0 => 'student',
            1 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1759 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentCheckin.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1777 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentCheckin.store',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1794 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentCheckin.checkOut',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1811 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentTasks.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1830 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studentEducations.index',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1852 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1883 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'salarytype.show',
          ),
          1 => 
          array (
            0 => 'salaryType',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1905 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'salarytype.destroy',
          ),
          1 => 
          array (
            0 => 'salaryType',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1933 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'salarytype.edit',
          ),
          1 => 
          array (
            0 => 'salaryType',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1948 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'salarytype.updateStatus',
          ),
          1 => 
          array (
            0 => 'salaryType',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1958 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'salarytype.update',
          ),
          1 => 
          array (
            0 => 'salaryType',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2002 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userpermission.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2026 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userpermission.active',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2051 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userpermission.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2060 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'userpermission.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2092 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.show',
          ),
          1 => 
          array (
            0 => 'roles',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2114 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.destroy',
          ),
          1 => 
          array (
            0 => 'roles',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2139 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.edit',
          ),
          1 => 
          array (
            0 => 'roles',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2148 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'roles.update',
          ),
          1 => 
          array (
            0 => 'roles',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2182 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2211 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'branch.show',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2233 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'branch.destroy',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2258 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'branch.edit',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2267 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'branch.update',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2307 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'department.show',
          ),
          1 => 
          array (
            0 => 'department',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2329 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'department.destroy',
          ),
          1 => 
          array (
            0 => 'department',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2354 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'department.edit',
          ),
          1 => 
          array (
            0 => 'department',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2363 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'department.update',
          ),
          1 => 
          array (
            0 => 'department',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2395 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'designation.show',
          ),
          1 => 
          array (
            0 => 'designation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2417 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'designation.destroy',
          ),
          1 => 
          array (
            0 => 'designation',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2442 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'designation.edit',
          ),
          1 => 
          array (
            0 => 'designation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2451 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'designation.update',
          ),
          1 => 
          array (
            0 => 'designation',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2489 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documentlist.index',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2503 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documentlist.store',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2531 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documenttype.adddoctype',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2567 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documenttype.updateStatus',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2580 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documenttype.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2590 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'documenttype.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2630 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leaveplan.show',
          ),
          1 => 
          array (
            0 => 'leaveplan',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2652 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leaveplan.destroy',
          ),
          1 => 
          array (
            0 => 'leaveplan',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2677 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leaveplan.edit',
          ),
          1 => 
          array (
            0 => 'leaveplan',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2686 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leaveplan.update',
          ),
          1 => 
          array (
            0 => 'leaveplan',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2714 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.show',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2731 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.exportPdf',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2753 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.destroy',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2778 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.edit',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2787 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.update',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2813 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.confirm',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2854 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leave.fetchUserLeave',
          ),
          1 => 
          array (
            0 => 'leave',
            1 => 'empid',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2892 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leadreports.studentLedgerReport',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2949 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leadreports.studentRevenueReport',
            'employee' => NULL,
          ),
          1 => 
          array (
            0 => 'formdate',
            1 => 'todate',
            2 => 'isAdmin',
            3 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3002 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'leadreports.MonthlyEmpLeadReport',
            'employee' => NULL,
          ),
          1 => 
          array (
            0 => 'formdate',
            1 => 'todate',
            2 => 'isAdmin',
            3 => 'employee',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3037 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workhour.show',
          ),
          1 => 
          array (
            0 => 'workHourSetup',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3059 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workhour.destroy',
          ),
          1 => 
          array (
            0 => 'workHourSetup',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3087 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workhour.edit',
          ),
          1 => 
          array (
            0 => 'workHourSetup',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3102 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workhour.updateStatus',
          ),
          1 => 
          array (
            0 => 'workHourSetup',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3112 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workhour.update',
          ),
          1 => 
          array (
            0 => 'workHourSetup',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3149 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workflow.updateStatus',
          ),
          1 => 
          array (
            0 => 'workflow',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3158 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workflow.show',
          ),
          1 => 
          array (
            0 => 'workflow',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3181 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workflow.destroy',
          ),
          1 => 
          array (
            0 => 'workflow',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3206 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workflow.edit',
          ),
          1 => 
          array (
            0 => 'workflow',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3215 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'workflow.update',
          ),
          1 => 
          array (
            0 => 'workflow',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3258 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attensetting.show',
          ),
          1 => 
          array (
            0 => 'attensetting',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3280 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attensetting.destroy',
          ),
          1 => 
          array (
            0 => 'attensetting',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3305 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attensetting.edit',
          ),
          1 => 
          array (
            0 => 'attensetting',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3314 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attensetting.update',
          ),
          1 => 
          array (
            0 => 'attensetting',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3346 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendeduct.show',
          ),
          1 => 
          array (
            0 => 'attendeduct',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3368 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendeduct.destroy',
          ),
          1 => 
          array (
            0 => 'attendeduct',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3393 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendeduct.edit',
          ),
          1 => 
          array (
            0 => 'attendeduct',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3402 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendeduct.update',
          ),
          1 => 
          array (
            0 => 'attendeduct',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3446 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.edit',
          ),
          1 => 
          array (
            0 => 'attendanceStatus',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3461 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.updateStatus',
          ),
          1 => 
          array (
            0 => 'attendanceStatus',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3471 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.update',
          ),
          1 => 
          array (
            0 => 'attendanceStatus',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.show',
          ),
          1 => 
          array (
            0 => 'attendanceStatus',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3494 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'attendanceStatus.destroy',
          ),
          1 => 
          array (
            0 => 'attendanceStatus',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3543 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.studentArchive',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3569 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.studentTransfer',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3594 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.studentOnBoard',
          ),
          1 => 
          array (
            0 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3631 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.leaveRequest',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3648 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.leaveApproved',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3663 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.leaveCancel',
          ),
          1 => 
          array (
            0 => 'leave',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3689 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.QuoattionView',
          ),
          1 => 
          array (
            0 => 'quotation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3708 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.QuoattionConfirm',
          ),
          1 => 
          array (
            0 => 'quotation',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3722 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.QuoattionCancel',
          ),
          1 => 
          array (
            0 => 'quotation',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3749 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.ReturnConfirm',
          ),
          1 => 
          array (
            0 => 'return',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3763 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'approval.ReturnCancel',
          ),
          1 => 
          array (
            0 => 'return',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3808 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'academic.updateStatus',
          ),
          1 => 
          array (
            0 => 'academic',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3817 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'academic.show',
          ),
          1 => 
          array (
            0 => 'academic',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3840 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'academic.destroy',
          ),
          1 => 
          array (
            0 => 'academic',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3865 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'academic.edit',
          ),
          1 => 
          array (
            0 => 'academic',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3874 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'academic.update',
          ),
          1 => 
          array (
            0 => 'academic',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3919 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accsetting.GroupTwo',
          ),
          1 => 
          array (
            0 => 'GroupOne',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3947 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accsetting.GroupThree',
          ),
          1 => 
          array (
            0 => 'GroupOne',
            1 => 'GroupTwo',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3994 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidayhd.updateStatus',
          ),
          1 => 
          array (
            0 => 'holidayhd',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4003 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidayHd.show',
          ),
          1 => 
          array (
            0 => 'holidayHd',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4026 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidayHd.destroy',
          ),
          1 => 
          array (
            0 => 'holidayHd',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4051 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidayHd.edit',
          ),
          1 => 
          array (
            0 => 'holidayHd',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4060 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidayHd.update',
          ),
          1 => 
          array (
            0 => 'holidayHd',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4095 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidaydt.create',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidaydt.edit',
          ),
          1 => 
          array (
            0 => 'holidayDt',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'holidaydt.update',
          ),
          1 => 
          array (
            0 => 'holidayDt',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4138 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'holidaydt.destroy',
          ),
          1 => 
          array (
            0 => 'holidayDt',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4187 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'personalinfo.updateStatus',
          ),
          1 => 
          array (
            0 => 'PersonalInfo',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4196 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'personalinfo.show',
          ),
          1 => 
          array (
            0 => 'PersonalInfo',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4219 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'personalinfo.destroy',
          ),
          1 => 
          array (
            0 => 'PersonalInfo',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4244 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'personalinfo.edit',
          ),
          1 => 
          array (
            0 => 'PersonalInfo',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4253 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'personalinfo.update',
          ),
          1 => 
          array (
            0 => 'PersonalInfo',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4304 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partnerbranch.updateStatus',
          ),
          1 => 
          array (
            0 => 'PartnerBranch',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4317 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partnerbranch.edit',
          ),
          1 => 
          array (
            0 => 'PartnerBranch',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4327 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.partnerbranch',
          ),
          1 => 
          array (
            0 => 'PartnerBranch',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4350 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partnerbranch.destroy',
          ),
          1 => 
          array (
            0 => 'PartnerBranch',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4385 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.updateStatus',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4398 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.edit',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4408 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.update',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4431 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'partner.destroy',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4479 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.application',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4497 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.appoinments',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4516 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.aggrements',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4532 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.accounts',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4552 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.product',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4568 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.promotions',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4587 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.branch',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.branchStore',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4610 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.branchDelete',
          ),
          1 => 
          array (
            0 => 'partner',
            1 => 'partnerBranch',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4631 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.contacts',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4650 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.conversations',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4665 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.notes',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4683 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.documents',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4697 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.tasks',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4712 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'PartnerActivities.others',
          ),
          1 => 
          array (
            0 => 'partner',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4755 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'product.updateStatus',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4768 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'product.edit',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4778 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'product.update',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4801 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'product.destroy',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4843 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.application',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4861 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.documents',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4877 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.fees',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.storefess',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4898 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.updatefees',
          ),
          1 => 
          array (
            0 => 'product',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4920 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.feeDelete',
          ),
          1 => 
          array (
            0 => 'product',
            1 => 'productFeesHd',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4945 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.requirement',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4968 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.editRequirement',
          ),
          1 => 
          array (
            0 => 'product',
            1 => 'requirement',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4977 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.storeRequirement',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5006 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.editRequirementEng',
          ),
          1 => 
          array (
            0 => 'product',
            1 => 'requirementEng',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5015 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.storeRequirementEng',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5048 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.editRequirementOthers',
          ),
          1 => 
          array (
            0 => 'product',
            1 => 'requirementOthers',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5057 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.storeRequirementOthers',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5074 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.others',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5093 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productActivities.promotions',
          ),
          1 => 
          array (
            0 => 'product',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5139 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'transaction.updateStatus',
          ),
          1 => 
          array (
            0 => 'transaction',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5148 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'transaction.show',
          ),
          1 => 
          array (
            0 => 'transaction',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5171 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'transaction.destroy',
          ),
          1 => 
          array (
            0 => 'transaction',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5196 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'transaction.edit',
          ),
          1 => 
          array (
            0 => 'transaction',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5205 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'transaction.update',
          ),
          1 => 
          array (
            0 => 'transaction',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5250 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.edit',
          ),
          1 => 
          array (
            0 => 'general',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5265 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.updateStatus',
          ),
          1 => 
          array (
            0 => 'general',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5275 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.update',
          ),
          1 => 
          array (
            0 => 'general',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'general.show',
          ),
          1 => 
          array (
            0 => 'general',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5298 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.destroy',
          ),
          1 => 
          array (
            0 => 'general',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5318 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.patnersetupstore',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5353 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.patnersetupUpdateStatus',
          ),
          1 => 
          array (
            0 => 'patnersetup',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5390 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'patnersetup.patnersetupdestroy',
          ),
          1 => 
          array (
            0 => 'patnersetup',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5410 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.productsetuppstore',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5448 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'general.producttypeUpdateStatus',
          ),
          1 => 
          array (
            0 => 'productsetup',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5483 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'productsetup.productsetupdestroy',
          ),
          1 => 
          array (
            0 => 'productsetup',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5523 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupOne.edit',
          ),
          1 => 
          array (
            0 => 'groupOne',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5538 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupOne.updateStatus',
          ),
          1 => 
          array (
            0 => 'groupOne',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5548 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupOne.update',
          ),
          1 => 
          array (
            0 => 'groupOne',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'GroupOne.show',
          ),
          1 => 
          array (
            0 => 'groupOne',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5571 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupOne.destroy',
          ),
          1 => 
          array (
            0 => 'groupOne',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5609 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'fees.updateStatus',
          ),
          1 => 
          array (
            0 => 'fees',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5618 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'fees.show',
          ),
          1 => 
          array (
            0 => 'fees',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5641 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'fees.destroy',
          ),
          1 => 
          array (
            0 => 'fees',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5666 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'fees.edit',
          ),
          1 => 
          array (
            0 => 'fees',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5675 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'fees.update',
          ),
          1 => 
          array (
            0 => 'fees',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5723 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'installment.updateStatus',
          ),
          1 => 
          array (
            0 => 'installment',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5732 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'installment.show',
          ),
          1 => 
          array (
            0 => 'installment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5755 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'installment.destroy',
          ),
          1 => 
          array (
            0 => 'installment',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5780 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'installment.edit',
          ),
          1 => 
          array (
            0 => 'installment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5789 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'installment.update',
          ),
          1 => 
          array (
            0 => 'installment',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5839 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.createMR',
          ),
          1 => 
          array (
            0 => 'insid',
            1 => 'sid',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5864 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.storeMR',
          ),
          1 => 
          array (
            0 => 'insnumber',
            1 => 'student',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5882 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.onView',
          ),
          1 => 
          array (
            0 => 'confirm',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5900 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.onCancel',
          ),
          1 => 
          array (
            0 => 'confirm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5915 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.onConfirm',
          ),
          1 => 
          array (
            0 => 'confirm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5931 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'invoicelist.onReport',
          ),
          1 => 
          array (
            0 => 'onReport',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5978 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupTwo.edit',
          ),
          1 => 
          array (
            0 => 'groupTwo',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5993 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupTwo.updateStatus',
          ),
          1 => 
          array (
            0 => 'groupTwo',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      6003 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupTwo.update',
          ),
          1 => 
          array (
            0 => 'groupTwo',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'GroupTwo.show',
          ),
          1 => 
          array (
            0 => 'groupTwo',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      6026 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupTwo.destroy',
          ),
          1 => 
          array (
            0 => 'groupTwo',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      6063 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupThree.edit',
          ),
          1 => 
          array (
            0 => 'groupThree',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      6078 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupThree.updateStatus',
          ),
          1 => 
          array (
            0 => 'groupThree',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      6088 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupThree.update',
          ),
          1 => 
          array (
            0 => 'groupThree',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'GroupThree.show',
          ),
          1 => 
          array (
            0 => 'groupThree',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      6111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'GroupThree.destroy',
          ),
          1 => 
          array (
            0 => 'groupThree',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      6154 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.verify',
          ),
          1 => 
          array (
            0 => 'id',
            1 => 'hash',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'sanctum.csrf-cookie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'sanctum.csrf-cookie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6VAQc8yMFZGcRuPE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:77:"function (\\Illuminate\\Http\\Request $request) {
    return $request->user();
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000007370000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6VAQc8yMFZGcRuPE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'device.syncData' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/attendances',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DeviceController@syncData',
        'controller' => 'App\\Http\\Controllers\\HRM\\DeviceController@syncData',
        'namespace' => NULL,
        'prefix' => 'api/attendances',
        'where' => 
        array (
        ),
        'as' => 'device.syncData',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hOWPzKSZzhEv5ufH' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/attendances/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DeviceController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\DeviceController@store',
        'namespace' => NULL,
        'prefix' => 'api/attendances',
        'where' => 
        array (
        ),
        'as' => 'generated::hOWPzKSZzhEv5ufH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Zsz0AvE5gAwpY3J0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:829:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'D:\\\\xampp\\\\htdocs\\\\lara12hrm\\\\vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Foundation\\\\Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"00000000000007390000000000000000";}}',
        'as' => 'generated::Zsz0AvE5gAwpY3J0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:66:"function () {
    return \\Inertia\\Inertia::render(\'auth/Login\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000007300000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.ArchiveRequest' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/ArchiveRequest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@ArchiveRequest',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@ArchiveRequest',
        'namespace' => NULL,
        'prefix' => '/dashboard',
        'where' => 
        array (
        ),
        'as' => 'dashboard.ArchiveRequest',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.TransferRequest' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/TransferRequest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@TransferRequest',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@TransferRequest',
        'namespace' => NULL,
        'prefix' => '/dashboard',
        'where' => 
        array (
        ),
        'as' => 'dashboard.TransferRequest',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.onBoardRequest' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/onBoardRequest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@onBoardRequest',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@onBoardRequest',
        'namespace' => NULL,
        'prefix' => '/dashboard',
        'where' => 
        array (
        ),
        'as' => 'dashboard.onBoardRequest',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.LeaveRequest' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/LeaveRequest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@LeaveRequest',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@LeaveRequest',
        'namespace' => NULL,
        'prefix' => '/dashboard',
        'where' => 
        array (
        ),
        'as' => 'dashboard.LeaveRequest',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.QuotationRequest' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/QuotationRequest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@QuotationRequest',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@QuotationRequest',
        'namespace' => NULL,
        'prefix' => '/dashboard',
        'where' => 
        array (
        ),
        'as' => 'dashboard.QuotationRequest',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.ReturnRequest' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/ReturnRequest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@ReturnRequest',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@ReturnRequest',
        'namespace' => NULL,
        'prefix' => '/dashboard',
        'where' => 
        array (
        ),
        'as' => 'dashboard.ReturnRequest',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.Calender' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/Calender',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@Calender',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@Calender',
        'namespace' => NULL,
        'prefix' => '/dashboard',
        'where' => 
        array (
        ),
        'as' => 'dashboard.Calender',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\DashboardController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Default\\DashboardController@dashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'users/list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\CountryController@userlist',
        'controller' => 'App\\Http\\Controllers\\Default\\CountryController@userlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.list',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9Hi10pj6iWU71ldj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'countries/{country}/states',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\CountryController@states',
        'controller' => 'App\\Http\\Controllers\\Default\\CountryController@states',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::9Hi10pj6iWU71ldj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::r4qUclUarAXS6WY5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'states/{state}/cities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\StateController@cities',
        'controller' => 'App\\Http\\Controllers\\Default\\StateController@cities',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::r4qUclUarAXS6WY5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userpermission.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'userpermission',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\UserPermissionController@index',
        'controller' => 'App\\Http\\Controllers\\Users\\UserPermissionController@index',
        'namespace' => NULL,
        'prefix' => '/userpermission',
        'where' => 
        array (
        ),
        'as' => 'userpermission.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userpermission.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'userpermission/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\UserPermissionController@store',
        'controller' => 'App\\Http\\Controllers\\Users\\UserPermissionController@store',
        'namespace' => NULL,
        'prefix' => '/userpermission',
        'where' => 
        array (
        ),
        'as' => 'userpermission.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userpermission.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'userpermission/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\UserPermissionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Users\\UserPermissionController@destroy',
        'namespace' => NULL,
        'prefix' => '/userpermission',
        'where' => 
        array (
        ),
        'as' => 'userpermission.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userpermission.active' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'userpermission/active/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\UserPermissionController@active',
        'controller' => 'App\\Http\\Controllers\\Users\\UserPermissionController@active',
        'namespace' => NULL,
        'prefix' => '/userpermission',
        'where' => 
        array (
        ),
        'as' => 'userpermission.active',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userpermission.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'userpermission/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\UserPermissionController@edit',
        'controller' => 'App\\Http\\Controllers\\Users\\UserPermissionController@edit',
        'namespace' => NULL,
        'prefix' => '/userpermission',
        'where' => 
        array (
        ),
        'as' => 'userpermission.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'userpermission.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'userpermission/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\UserPermissionController@update',
        'controller' => 'App\\Http\\Controllers\\Users\\UserPermissionController@update',
        'namespace' => NULL,
        'prefix' => '/userpermission',
        'where' => 
        array (
        ),
        'as' => 'userpermission.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\RoleController@index',
        'controller' => 'App\\Http\\Controllers\\Users\\RoleController@index',
        'namespace' => NULL,
        'prefix' => '/roles',
        'where' => 
        array (
        ),
        'as' => 'roles.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'roles/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\RoleController@store',
        'controller' => 'App\\Http\\Controllers\\Users\\RoleController@store',
        'namespace' => NULL,
        'prefix' => '/roles',
        'where' => 
        array (
        ),
        'as' => 'roles.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'roles/{roles}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\RoleController@show',
        'controller' => 'App\\Http\\Controllers\\Users\\RoleController@show',
        'namespace' => NULL,
        'prefix' => '/roles',
        'where' => 
        array (
        ),
        'as' => 'roles.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'roles/show/{roles}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\RoleController@destroy',
        'controller' => 'App\\Http\\Controllers\\Users\\RoleController@destroy',
        'namespace' => NULL,
        'prefix' => '/roles',
        'where' => 
        array (
        ),
        'as' => 'roles.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'roles/{roles}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\RoleController@edit',
        'controller' => 'App\\Http\\Controllers\\Users\\RoleController@edit',
        'namespace' => NULL,
        'prefix' => '/roles',
        'where' => 
        array (
        ),
        'as' => 'roles.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'roles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'roles/{roles}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Users\\RoleController@update',
        'controller' => 'App\\Http\\Controllers\\Users\\RoleController@update',
        'namespace' => NULL,
        'prefix' => '/roles',
        'where' => 
        array (
        ),
        'as' => 'roles.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'company.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'companyinfo',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\CompanyInfoController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\CompanyInfoController@edit',
        'namespace' => NULL,
        'prefix' => '/companyinfo',
        'where' => 
        array (
        ),
        'as' => 'company.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'company.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'companyinfo/{companyInfo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\CompanyInfoController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\CompanyInfoController@update',
        'namespace' => NULL,
        'prefix' => '/companyinfo',
        'where' => 
        array (
        ),
        'as' => 'company.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'branch.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'branch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\BranchController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\BranchController@index',
        'namespace' => NULL,
        'prefix' => '/branch',
        'where' => 
        array (
        ),
        'as' => 'branch.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'branch.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'branch/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\BranchController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\BranchController@store',
        'namespace' => NULL,
        'prefix' => '/branch',
        'where' => 
        array (
        ),
        'as' => 'branch.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'branch.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'branch/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\BranchController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\BranchController@show',
        'namespace' => NULL,
        'prefix' => '/branch',
        'where' => 
        array (
        ),
        'as' => 'branch.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'branch.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'branch/show/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\BranchController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\BranchController@destroy',
        'namespace' => NULL,
        'prefix' => '/branch',
        'where' => 
        array (
        ),
        'as' => 'branch.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'branch.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'branch/{branch}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\BranchController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\BranchController@edit',
        'namespace' => NULL,
        'prefix' => '/branch',
        'where' => 
        array (
        ),
        'as' => 'branch.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'branch.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'branch/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\BranchController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\BranchController@update',
        'namespace' => NULL,
        'prefix' => '/branch',
        'where' => 
        array (
        ),
        'as' => 'branch.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'department.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'department',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DepartmentController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\DepartmentController@index',
        'namespace' => NULL,
        'prefix' => '/department',
        'where' => 
        array (
        ),
        'as' => 'department.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'department.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'department/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DepartmentController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\DepartmentController@store',
        'namespace' => NULL,
        'prefix' => '/department',
        'where' => 
        array (
        ),
        'as' => 'department.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'department.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'department/{department}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DepartmentController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\DepartmentController@show',
        'namespace' => NULL,
        'prefix' => '/department',
        'where' => 
        array (
        ),
        'as' => 'department.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'department.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'department/show/{department}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DepartmentController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\DepartmentController@destroy',
        'namespace' => NULL,
        'prefix' => '/department',
        'where' => 
        array (
        ),
        'as' => 'department.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'department.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'department/{department}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DepartmentController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\DepartmentController@edit',
        'namespace' => NULL,
        'prefix' => '/department',
        'where' => 
        array (
        ),
        'as' => 'department.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'department.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'department/{department}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DepartmentController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\DepartmentController@update',
        'namespace' => NULL,
        'prefix' => '/department',
        'where' => 
        array (
        ),
        'as' => 'department.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'designation.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'designation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DesignationController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\DesignationController@index',
        'namespace' => NULL,
        'prefix' => '/designation',
        'where' => 
        array (
        ),
        'as' => 'designation.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'designation.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'designation/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DesignationController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\DesignationController@store',
        'namespace' => NULL,
        'prefix' => '/designation',
        'where' => 
        array (
        ),
        'as' => 'designation.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'designation.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'designation/{designation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DesignationController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\DesignationController@show',
        'namespace' => NULL,
        'prefix' => '/designation',
        'where' => 
        array (
        ),
        'as' => 'designation.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'designation.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'designation/show/{designation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DesignationController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\DesignationController@destroy',
        'namespace' => NULL,
        'prefix' => '/designation',
        'where' => 
        array (
        ),
        'as' => 'designation.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'designation.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'designation/{designation}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DesignationController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\DesignationController@edit',
        'namespace' => NULL,
        'prefix' => '/designation',
        'where' => 
        array (
        ),
        'as' => 'designation.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'designation.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'designation/{designation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\DesignationController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\DesignationController@update',
        'namespace' => NULL,
        'prefix' => '/designation',
        'where' => 
        array (
        ),
        'as' => 'designation.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leaveplan.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leaveplan',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@index',
        'namespace' => NULL,
        'prefix' => '/leaveplan',
        'where' => 
        array (
        ),
        'as' => 'leaveplan.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leaveplan.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'leaveplan/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@store',
        'namespace' => NULL,
        'prefix' => '/leaveplan',
        'where' => 
        array (
        ),
        'as' => 'leaveplan.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leaveplan.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leaveplan/{leaveplan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@show',
        'namespace' => NULL,
        'prefix' => '/leaveplan',
        'where' => 
        array (
        ),
        'as' => 'leaveplan.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leaveplan.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'leaveplan/show/{leaveplan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@destroy',
        'namespace' => NULL,
        'prefix' => '/leaveplan',
        'where' => 
        array (
        ),
        'as' => 'leaveplan.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leaveplan.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leaveplan/{leaveplan}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@edit',
        'namespace' => NULL,
        'prefix' => '/leaveplan',
        'where' => 
        array (
        ),
        'as' => 'leaveplan.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leaveplan.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'leaveplan/{leaveplan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveplanController@update',
        'namespace' => NULL,
        'prefix' => '/leaveplan',
        'where' => 
        array (
        ),
        'as' => 'leaveplan.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workhour.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'workhour',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@index',
        'namespace' => NULL,
        'prefix' => '/workhour',
        'where' => 
        array (
        ),
        'as' => 'workhour.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workhour.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'workhour/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@store',
        'namespace' => NULL,
        'prefix' => '/workhour',
        'where' => 
        array (
        ),
        'as' => 'workhour.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workhour.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'workhour/{workHourSetup}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@show',
        'namespace' => NULL,
        'prefix' => '/workhour',
        'where' => 
        array (
        ),
        'as' => 'workhour.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workhour.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'workhour/show/{workHourSetup}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@destroy',
        'namespace' => NULL,
        'prefix' => '/workhour',
        'where' => 
        array (
        ),
        'as' => 'workhour.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workhour.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'workhour/{workHourSetup}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@edit',
        'namespace' => NULL,
        'prefix' => '/workhour',
        'where' => 
        array (
        ),
        'as' => 'workhour.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workhour.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'workhour/{workHourSetup}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@update',
        'namespace' => NULL,
        'prefix' => '/workhour',
        'where' => 
        array (
        ),
        'as' => 'workhour.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workhour.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'workhour/{workHourSetup}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\HRM\\WorkHourSetupController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/workhour',
        'where' => 
        array (
        ),
        'as' => 'workhour.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attensetting.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attensetting',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@index',
        'namespace' => NULL,
        'prefix' => '/attensetting',
        'where' => 
        array (
        ),
        'as' => 'attensetting.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attensetting.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'attensetting/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@store',
        'namespace' => NULL,
        'prefix' => '/attensetting',
        'where' => 
        array (
        ),
        'as' => 'attensetting.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attensetting.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attensetting/{attensetting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@show',
        'namespace' => NULL,
        'prefix' => '/attensetting',
        'where' => 
        array (
        ),
        'as' => 'attensetting.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attensetting.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'attensetting/show/{attensetting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@destroy',
        'namespace' => NULL,
        'prefix' => '/attensetting',
        'where' => 
        array (
        ),
        'as' => 'attensetting.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attensetting.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attensetting/{attensetting}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@edit',
        'namespace' => NULL,
        'prefix' => '/attensetting',
        'where' => 
        array (
        ),
        'as' => 'attensetting.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attensetting.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'attensetting/{attensetting}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenSettingController@update',
        'namespace' => NULL,
        'prefix' => '/attensetting',
        'where' => 
        array (
        ),
        'as' => 'attensetting.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendeduct.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attendeduct',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@index',
        'namespace' => NULL,
        'prefix' => '/attendeduct',
        'where' => 
        array (
        ),
        'as' => 'attendeduct.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendeduct.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'attendeduct/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@store',
        'namespace' => NULL,
        'prefix' => '/attendeduct',
        'where' => 
        array (
        ),
        'as' => 'attendeduct.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendeduct.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attendeduct/{attendeduct}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@show',
        'namespace' => NULL,
        'prefix' => '/attendeduct',
        'where' => 
        array (
        ),
        'as' => 'attendeduct.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendeduct.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'attendeduct/show/{attendeduct}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@destroy',
        'namespace' => NULL,
        'prefix' => '/attendeduct',
        'where' => 
        array (
        ),
        'as' => 'attendeduct.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendeduct.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attendeduct/{attendeduct}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@edit',
        'namespace' => NULL,
        'prefix' => '/attendeduct',
        'where' => 
        array (
        ),
        'as' => 'attendeduct.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendeduct.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'attendeduct/{attendeduct}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttenDeductController@update',
        'namespace' => NULL,
        'prefix' => '/attendeduct',
        'where' => 
        array (
        ),
        'as' => 'attendeduct.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attendanceStatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@index',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attendanceStatus/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@create',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@create',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'attendanceStatus/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@store',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attendanceStatus/{attendanceStatus}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@edit',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'attendanceStatus/{attendanceStatus}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@update',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'attendanceStatus/{attendanceStatus}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'attendanceStatus/{attendanceStatus}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@show',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'attendanceStatus.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'attendanceStatus/show/{attendanceStatus}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\AttendanceStatusController@destroy',
        'namespace' => NULL,
        'prefix' => '/attendanceStatus',
        'where' => 
        array (
        ),
        'as' => 'attendanceStatus.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'salarytype.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'salarytype',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@index',
        'namespace' => NULL,
        'prefix' => '/salarytype',
        'where' => 
        array (
        ),
        'as' => 'salarytype.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'salarytype.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'salarytype/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@store',
        'namespace' => NULL,
        'prefix' => '/salarytype',
        'where' => 
        array (
        ),
        'as' => 'salarytype.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'salarytype.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'salarytype/{salaryType}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@show',
        'namespace' => NULL,
        'prefix' => '/salarytype',
        'where' => 
        array (
        ),
        'as' => 'salarytype.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'salarytype.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'salarytype/show/{salaryType}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@destroy',
        'namespace' => NULL,
        'prefix' => '/salarytype',
        'where' => 
        array (
        ),
        'as' => 'salarytype.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'salarytype.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'salarytype/{salaryType}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@edit',
        'namespace' => NULL,
        'prefix' => '/salarytype',
        'where' => 
        array (
        ),
        'as' => 'salarytype.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'salarytype.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'salarytype/{salaryType}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@update',
        'namespace' => NULL,
        'prefix' => '/salarytype',
        'where' => 
        array (
        ),
        'as' => 'salarytype.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'salarytype.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'salarytype/{salaryType}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\HRM\\SalaryTypeController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/salarytype',
        'where' => 
        array (
        ),
        'as' => 'salarytype.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidayHd.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'holidayHd',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@index',
        'namespace' => NULL,
        'prefix' => '/holidayHd',
        'where' => 
        array (
        ),
        'as' => 'holidayHd.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidayHd.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'holidayHd/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@store',
        'namespace' => NULL,
        'prefix' => '/holidayHd',
        'where' => 
        array (
        ),
        'as' => 'holidayHd.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidayhd.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'holidayHd/{holidayhd}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/holidayHd',
        'where' => 
        array (
        ),
        'as' => 'holidayhd.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidayHd.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'holidayHd/{holidayHd}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@show',
        'namespace' => NULL,
        'prefix' => '/holidayHd',
        'where' => 
        array (
        ),
        'as' => 'holidayHd.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidayHd.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'holidayHd/show/{holidayHd}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@destroy',
        'namespace' => NULL,
        'prefix' => '/holidayHd',
        'where' => 
        array (
        ),
        'as' => 'holidayHd.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidayHd.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'holidayHd/{holidayHd}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@edit',
        'namespace' => NULL,
        'prefix' => '/holidayHd',
        'where' => 
        array (
        ),
        'as' => 'holidayHd.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidayHd.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'holidayHd/{holidayHd}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayHdController@update',
        'namespace' => NULL,
        'prefix' => '/holidayHd',
        'where' => 
        array (
        ),
        'as' => 'holidayHd.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidaydt.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'holidaydt/{id}/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@create',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@create',
        'namespace' => NULL,
        'prefix' => '/holidaydt',
        'where' => 
        array (
        ),
        'as' => 'holidaydt.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidaydt.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'holidaydt/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@store',
        'namespace' => NULL,
        'prefix' => '/holidaydt',
        'where' => 
        array (
        ),
        'as' => 'holidaydt.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidaydt.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'holidaydt/{holidayDt}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@edit',
        'namespace' => NULL,
        'prefix' => '/holidaydt',
        'where' => 
        array (
        ),
        'as' => 'holidaydt.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidaydt.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'holidaydt/{holidayDt}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@update',
        'namespace' => NULL,
        'prefix' => '/holidaydt',
        'where' => 
        array (
        ),
        'as' => 'holidaydt.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'holidaydt.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'holidaydt/destroy/{holidayDt}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\HolidayDtController@destroy',
        'namespace' => NULL,
        'prefix' => '/holidaydt',
        'where' => 
        array (
        ),
        'as' => 'holidaydt.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'personalinfo.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'personalinfo',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@index',
        'namespace' => NULL,
        'prefix' => '/personalinfo',
        'where' => 
        array (
        ),
        'as' => 'personalinfo.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'personalinfo.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'personalinfo/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@store',
        'namespace' => NULL,
        'prefix' => '/personalinfo',
        'where' => 
        array (
        ),
        'as' => 'personalinfo.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'personalinfo.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'personalinfo/{PersonalInfo}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/personalinfo',
        'where' => 
        array (
        ),
        'as' => 'personalinfo.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'personalinfo.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'personalinfo/{PersonalInfo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@show',
        'namespace' => NULL,
        'prefix' => '/personalinfo',
        'where' => 
        array (
        ),
        'as' => 'personalinfo.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'personalinfo.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'personalinfo/show/{PersonalInfo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@destroy',
        'namespace' => NULL,
        'prefix' => '/personalinfo',
        'where' => 
        array (
        ),
        'as' => 'personalinfo.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'personalinfo.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'personalinfo/{PersonalInfo}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@edit',
        'namespace' => NULL,
        'prefix' => '/personalinfo',
        'where' => 
        array (
        ),
        'as' => 'personalinfo.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'personalinfo.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'personalinfo/{PersonalInfo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\PersonalInfoController@update',
        'namespace' => NULL,
        'prefix' => '/personalinfo',
        'where' => 
        array (
        ),
        'as' => 'personalinfo.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leave',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@index',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'leave/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@store',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@store',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leave/show/{leave}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@show',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@show',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.exportPdf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leave/{leave}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@exportPdf',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@exportPdf',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.exportPdf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'leave/show/{leave}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@destroy',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@destroy',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leave/{leave}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@edit',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@edit',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'leave/{leave}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@update',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@update',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'leave/confirm/{leave}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@confirm',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@confirm',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leave.fetchUserLeave' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leave/{leave}/{empid}/fetchUserLeave',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\LeaveController@fetchUserLeave',
        'controller' => 'App\\Http\\Controllers\\HRM\\LeaveController@fetchUserLeave',
        'namespace' => NULL,
        'prefix' => '/leave',
        'where' => 
        array (
        ),
        'as' => 'leave.fetchUserLeave',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@index',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@index',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.EmpInfoReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports/empreport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@EmpInfoReport',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@EmpInfoReport',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.EmpInfoReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.DailyAttendance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports/DailyAttendance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@DailyAttendance',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@DailyAttendance',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.DailyAttendance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.DailyAttendanceReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports/DailyAttendanceReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@DailyAttendanceReport',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@DailyAttendanceReport',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.DailyAttendanceReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.EmployeeAttendance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports/EmployeeAttendance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@EmployeeAttendance',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@EmployeeAttendance',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.EmployeeAttendance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.EmployeeAttendanceReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports/EmployeeAttendanceReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@EmployeeAttendanceReport',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@EmployeeAttendanceReport',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.EmployeeAttendanceReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.MonthlyAttendance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports/MonthlyAttendance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@MonthlyAttendance',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@MonthlyAttendance',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.MonthlyAttendance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hrreports.MonthlyAttendanceReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'hrreports/MonthlyAttendanceReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\HRM\\HRreportsController@MonthlyAttendanceReport',
        'controller' => 'App\\Http\\Controllers\\HRM\\HRreportsController@MonthlyAttendanceReport',
        'namespace' => NULL,
        'prefix' => '/hrreports',
        'where' => 
        array (
        ),
        'as' => 'hrreports.MonthlyAttendanceReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partnerbranch.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partnerbranch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@index',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@index',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partnerbranch.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partnerbranch.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partnerbranch/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@create',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@create',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partnerbranch.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partnerbranch.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'partnerbranch/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@store',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@store',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partnerbranch.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partnerbranch.partnerBranch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'partnerbranch/PartnerBranch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@PartnerBranch',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@PartnerBranch',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partnerbranch.partnerBranch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partnerbranch.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'partnerbranch/{PartnerBranch}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partnerbranch.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partnerbranch.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partnerbranch/{PartnerBranch}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@edit',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@edit',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partnerbranch.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.partnerbranch' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'partnerbranch/{PartnerBranch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@update',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@update',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partner.partnerbranch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partnerbranch.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'partnerbranch/show/{PartnerBranch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@destroy',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerBranchController@destroy',
        'namespace' => NULL,
        'prefix' => '/partnerbranch',
        'where' => 
        array (
        ),
        'as' => 'partnerbranch.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerController@index',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerController@index',
        'namespace' => NULL,
        'prefix' => '/partner',
        'where' => 
        array (
        ),
        'as' => 'partner.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerController@create',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerController@create',
        'namespace' => NULL,
        'prefix' => '/partner',
        'where' => 
        array (
        ),
        'as' => 'partner.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'partner/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerController@store',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerController@store',
        'namespace' => NULL,
        'prefix' => '/partner',
        'where' => 
        array (
        ),
        'as' => 'partner.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'partner/{partner}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/partner',
        'where' => 
        array (
        ),
        'as' => 'partner.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/{partner}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerController@edit',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerController@edit',
        'namespace' => NULL,
        'prefix' => '/partner',
        'where' => 
        array (
        ),
        'as' => 'partner.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'partner/{partner}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerController@update',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerController@update',
        'namespace' => NULL,
        'prefix' => '/partner',
        'where' => 
        array (
        ),
        'as' => 'partner.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'partner.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'partner/show/{partner}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerController@destroy',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerController@destroy',
        'namespace' => NULL,
        'prefix' => '/partner',
        'where' => 
        array (
        ),
        'as' => 'partner.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.application' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/aplication',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@aplication',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@aplication',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.application',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.product' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/product',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@product',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@product',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.product',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.branch' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/branch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@branch',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@branch',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.branch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.branchStore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'partner/activities/{partner}/branch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@branchStore',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@branchStore',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.branchStore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.branchDelete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'partner/activities/{partner}/branch/show/{partnerBranch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@branchDelete',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@branchDelete',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.branchDelete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.aggrements' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/aggrements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@aggrements',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@aggrements',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.aggrements',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.contacts' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/contacts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@contacts',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@contacts',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.contacts',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.notes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/notes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@notes',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@notes',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.notes',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.documents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@documents',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@documents',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.documents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.appoinments' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/appoinments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@appoinments',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@appoinments',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.appoinments',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.accounts' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@accounts',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@accounts',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.accounts',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.conversations' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/conversations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@conversations',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@conversations',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.conversations',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.tasks' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/tasks',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@tasks',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@tasks',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.tasks',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.others' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/others',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@others',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@others',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.others',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'PartnerActivities.promotions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'partner/activities/{partner}/promotions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@promotions',
        'controller' => 'App\\Http\\Controllers\\Partner\\PartnerActivities@promotions',
        'namespace' => NULL,
        'prefix' => '/partner/activities/{partner}',
        'where' => 
        array (
        ),
        'as' => 'PartnerActivities.promotions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentStage.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studentStage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentStageController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentStageController@index',
        'namespace' => NULL,
        'prefix' => '/studentStage',
        'where' => 
        array (
        ),
        'as' => 'studentStage.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentStage.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studentStage/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentStageController@create',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentStageController@create',
        'namespace' => NULL,
        'prefix' => '/studentStage',
        'where' => 
        array (
        ),
        'as' => 'studentStage.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentStage.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studentStage/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentStageController@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentStageController@store',
        'namespace' => NULL,
        'prefix' => '/studentStage',
        'where' => 
        array (
        ),
        'as' => 'studentStage.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentStage.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'studentStage/{studentStage}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentStageController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentStageController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/studentStage',
        'where' => 
        array (
        ),
        'as' => 'studentStage.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentStage.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studentStage/{studentStage}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentStageController@edit',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentStageController@edit',
        'namespace' => NULL,
        'prefix' => '/studentStage',
        'where' => 
        array (
        ),
        'as' => 'studentStage.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentStage.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'studentStage/{studentStage}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentStageController@update',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentStageController@update',
        'namespace' => NULL,
        'prefix' => '/studentStage',
        'where' => 
        array (
        ),
        'as' => 'studentStage.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentStage.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'studentStage/show/{studentStage}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentStageController@destroy',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentStageController@destroy',
        'namespace' => NULL,
        'prefix' => '/studentStage',
        'where' => 
        array (
        ),
        'as' => 'studentStage.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentSource.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studentSource',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentSourceController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentSourceController@index',
        'namespace' => NULL,
        'prefix' => '/studentSource',
        'where' => 
        array (
        ),
        'as' => 'studentSource.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentSource.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studentSource/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentSourceController@create',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentSourceController@create',
        'namespace' => NULL,
        'prefix' => '/studentSource',
        'where' => 
        array (
        ),
        'as' => 'studentSource.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentSource.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studentSource/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentSourceController@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentSourceController@store',
        'namespace' => NULL,
        'prefix' => '/studentSource',
        'where' => 
        array (
        ),
        'as' => 'studentSource.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentSource.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'studentSource/{studentSource}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentSourceController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentSourceController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/studentSource',
        'where' => 
        array (
        ),
        'as' => 'studentSource.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentSource.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studentSource/{studentSource}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentSourceController@edit',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentSourceController@edit',
        'namespace' => NULL,
        'prefix' => '/studentSource',
        'where' => 
        array (
        ),
        'as' => 'studentSource.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentSource.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'studentSource/{studentSource}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentSourceController@update',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentSourceController@update',
        'namespace' => NULL,
        'prefix' => '/studentSource',
        'where' => 
        array (
        ),
        'as' => 'studentSource.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentSource.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'studentSource/show/{studentSource}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentSourceController@destroy',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentSourceController@destroy',
        'namespace' => NULL,
        'prefix' => '/studentSource',
        'where' => 
        array (
        ),
        'as' => 'studentSource.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@index',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.lead' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/lead',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@lead',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@lead',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.lead',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.pending' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/pending',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@pending',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@pending',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.pending',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.prospect' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/prospect',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@prospect',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@prospect',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.prospect',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.onBoard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/onBoard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@onBoard',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@onBoard',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.onBoard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.archive' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@archive',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@archive',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.archive',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@create',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@create',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@store',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/{student}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/{student}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@edit',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@edit',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/{student}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@update',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@update',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'student.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'student/show/{student}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentController@destroy',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentController@destroy',
        'namespace' => NULL,
        'prefix' => '/student',
        'where' => 
        array (
        ),
        'as' => 'student.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentActivities.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/allactivities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/allactivities',
        'where' => 
        array (
        ),
        'as' => 'studentActivities.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentActivities.updateArchive' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/activities/{student}/allactivities/status/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@updateArchive',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@updateArchive',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/allactivities',
        'where' => 
        array (
        ),
        'as' => 'studentActivities.updateArchive',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentActivities.confirmonBoard' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/activities/{student}/allactivities/status/transfer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@confirmonBoard',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@confirmonBoard',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/allactivities',
        'where' => 
        array (
        ),
        'as' => 'studentActivities.confirmonBoard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentActivities.updateRate' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/activities/{student}/allactivities/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@updateRate',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@updateRate',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/allactivities',
        'where' => 
        array (
        ),
        'as' => 'studentActivities.updateRate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentActivities.updateAssignee' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/allactivities/assignee',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@updateAssignee',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentActivitiesController@updateAssignee',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/allactivities',
        'where' => 
        array (
        ),
        'as' => 'studentActivities.updateAssignee',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.partner' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{partner}/partner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@partner',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@partner',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.partner',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.product' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{product}/{partner}/product',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@product',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@product',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.product',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/application/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@store',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@edit',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@edit',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@update',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@update',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'student/activities/{student}/application/show/{studentApplication}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@destroy',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@destroy',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.appActivities' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/activities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@appActivities',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@appActivities',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.appActivities',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.documentApplication' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/document',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@documentApplication',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@documentApplication',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.documentApplication',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.documentNextStep' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/document/next',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@documentNextStep',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@documentNextStep',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.documentNextStep',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.documentBackStep' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/document/back',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@documentBackStep',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@documentBackStep',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.documentBackStep',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.updateCheckList' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/document/checklist',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@updateCheckList',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@updateCheckList',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.updateCheckList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.docAppStore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/document/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@docAppStore',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@docAppStore',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.docAppStore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.docAppDownload' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/document/{document}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@downloadAppDocument',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@downloadAppDocument',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.docAppDownload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.docAppDelete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/document/{document}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@deleteAppDocument',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@deleteAppDocument',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.docAppDelete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.notesApplication' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/notes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@notesApplication',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@notesApplication',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.notesApplication',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.tasksApplication' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/tasks',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@tasksApplication',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@tasksApplication',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.tasksApplication',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentApplication.paymentApplication' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/application/{studentApplication}/payment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@paymentApplication',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentApplicationController@paymentApplication',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/application',
        'where' => 
        array (
        ),
        'as' => 'studentApplication.paymentApplication',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/interestedservice',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.partner' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/interestedservice/{partner}/partner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@partner',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@partner',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.partner',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.product' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/interestedservice/{product}/{partner}/product',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@product',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@product',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.product',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/interestedservice/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@store',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/interestedservice/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@create',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@create',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.editApplication' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/interestedservice/{studentInService}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@editApplication',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@editApplication',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.editApplication',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/activities/{student}/interestedservice/{studentInService}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@update',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@update',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentInService.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'student/activities/{student}/interestedservice/show/{studentInService}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@destroy',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentInServiceController@destroy',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/interestedservice',
        'where' => 
        array (
        ),
        'as' => 'studentInService.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentDocument.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/document',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentDocument@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentDocument@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/document',
        'where' => 
        array (
        ),
        'as' => 'studentDocument.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAppointements.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/appoinments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAppointements@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAppointements@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/appoinments',
        'where' => 
        array (
        ),
        'as' => 'studentAppointements.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAppointements.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/appoinments/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAppointements@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAppointements@store',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/appoinments',
        'where' => 
        array (
        ),
        'as' => 'studentAppointements.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentNotes.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/notes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentNotes@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentNotes@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/notes',
        'where' => 
        array (
        ),
        'as' => 'studentNotes.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentQuotations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/quotations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentQuotations@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentQuotations@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/quotations',
        'where' => 
        array (
        ),
        'as' => 'studentQuotations.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentQuotations.fetchData' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/quotations/{product}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentQuotations@fetchData',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentQuotations@fetchData',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/quotations',
        'where' => 
        array (
        ),
        'as' => 'studentQuotations.fetchData',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentQuotations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/quotations/general',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentQuotations@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentQuotations@store',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/quotations',
        'where' => 
        array (
        ),
        'as' => 'studentQuotations.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentQuotations.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'student/activities/{student}/quotations/{product}/confirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentQuotations@confirm',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentQuotations@confirm',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/quotations',
        'where' => 
        array (
        ),
        'as' => 'studentQuotations.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentQuotations.destory' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/quotations/{product}/destory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentQuotations@destory',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentQuotations@destory',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/quotations',
        'where' => 
        array (
        ),
        'as' => 'studentQuotations.destory',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentQuotations.exportPdfGeneral' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/quotations/{product}/{quoatation}/exportPdfGeneral',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentQuotations@exportPdfGeneral',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentQuotations@exportPdfGeneral',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/quotations',
        'where' => 
        array (
        ),
        'as' => 'studentQuotations.exportPdfGeneral',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentQuotations.exportPdfApproved' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/quotations/{product}/{quoatation}/exportPdfApproved',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentQuotations@exportPdfApproved',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentQuotations@exportPdfApproved',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/quotations',
        'where' => 
        array (
        ),
        'as' => 'studentQuotations.exportPdfApproved',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/accounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.return' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/accounts/return',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@return',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@return',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.return',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/accounts/{quotation}/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@create',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@create',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.fetchMR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/accounts/{mrid}/fetchMR',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@fetchMR',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@fetchMR',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.fetchMR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.storeReturn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/accounts/storeReturn',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@storeReturn',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@storeReturn',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.storeReturn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.fetchSR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/accounts/{srid}/fetchSR',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@fetchSR',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@fetchSR',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.fetchSR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.returnCancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/accounts/{confirm}/returnCancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@returnCancel',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@returnCancel',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.returnCancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.returnConfirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/accounts/{confirm}/returnConfirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@returnConfirm',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@returnConfirm',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.returnConfirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/accounts/{quotation}/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@store',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.onView' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/accounts/{confirm}/onView',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onView',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onView',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.onView',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.onDelete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/accounts/{confirm}/onDelete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onDelete',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onDelete',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.onDelete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.onConfirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/accounts/{confirm}/onConfirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onConfirm',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onConfirm',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.onConfirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentAccounts.onReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/accounts/{confirm}/onReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onReport',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentAccounts@onReport',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/accounts',
        'where' => 
        array (
        ),
        'as' => 'studentAccounts.onReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentConversations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/conversations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentConversations@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentConversations@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/conversations',
        'where' => 
        array (
        ),
        'as' => 'studentConversations.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentConversations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/conversations/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentConversations@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentConversations@store',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/conversations',
        'where' => 
        array (
        ),
        'as' => 'studentConversations.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentConversations.fetchData' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/conversations/{conversation}/fetchData',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentConversations@fetchData',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentConversations@fetchData',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/conversations',
        'where' => 
        array (
        ),
        'as' => 'studentConversations.fetchData',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentTasks.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/tasks',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentTasks@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentTasks@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/tasks',
        'where' => 
        array (
        ),
        'as' => 'studentTasks.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentEducations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/educations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentEducations@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentEducations@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/educations',
        'where' => 
        array (
        ),
        'as' => 'studentEducations.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentCheckin.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'student/activities/{student}/checkin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentCheckLogController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentCheckLogController@index',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/checkin',
        'where' => 
        array (
        ),
        'as' => 'studentCheckin.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentCheckin.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/checkin/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentCheckLogController@store',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentCheckLogController@store',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/checkin',
        'where' => 
        array (
        ),
        'as' => 'studentCheckin.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studentCheckin.checkOut' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'student/activities/{student}/checkin/checkOut',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentCheckLogController@checkOut',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentCheckLogController@checkOut',
        'namespace' => NULL,
        'prefix' => '/student/activities/{student}/checkin',
        'where' => 
        array (
        ),
        'as' => 'studentCheckin.checkOut',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leadreports.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leadreports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentReportController@index',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentReportController@index',
        'namespace' => NULL,
        'prefix' => '/leadreports',
        'where' => 
        array (
        ),
        'as' => 'leadreports.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leadreports.studentLedger' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leadreports/ledger',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentLedger',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentLedger',
        'namespace' => NULL,
        'prefix' => '/leadreports',
        'where' => 
        array (
        ),
        'as' => 'leadreports.studentLedger',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leadreports.studentLedgerReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leadreports/ledger/{student}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentLedgerReport',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentLedgerReport',
        'namespace' => NULL,
        'prefix' => '/leadreports',
        'where' => 
        array (
        ),
        'as' => 'leadreports.studentLedgerReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leadreports.studentRevenue' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leadreports/revenue',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentRevenue',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentRevenue',
        'namespace' => NULL,
        'prefix' => '/leadreports',
        'where' => 
        array (
        ),
        'as' => 'leadreports.studentRevenue',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leadreports.studentRevenueReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leadreports/revenue/{formdate}/{todate}/{isAdmin}/{employee?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentRevenueReport',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentReportController@studentRevenueReport',
        'namespace' => NULL,
        'prefix' => '/leadreports',
        'where' => 
        array (
        ),
        'as' => 'leadreports.studentRevenueReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'leadreports.MonthlyEmpLeadReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'leadreports/emp/{formdate}/{todate}/{isAdmin}/{employee?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Student\\StudentReportController@MonthlyEmpLeadReport',
        'controller' => 'App\\Http\\Controllers\\Student\\StudentReportController@MonthlyEmpLeadReport',
        'namespace' => NULL,
        'prefix' => '/leadreports',
        'where' => 
        array (
        ),
        'as' => 'leadreports.MonthlyEmpLeadReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'product.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductController@index',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductController@index',
        'namespace' => NULL,
        'prefix' => '/product',
        'where' => 
        array (
        ),
        'as' => 'product.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'product.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductController@create',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductController@create',
        'namespace' => NULL,
        'prefix' => '/product',
        'where' => 
        array (
        ),
        'as' => 'product.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'product.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'product/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductController@store',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductController@store',
        'namespace' => NULL,
        'prefix' => '/product',
        'where' => 
        array (
        ),
        'as' => 'product.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'product.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'product/{product}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/product',
        'where' => 
        array (
        ),
        'as' => 'product.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'product.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/{product}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductController@edit',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductController@edit',
        'namespace' => NULL,
        'prefix' => '/product',
        'where' => 
        array (
        ),
        'as' => 'product.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'product.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'product/{product}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductController@update',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductController@update',
        'namespace' => NULL,
        'prefix' => '/product',
        'where' => 
        array (
        ),
        'as' => 'product.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'product.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'product/show/{product}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductController@destroy',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductController@destroy',
        'namespace' => NULL,
        'prefix' => '/product',
        'where' => 
        array (
        ),
        'as' => 'product.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.application' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/aplication',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@aplication',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@aplication',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.application',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.documents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@documents',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@documents',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.documents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.fees' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/fees',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@fees',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@fees',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.fees',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.storefess' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'product/activities/{product}/fees',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@storefess',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@storefess',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.storefess',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.updatefees' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'product/activities/{product}/fees/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@updatefees',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@updatefees',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.updatefees',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.feeDelete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'product/activities/{product}/fees/show/{productFeesHd}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@feeDelete',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@feeDelete',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.feeDelete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.requirement' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/requirement',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@requirement',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@requirement',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.requirement',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.editRequirement' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/requirement/{requirement}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@editRequirement',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@editRequirement',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.editRequirement',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.storeRequirement' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'product/activities/{product}/requirement',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@storeRequirement',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@storeRequirement',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.storeRequirement',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.editRequirementEng' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/requirementEng/{requirementEng}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@editRequirementEng',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@editRequirementEng',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.editRequirementEng',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.storeRequirementEng' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'product/activities/{product}/requirementEng',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@storeRequirementEng',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@storeRequirementEng',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.storeRequirementEng',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.editRequirementOthers' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/requirementOthers/{requirementOthers}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@editRequirementOthers',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@editRequirementOthers',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.editRequirementOthers',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.storeRequirementOthers' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'product/activities/{product}/requirementOthers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@storeRequirementOthers',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@storeRequirementOthers',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.storeRequirementOthers',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.others' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/others',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@others',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@others',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.others',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productActivities.promotions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'product/activities/{product}/promotions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Product\\ProductActivities@promotions',
        'controller' => 'App\\Http\\Controllers\\Product\\ProductActivities@promotions',
        'namespace' => NULL,
        'prefix' => '/product/activities/{product}',
        'where' => 
        array (
        ),
        'as' => 'productActivities.promotions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'imports.showImportForm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'imports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ExcelImportController@showImportForm',
        'controller' => 'App\\Http\\Controllers\\Default\\ExcelImportController@showImportForm',
        'namespace' => NULL,
        'prefix' => '/imports',
        'where' => 
        array (
        ),
        'as' => 'imports.showImportForm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'imports.import' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'imports/import',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ExcelImportController@import',
        'controller' => 'App\\Http\\Controllers\\Default\\ExcelImportController@import',
        'namespace' => NULL,
        'prefix' => '/imports',
        'where' => 
        array (
        ),
        'as' => 'imports.import',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'imports.downloadTemplate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'imports/downloadTemplate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ExcelImportController@downloadTemplate',
        'controller' => 'App\\Http\\Controllers\\Default\\ExcelImportController@downloadTemplate',
        'namespace' => NULL,
        'prefix' => '/imports',
        'where' => 
        array (
        ),
        'as' => 'imports.downloadTemplate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'transaction.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'transaction',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\TransactionController@index',
        'controller' => 'App\\Http\\Controllers\\Default\\TransactionController@index',
        'namespace' => NULL,
        'prefix' => '/transaction',
        'where' => 
        array (
        ),
        'as' => 'transaction.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'transaction.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'transaction/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\TransactionController@store',
        'controller' => 'App\\Http\\Controllers\\Default\\TransactionController@store',
        'namespace' => NULL,
        'prefix' => '/transaction',
        'where' => 
        array (
        ),
        'as' => 'transaction.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'transaction.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'transaction/{transaction}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\TransactionController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Default\\TransactionController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/transaction',
        'where' => 
        array (
        ),
        'as' => 'transaction.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'transaction.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'transaction/{transaction}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\TransactionController@show',
        'controller' => 'App\\Http\\Controllers\\Default\\TransactionController@show',
        'namespace' => NULL,
        'prefix' => '/transaction',
        'where' => 
        array (
        ),
        'as' => 'transaction.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'transaction.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'transaction/show/{transaction}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\TransactionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Default\\TransactionController@destroy',
        'namespace' => NULL,
        'prefix' => '/transaction',
        'where' => 
        array (
        ),
        'as' => 'transaction.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'transaction.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'transaction/{transaction}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\TransactionController@edit',
        'controller' => 'App\\Http\\Controllers\\Default\\TransactionController@edit',
        'namespace' => NULL,
        'prefix' => '/transaction',
        'where' => 
        array (
        ),
        'as' => 'transaction.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'transaction.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'transaction/{transaction}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\TransactionController@update',
        'controller' => 'App\\Http\\Controllers\\Default\\TransactionController@update',
        'namespace' => NULL,
        'prefix' => '/transaction',
        'where' => 
        array (
        ),
        'as' => 'transaction.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.studentArchive' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'approval/studentArchive/{student}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@studentArchive',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@studentArchive',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.studentArchive',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.studentTransfer' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'approval/studentTransfer/{student}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@studentTransfer',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@studentTransfer',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.studentTransfer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.studentOnBoard' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'approval/studentOnBoard/{student}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@studentOnBoard',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@studentOnBoard',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.studentOnBoard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.leaveRequest' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'approval/{leave}/leaveRequest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@leaveRequest',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@leaveRequest',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.leaveRequest',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.leaveApproved' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'approval/{leave}/leaveApproved',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@leaveApproved',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@leaveApproved',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.leaveApproved',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.leaveCancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'approval/{leave}/leaveCancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@leaveCancel',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@leaveCancel',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.leaveCancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.QuoattionView' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'approval/{quotation}/QuoattionView',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@QuoattionView',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@QuoattionView',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.QuoattionView',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.QuoattionConfirm' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'approval/{quotation}/QuoattionConfirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@QuoattionConfirm',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@QuoattionConfirm',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.QuoattionConfirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.QuoattionCancel' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'approval/{quotation}/QuoattionCancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@QuoattionCancel',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@QuoattionCancel',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.QuoattionCancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.ReturnConfirm' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'approval/{return}/ReturnConfirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@ReturnConfirm',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@ReturnConfirm',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.ReturnConfirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'approval.ReturnCancel' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'approval/{return}/ReturnCancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@ReturnCancel',
        'controller' => 'App\\Http\\Controllers\\Default\\ApprovalRequestController@ReturnCancel',
        'namespace' => NULL,
        'prefix' => '/approval',
        'where' => 
        array (
        ),
        'as' => 'approval.ReturnCancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'general',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@index',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@index',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.patnersetup' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'general/patnersetup',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetup',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetup',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.patnersetup',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.productsetup' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'general/productsetup',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@productsetup',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@productsetup',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.productsetup',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'general/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@store',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@store',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'general/{general}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@edit',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@edit',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'general/{general}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@update',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@update',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'general/{general}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'general/{general}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@show',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@show',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'general/show/{general}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@destroy',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@destroy',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.patnersetupstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'general/patnersetup',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetupstore',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetupstore',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.patnersetupstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.patnersetupUpdateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'general/{patnersetup}/patnersetupstatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetupUpdateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetupUpdateStatus',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.patnersetupUpdateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'patnersetup.patnersetupdestroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'general/patnersetup/show/{patnersetup}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetupdestroy',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@patnersetupdestroy',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'patnersetup.patnersetupdestroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.productsetuppstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'general/productsetup',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@productsetuppstore',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@productsetuppstore',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.productsetuppstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'general.producttypeUpdateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'general/{productsetup}/producttypeupstatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@producttypeUpdateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@producttypeUpdateStatus',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'general.producttypeUpdateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'productsetup.productsetupdestroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'general/productsetup/show/{productsetup}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@productsetupdestroy',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\GeneralController@productsetupdestroy',
        'namespace' => NULL,
        'prefix' => '/general',
        'where' => 
        array (
        ),
        'as' => 'productsetup.productsetupdestroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workflow.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'workflow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@index',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@index',
        'namespace' => NULL,
        'prefix' => '/workflow',
        'where' => 
        array (
        ),
        'as' => 'workflow.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workflow.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'workflow/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@store',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@store',
        'namespace' => NULL,
        'prefix' => '/workflow',
        'where' => 
        array (
        ),
        'as' => 'workflow.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workflow.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'workflow/{workflow}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/workflow',
        'where' => 
        array (
        ),
        'as' => 'workflow.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workflow.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'workflow/{workflow}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@show',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@show',
        'namespace' => NULL,
        'prefix' => '/workflow',
        'where' => 
        array (
        ),
        'as' => 'workflow.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workflow.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'workflow/show/{workflow}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@destroy',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@destroy',
        'namespace' => NULL,
        'prefix' => '/workflow',
        'where' => 
        array (
        ),
        'as' => 'workflow.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workflow.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'workflow/{workflow}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@edit',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@edit',
        'namespace' => NULL,
        'prefix' => '/workflow',
        'where' => 
        array (
        ),
        'as' => 'workflow.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'workflow.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'workflow/{workflow}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@update',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WorkflowController@update',
        'namespace' => NULL,
        'prefix' => '/workflow',
        'where' => 
        array (
        ),
        'as' => 'workflow.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documentlist.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'documentlist/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentCheckController@index',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentCheckController@index',
        'namespace' => NULL,
        'prefix' => '/documentlist',
        'where' => 
        array (
        ),
        'as' => 'documentlist.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documentlist.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'documentlist/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentCheckController@store',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentCheckController@store',
        'namespace' => NULL,
        'prefix' => '/documentlist',
        'where' => 
        array (
        ),
        'as' => 'documentlist.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documenttype.adddoctype' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'documentlist/{id}/adddoctype',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentCheckController@adddoctype',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentCheckController@adddoctype',
        'namespace' => NULL,
        'prefix' => '/documentlist',
        'where' => 
        array (
        ),
        'as' => 'documenttype.adddoctype',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documenttype.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'documenttype',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@index',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@index',
        'namespace' => NULL,
        'prefix' => '/documenttype',
        'where' => 
        array (
        ),
        'as' => 'documenttype.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documenttype.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'documenttype/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@store',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@store',
        'namespace' => NULL,
        'prefix' => '/documenttype',
        'where' => 
        array (
        ),
        'as' => 'documenttype.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documenttype.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'documenttype/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/documenttype',
        'where' => 
        array (
        ),
        'as' => 'documenttype.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documenttype.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'documenttype/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@edit',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@edit',
        'namespace' => NULL,
        'prefix' => '/documenttype',
        'where' => 
        array (
        ),
        'as' => 'documenttype.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'documenttype.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'documenttype/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@update',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\WDocumentTypeController@update',
        'namespace' => NULL,
        'prefix' => '/documenttype',
        'where' => 
        array (
        ),
        'as' => 'documenttype.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'fees.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'fees',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@index',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@index',
        'namespace' => NULL,
        'prefix' => '/fees',
        'where' => 
        array (
        ),
        'as' => 'fees.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'fees.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'fees/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@store',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@store',
        'namespace' => NULL,
        'prefix' => '/fees',
        'where' => 
        array (
        ),
        'as' => 'fees.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'fees.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'fees/{fees}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/fees',
        'where' => 
        array (
        ),
        'as' => 'fees.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'fees.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'fees/{fees}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@show',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@show',
        'namespace' => NULL,
        'prefix' => '/fees',
        'where' => 
        array (
        ),
        'as' => 'fees.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'fees.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'fees/show/{fees}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@destroy',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@destroy',
        'namespace' => NULL,
        'prefix' => '/fees',
        'where' => 
        array (
        ),
        'as' => 'fees.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'fees.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'fees/{fees}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@edit',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@edit',
        'namespace' => NULL,
        'prefix' => '/fees',
        'where' => 
        array (
        ),
        'as' => 'fees.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'fees.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'fees/{fees}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@update',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\FeesController@update',
        'namespace' => NULL,
        'prefix' => '/fees',
        'where' => 
        array (
        ),
        'as' => 'fees.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'installment.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'installment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@index',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@index',
        'namespace' => NULL,
        'prefix' => '/installment',
        'where' => 
        array (
        ),
        'as' => 'installment.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'installment.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'installment/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@store',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@store',
        'namespace' => NULL,
        'prefix' => '/installment',
        'where' => 
        array (
        ),
        'as' => 'installment.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'installment.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'installment/{installment}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/installment',
        'where' => 
        array (
        ),
        'as' => 'installment.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'installment.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'installment/{installment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@show',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@show',
        'namespace' => NULL,
        'prefix' => '/installment',
        'where' => 
        array (
        ),
        'as' => 'installment.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'installment.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'installment/show/{installment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@destroy',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@destroy',
        'namespace' => NULL,
        'prefix' => '/installment',
        'where' => 
        array (
        ),
        'as' => 'installment.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'installment.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'installment/{installment}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@edit',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@edit',
        'namespace' => NULL,
        'prefix' => '/installment',
        'where' => 
        array (
        ),
        'as' => 'installment.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'installment.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'installment/{installment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@update',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\InstallmentController@update',
        'namespace' => NULL,
        'prefix' => '/installment',
        'where' => 
        array (
        ),
        'as' => 'installment.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'academic.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'academics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@index',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@index',
        'namespace' => NULL,
        'prefix' => '/academics',
        'where' => 
        array (
        ),
        'as' => 'academic.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'academic.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'academics/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@store',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@store',
        'namespace' => NULL,
        'prefix' => '/academics',
        'where' => 
        array (
        ),
        'as' => 'academic.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'academic.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'academics/{academic}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/academics',
        'where' => 
        array (
        ),
        'as' => 'academic.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'academic.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'academics/{academic}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@show',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@show',
        'namespace' => NULL,
        'prefix' => '/academics',
        'where' => 
        array (
        ),
        'as' => 'academic.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'academic.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'academics/show/{academic}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@destroy',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@destroy',
        'namespace' => NULL,
        'prefix' => '/academics',
        'where' => 
        array (
        ),
        'as' => 'academic.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'academic.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'academics/{academic}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@edit',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@edit',
        'namespace' => NULL,
        'prefix' => '/academics',
        'where' => 
        array (
        ),
        'as' => 'academic.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'academic.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'academics/{academic}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@update',
        'controller' => 'App\\Http\\Controllers\\AgencySetting\\AcademicController@update',
        'namespace' => NULL,
        'prefix' => '/academics',
        'where' => 
        array (
        ),
        'as' => 'academic.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7LTNfkNsJ8zGijpe' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
        2 => 'POST',
        3 => 'PUT',
        4 => 'PATCH',
        5 => 'DELETE',
        6 => 'OPTIONS',
      ),
      'uri' => 'settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'isBanned',
          3 => 'UserActivity',
        ),
        'uses' => '\\Illuminate\\Routing\\RedirectController@__invoke',
        'controller' => '\\Illuminate\\Routing\\RedirectController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::7LTNfkNsJ8zGijpe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
        'destination' => '/settings/profile',
        'status' => 302,
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'isBanned',
          3 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Settings\\ProfileController@edit',
        'controller' => 'App\\Http\\Controllers\\Settings\\ProfileController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'profile.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'settings/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'isBanned',
          3 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Settings\\ProfileController@update',
        'controller' => 'App\\Http\\Controllers\\Settings\\ProfileController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'profile.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'settings/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'isBanned',
          3 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Settings\\ProfileController@destroy',
        'controller' => 'App\\Http\\Controllers\\Settings\\ProfileController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'profile.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'isBanned',
          3 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Settings\\PasswordController@edit',
        'controller' => 'App\\Http\\Controllers\\Settings\\PasswordController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'settings/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'isBanned',
          3 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Settings\\PasswordController@update',
        'controller' => 'App\\Http\\Controllers\\Settings\\PasswordController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'appearance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/appearance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'isBanned',
          3 => 'UserActivity',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:83:"function () {
        return \\Inertia\\Inertia::render(\'settings/Appearance\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005d20000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'appearance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accsetting.GroupOne' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accountssetting',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\AccountsSetup@index',
        'controller' => 'App\\Http\\Controllers\\Accounts\\AccountsSetup@index',
        'namespace' => NULL,
        'prefix' => '/accountssetting',
        'where' => 
        array (
        ),
        'as' => 'accsetting.GroupOne',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accsetting.GroupTwo' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accountssetting/{GroupOne}/Grouptwo',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\AccountsSetup@Grouptwo',
        'controller' => 'App\\Http\\Controllers\\Accounts\\AccountsSetup@Grouptwo',
        'namespace' => NULL,
        'prefix' => '/accountssetting',
        'where' => 
        array (
        ),
        'as' => 'accsetting.GroupTwo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accsetting.GroupThree' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'accountssetting/{GroupOne}/{GroupTwo}/Groupthree',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\AccountsSetup@Groupthree',
        'controller' => 'App\\Http\\Controllers\\Accounts\\AccountsSetup@Groupthree',
        'namespace' => NULL,
        'prefix' => '/accountssetting',
        'where' => 
        array (
        ),
        'as' => 'accsetting.GroupThree',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupOne.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'groupOne/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@store',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@store',
        'namespace' => NULL,
        'prefix' => '/groupOne',
        'where' => 
        array (
        ),
        'as' => 'GroupOne.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupOne.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'groupOne/{groupOne}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@edit',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@edit',
        'namespace' => NULL,
        'prefix' => '/groupOne',
        'where' => 
        array (
        ),
        'as' => 'GroupOne.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupOne.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'groupOne/{groupOne}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@update',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@update',
        'namespace' => NULL,
        'prefix' => '/groupOne',
        'where' => 
        array (
        ),
        'as' => 'GroupOne.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupOne.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'groupOne/{groupOne}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/groupOne',
        'where' => 
        array (
        ),
        'as' => 'GroupOne.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupOne.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'groupOne/{groupOne}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@show',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@show',
        'namespace' => NULL,
        'prefix' => '/groupOne',
        'where' => 
        array (
        ),
        'as' => 'GroupOne.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupOne.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'groupOne/show/{groupOne}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@destroy',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupOneController@destroy',
        'namespace' => NULL,
        'prefix' => '/groupOne',
        'where' => 
        array (
        ),
        'as' => 'GroupOne.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupTwo.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'Grouptwo/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@store',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@store',
        'namespace' => NULL,
        'prefix' => '/Grouptwo',
        'where' => 
        array (
        ),
        'as' => 'GroupTwo.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupTwo.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'Grouptwo/{groupTwo}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@edit',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@edit',
        'namespace' => NULL,
        'prefix' => '/Grouptwo',
        'where' => 
        array (
        ),
        'as' => 'GroupTwo.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupTwo.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'Grouptwo/{groupTwo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@update',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@update',
        'namespace' => NULL,
        'prefix' => '/Grouptwo',
        'where' => 
        array (
        ),
        'as' => 'GroupTwo.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupTwo.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'Grouptwo/{groupTwo}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/Grouptwo',
        'where' => 
        array (
        ),
        'as' => 'GroupTwo.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupTwo.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'Grouptwo/{groupTwo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@show',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@show',
        'namespace' => NULL,
        'prefix' => '/Grouptwo',
        'where' => 
        array (
        ),
        'as' => 'GroupTwo.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupTwo.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'Grouptwo/show/{groupTwo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@destroy',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupTwoController@destroy',
        'namespace' => NULL,
        'prefix' => '/Grouptwo',
        'where' => 
        array (
        ),
        'as' => 'GroupTwo.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupThree.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'Groupthree/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@store',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@store',
        'namespace' => NULL,
        'prefix' => '/Groupthree',
        'where' => 
        array (
        ),
        'as' => 'GroupThree.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupThree.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'Groupthree/{groupThree}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@edit',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@edit',
        'namespace' => NULL,
        'prefix' => '/Groupthree',
        'where' => 
        array (
        ),
        'as' => 'GroupThree.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupThree.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'Groupthree/{groupThree}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@update',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@update',
        'namespace' => NULL,
        'prefix' => '/Groupthree',
        'where' => 
        array (
        ),
        'as' => 'GroupThree.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupThree.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'Groupthree/{groupThree}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/Groupthree',
        'where' => 
        array (
        ),
        'as' => 'GroupThree.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupThree.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'Groupthree/{groupThree}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@show',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@show',
        'namespace' => NULL,
        'prefix' => '/Groupthree',
        'where' => 
        array (
        ),
        'as' => 'GroupThree.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'GroupThree.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'Groupthree/show/{groupThree}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@destroy',
        'controller' => 'App\\Http\\Controllers\\Accounts\\GroupThreeController@destroy',
        'namespace' => NULL,
        'prefix' => '/Groupthree',
        'where' => 
        array (
        ),
        'as' => 'GroupThree.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chartOfAccount',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@index',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@index',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.getGroupTwo' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chartOfAccount/getGroupTwo/{GroupOne}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@getGroupTwo',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@getGroupTwo',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.getGroupTwo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.getGroupThree' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chartOfAccount/getGroupThree/{GroupOne}/{GroupTwo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@getGroupThree',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@getGroupThree',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.getGroupThree',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.generateCode' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chartOfAccount/generateAccountCode/{groupthree}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@generateCode',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@generateCode',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.generateCode',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'chartOfAccount/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@store',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@store',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chartOfAccount/{chartOfAccount}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@edit',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@edit',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'chartOfAccount/{chartOfAccount}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@update',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@update',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'chartOfAccount/{chartOfAccount}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@updateStatus',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chartOfAccount/{chartOfAccount}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@show',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@show',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chartOfAccount.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'chartOfAccount/show/{chartOfAccount}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@destroy',
        'controller' => 'App\\Http\\Controllers\\Accounts\\ChartOfAccountController@destroy',
        'namespace' => NULL,
        'prefix' => '/chartOfAccount',
        'where' => 
        array (
        ),
        'as' => 'chartOfAccount.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.AllInvoiceList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'invoicelist/AllInvoiceList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@AllInvoiceList',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@AllInvoiceList',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.AllInvoiceList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.DueInvoiceList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'invoicelist/DueInvoiceList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@DueInvoiceList',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@DueInvoiceList',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.DueInvoiceList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.MRList' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'invoicelist/MRList',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@MRList',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@MRList',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.MRList',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.createMR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'invoicelist/{insid}/createmr/{sid}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@createMR',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@createMR',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.createMR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.storeMR' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'invoicelist/{insnumber}/storeMR/{student}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@storeMR',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@storeMR',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.storeMR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.onView' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'invoicelist/{confirm}/onView',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onView',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onView',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.onView',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.onCancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'invoicelist/{confirm}/onCancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onCancel',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onCancel',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.onCancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.onConfirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'invoicelist/{confirm}/onConfirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onConfirm',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onConfirm',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.onConfirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'invoicelist.onReport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'invoicelist/{onReport}/onReport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onReport',
        'controller' => 'App\\Http\\Controllers\\Accounts\\MoneyReceiptController@onReport',
        'namespace' => NULL,
        'prefix' => '/invoicelist',
        'where' => 
        array (
        ),
        'as' => 'invoicelist.onReport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vouhcerheader.credit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'vouhcerheader/credit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\VouhcerheaderController@credit',
        'controller' => 'App\\Http\\Controllers\\Accounts\\VouhcerheaderController@credit',
        'namespace' => NULL,
        'prefix' => '/vouhcerheader',
        'where' => 
        array (
        ),
        'as' => 'vouhcerheader.credit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vouhcerheader.debit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'vouhcerheader/debit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\VouhcerheaderController@debitVoucher',
        'controller' => 'App\\Http\\Controllers\\Accounts\\VouhcerheaderController@debitVoucher',
        'namespace' => NULL,
        'prefix' => '/vouhcerheader',
        'where' => 
        array (
        ),
        'as' => 'vouhcerheader.debit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vouhcerheader.reverse' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'vouhcerheader/reverse',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'verified',
          2 => 'auth',
          3 => 'isBanned',
          4 => 'UserActivity',
        ),
        'uses' => 'App\\Http\\Controllers\\Accounts\\VouhcerheaderController@reverseVoucher',
        'controller' => 'App\\Http\\Controllers\\Accounts\\VouhcerheaderController@reverseVoucher',
        'namespace' => NULL,
        'prefix' => '/vouhcerheader',
        'where' => 
        array (
        ),
        'as' => 'vouhcerheader.reverse',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9mI0Jip2M9jYMvvr' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::9mI0Jip2M9jYMvvr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rfXuIdjSt7VfWeSl' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::rfXuIdjSt7VfWeSl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.email',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reset-password/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.reset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.notice' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'verify-email',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\EmailVerificationPromptController@__invoke',
        'controller' => 'App\\Http\\Controllers\\Auth\\EmailVerificationPromptController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.notice',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.verify' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'verify-email/{id}/{hash}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'signed',
          3 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\VerifyEmailController@__invoke',
        'controller' => 'App\\Http\\Controllers\\Auth\\VerifyEmailController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.verify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'email/verification-notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\EmailVerificationNotificationController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\EmailVerificationNotificationController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.send',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'confirm-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@show',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kabiLGFmrwNDHJ33' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'confirm-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::kabiLGFmrwNDHJ33',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:45:"D:\\xampp\\htdocs\\lara12hrm\\storage\\app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"000000000000072e0000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
