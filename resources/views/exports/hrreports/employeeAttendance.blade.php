<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px 0px;
            line-height: 1.2;
        }

        .header {
            margin-bottom: 8px;
            border-bottom: 2px solid #333;
            padding-bottom: 4px;
        }

        .header h2 {
            margin: 0 0 8px 0;
            font-size: 16px;
            text-transform: uppercase;
            text-align: center;
            color: #2c3e50;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 2px 5px;
        }

        .employee-details {
            width: 55%;
        }

        .summary-cards {
            width: 45%;
        }

        .info-row {
            margin-bottom: 3px;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            width: 100px;
        }

        .info-value {
            color: #2c3e50;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table td {
            border: 1px solid #dee2e6;
            padding: 6px 4px;
            text-align: center;
            background: #f8f9fa;
        }

        .stats-value {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }

        .stats-label {
            font-size: 9px;
            color: #6c757d;
            display: block;
            margin-top: 2px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 9px;
        }

        .table th {
            background-color: #2c3e50;
            color: white;
            border: 1px solid #34495e;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
        }

        .table td {
            border: 1px solid #bdc3c7;
            padding: 5px 4px;
            text-align: center;
        }

        .table td.text-left {
            text-align: left;
        }

        .status-present {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }

        .status-late {
            background-color: #fff3cd;
            color: #856404;
            font-weight: bold;
        }

        .status-leave {
            background-color: #d1ecf1;
            color: #0c5460;
            font-weight: bold;
        }

        .holiday-row {
            background-color: #fff3cd !important;
        }

        .holiday-cell {
            background-color: #ffeaa7 !important;
            font-weight: bold;
            color: #856404;
            text-align: center;
        }

        .footer-sign {
            width: 100%;
            margin-top: 6px;
            border-top: 1px solid #bdc3c7;
            padding-top: 4px;
        }

        .footer-sign td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
        }

        .signature-line {
            border-top: 1px solid #2c3e50;
            width: 80%;
            margin: 15px auto 2px auto;
            display: block;
        }

        .signature-label {
            font-size: 9px;
            color: #2c3e50;
            margin-top: 3px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Employee Attendance Report</h2>
        
        <table class="header-table">
            <tr>
                <!-- Left Side - Employee Details -->
                <td class="employee-details">
                    <div class="info-row">
                        <span class="info-label">Employee ID:</span>
                        <span class="info-value">{{ $employees->empid }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Employee Name:</span>
                        <span class="info-value">{{ $employees->empname }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Department:</span>
                        <span class="info-value">{{ $employees->department->deptname }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Designation:</span>
                        <span class="info-value">{{ $employees->designation->desname }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Month/Year:</span>
                        <span class="info-value">{{ $monthname }}, {{ $yearname }}</span>
                    </div>
                </td>

                <!-- Right Side - Summary Cards -->
                <td class="summary-cards">
                    <table class="stats-table">
                        <tr>
                            <td>
                                <span class="stats-value">{{ $presentCount }}</span>
                                <span class="stats-label">Present</span>
                            </td>
                            <td>
                                <span class="stats-value">{{ $lateCount }}</span>
                                <span class="stats-label">Late</span>
                            </td>
                            <td>
                                <span class="stats-value">{{ $absentCount }}</span>
                                <span class="stats-label">Absent</span>
                            </td>
                             <td>
                                <span class="stats-value">{{ $leaveCount }}</span>
                                <span class="stats-label">Leave</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="stats-value">{{ $holidayCount }}</span>
                                <span class="stats-label">Holiday</span>
                            </td>
                            <td>
                                <span class="stats-value">{{ $totalWork }}</span>
                                <span class="stats-label">Work Hours</span>
                            </td>
                            <td>
                                <span class="stats-value">{{ $totalDeduct }}</span>
                                <span class="stats-label">Deduct Hours</span>
                            </td>
                            <td>
                                <span class="stats-value">{{ $totalnetWork }}</span>
                                <span class="stats-label">Net Hours</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="15%">Day</th>
                <th width="12%">In Time</th>
                <th width="12%">Out Time</th>
                <th width="12%">Work Hours</th>
                <th width="12%">Deduct Hours</th>
                <th width="11%">Net Hours</th>
                <th width="11%">Status</th>
                <th width="11%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @if(count($data) > 0)
                @foreach ($data as $key)
                    @if ($key['is_holiday'])
                        <tr class="holiday-row">
                            <td class="text-left">{{ $key['datename'] }}</td>
                            <td>{{ date('D', strtotime($key['datename'])) }}</td>
                            <td colspan="7" class="holiday-cell">
                                {{ $key['holiday_type'] ?? 'Holiday' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-left">{{ $key['datename'] }}</td>
                            <td>{{ date('D', strtotime($key['datename'])) }}</td>
                            <td>{{ $key['intime'] ?? '---' }}</td>
                            <td>{{ $key['outtime'] ?? '---' }}</td>
                            <td>{{ $key['workhours'] ?? '---' }}</td>
                            <td class="text-center">{{ $key['deduct'] ?? '---' }}</td>
                            <td class="text-center">{{ $key['nethour'] ?? '---' }}</td>
                            <td class="status-{{ strtolower($key['status'] ?? '') }}">
                                {{ $key['status'] ?? '---' }}
                            </td>
                            <td class="text-left">{{ $key['remarks'] ?? '' }}</td>
                        </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="no-data">
                        No attendance data available for the selected period
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="footer-sign">
        <tr>
            <td>
                <div class="signature-label">Prepared By</div>
                <span class="signature-line"></span>
            </td>
            <td>
                <div class="signature-label">Checked By</div>
                <span class="signature-line"></span>
            </td>
            <td>
                <div class="signature-label">Approved By</div>
                <span class="signature-line"></span>
            </td>
        </tr>
    </table>
</body>
</html>