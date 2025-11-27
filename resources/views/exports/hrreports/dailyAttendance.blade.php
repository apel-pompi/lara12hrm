<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
            text-align: center;
            color: #2c3e50;
        }

        .header-info {
            width: 100%;
            margin-top: 10px;
        }

        .header-info td {
            font-size: 12px;
            padding: 2px 0;
            vertical-align: top;
        }

        .left-info {
            width: 60%;
        }

        .right-info {
            width: 40%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .summary-table td {
            border: 1px solid #bdc3c7;
            padding: 6px 4px;
            text-align: center;
            background: #f8f9fa;
            font-size: 11px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }

        .summary-label {
            font-size: 10px;
            color: #6c757d;
            display: block;
            margin-top: 2px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th {
            background-color: #2c3e50;
            color: white;
            border: 1px solid #34495e;
            padding: 6px 4px;
            font-size: 11px;
            text-align: center;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #bdc3c7;
            padding: 5px 4px;
            text-align: center;
            font-size: 11px;
        }

        .footer-sign {
            width: 100%;
            margin-top: 40px;
            text-align: center;
        }

        .footer-sign td {
            width: 33%;
            text-align: center;
            font-size: 12px;
        }

        .footer-sign .line {
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 3px;
            width: 80%;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            min-width: 120px;
            display: inline-block;
        }

        .info-value {
            color: #2c3e50;
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

        /* Alternating row colors */
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Daily Attendance Report</h2>
        
        <table class="header-info">
            <tr>
                <td class="left-info">
                    <div>
                        <span class="info-label">Branch:</span>
                        <span class="info-value">{{ $branch->branchname }}</span>
                    </div>
                    <div>
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{ $date }}</span>
                    </div>
                </td>
                
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="30">SL</th>
                <th>Employee Name</th>
                <th width="70">Employee ID</th>
                <th width="80">Department</th>
                <th width="80">Designation</th>
                <th width="60">In Time</th>
                <th width="60">Out Time</th>
                <th width="60">Work Hours</th>
                <th width="50">Status</th>
                <th width="50">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $index => $emp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $emp['name'] }}</td>
                    <td>{{ $emp['empid'] ?? 'N/A' }}</td>
                    <td>{{ $emp['deptname'] }}</td>
                    <td>{{ $emp['desname'] }}</td>
                    <td>{{ $emp['intime'] ?? '---' }}</td>
                    <td>{{ $emp['outtime'] ?? '---' }}</td>
                    <td>{{ $emp['workhours'] ?? '---' }}</td>
                    <td class="status-{{ strtolower($emp['status'] ?? '') }}">
                        {{ $emp['status'] ?? '---' }}
                    </td>
                    <td class="text-left">{{ $emp['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-sign">
        <tr>
            <td><span class="line">Prepared By</span></td>
            <td><span class="line">Checked By</span></td>
            <td><span class="line">Approved By</span></td>
        </tr>
    </table>
</body>
</html>