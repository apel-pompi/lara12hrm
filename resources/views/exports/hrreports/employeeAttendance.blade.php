<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Employee Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 5px;
        }

        .header {
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            text-align: center;
        }

        .header .info {
            margin-top: 5px;
            font-size: 12px;
        }

        .header .info p {
            margin: 2px 0;
            text-align: left; /* Left align all header info */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }

        .table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            font-size: 11px;
        }

        .table td.text-left {
            text-align: left;
        }

        .footer-sign {
            width: 100%;
            margin-top: 25px;
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
    </style>
</head>

<body>
    <div class="header">
        <h2>Employee Attendance Sheet</h2>
        <div class="info left">
            <p><strong>Employee Name:</strong> {{ $employees->empname }}</p>
            <p><strong>Department:</strong> {{ $employees->department->deptname }}</p>
            <p><strong>Designation:</strong> {{ $employees->designation->desname }}</p>
            <p><strong>Month/Year:</strong> {{ $monthname }}, {{ $yearname }}</p>
        </div>
        <div class="info right">
            <p><strong>Total Present:</strong> </p>
            <p><strong>Total Late:</strong> </p>
            <p><strong>Total Absent:</strong> </p>
            <p><strong>Working Hour:</strong> </p>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Work Hours</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key)
            @if ($key['is_holiday'])
                <tr class="bg-yellow-50">
                    <td class="text-left">{{ $key['datename'] }}</td>
                    <td colspan="5" class="text-center bg-yellow-100 font-semibold">
                        {{ $key['holiday_type'] }}
                    </td>
                </tr>
            @else
                <tr>
                    <td class="text-left">{{ $key['datename'] }}</td>
                    <td>{{ $key['intime'] ?? '---' }}</td>
                    <td>{{ $key['outtime'] ?? '---' }}</td>
                    <td>{{ $key['workhours'] ?? '---' }}</td>
                    <td>{{ $key['status'] ?? '---' }}</td>
                    <td class="text-left">{{ $key['remarks'] ?? '' }}</td>
                </tr>
            @endif
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
