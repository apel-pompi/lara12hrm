<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Daily Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
        }

        .branch-info {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .branch-info td {
            font-size: 12px;
            padding: 3px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th {
            background-color: #f0f0f0;
            border: 0.8px solid #000;
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }

        .table td {
            border: 0.8px solid #000;
            padding: 3px;
            text-align: center;
            font-size: 11.5px;
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
    </style>
</head>

<body>
    <div class="header">
        <h2>Daily Attendance Sheet</h2>
        <p>Branch:<strong>{{ $branch->branch_name }}</strong></p>
        <p>Date: {{ $date }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="30">SL</th>
                <th>Employee Name</th>
                <th width="100">Department</th>
                <th width="120">Designation</th>
                <th width="80">In Time</th>
                <th width="80">Out Time</th>
                <th width="50">Houre</th>
                <th width="50">Status</th>
                <th width="50">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $index => $emp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $emp['name'] }}</td>
                    <td>{{ $emp['deptname'] }}</td>
                    <td>{{ $emp['desname'] }}</td>
                    <td>{{ $emp['intime'] ?? '---' }}</td>
                    <td>{{ $emp['outtime'] ?? '---' }}</td>
                    <td>{{ $emp['workhours'] }}</td>
                    <td>{{ $emp['status'] ?? '---' }}</td>
                    <td>{{ $emp['remarks'] ?? '' }}</td>
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
