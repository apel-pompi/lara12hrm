<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Revenue</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

</head>
<style>
    * {
        font-family: 'DejaVu Sans', sans-serif;
    }

    body {
        font-size: 11px;
        color: #111827;
        margin: 15px;
    }

    .divider {
        border-bottom: 2px solid #1f2937;
        margin: 12px 0;
    }

    .muted {
        color: #6b7280;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .card {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 12px;
        background: #fff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f3f4f6;
        font-weight: 600;
        font-size: 11px;
        border: 1px solid #d1d5db;
        padding: 6px;
    }

    td {
        border: 1px solid #d1d5db;
        padding: 6px;
        font-size: 11px;
    }

    .total-row {
        background: #eef2ff;
        font-weight: 700;
    }
</style>

<body>
    <div style="display:table;width:100%">
        <div style="display:table-cell;width:20%">
            <img src="{{ public_path('storage/company/'.$company->companylogo) }}" width="100">
        </div>

        <div style="display:table-cell;width:60%;text-align:center">
            <h2 style="margin:0">Student Revenue Analysis</h2>
            <p class="muted">Financial Performance Overview</p>
        </div>

        <div style="display:table-cell;width:20%;text-align:right">
            <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>
            <p><strong>Prepared By:</strong> {{ auth()->user()->name }}</p>
        </div>
    </div>

    <div class="divider"></div>
    @if($personalinfo)
    <div class="card">
        <table>
            <tr>
                <td><strong>Employee Name</strong></td>
                <td class="text-right">{{$personalinfo->empname}}</td>

                <td><strong>Designation</strong></td>
                <td class="text-right">{{ $personalinfo->designation->desname }}</td>
            </tr>
        </table>
    </div>
    @endif
    <div class="card">
        <table>
            <tr>
                <td><strong>Total Students</strong></td>
                <td class="text-right">{{$totalStudents}}</td>

                <td><strong>Total Invoiced</strong></td>
                <td class="text-right">{{ $totalInvoiced }}</td>
            </tr>
            <tr>
                <td><strong>Total Received</strong></td>
                <td class="text-right">{{ $totalReceived }}</td>

                <td><strong>Total Due</strong></td>
                <td class="text-right">{{ $totalDue }}</td>
            </tr>
        </table>
    </div>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Program</th>
                    <th class="text-right">Invoiced</th>
                    <th class="text-right">Received</th>
                    <th class="text-right">Due</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i = 1;
                $grandInvoice = 0;
                $grandReceive = 0;
                @endphp

                @foreach ($grouped as $studentId => $rows)
                    @php
                        $invoice = $rows->filter(fn ($r) =>
                        str_starts_with($r->insnumber, 'INV-') && $r->sign == 1
                        )->sum('netamount');

                        $receive = $rows->filter(fn ($r) =>
                            str_starts_with($r->insnumber, 'MR--') && $r->sign == -1 && $r->note <> 'REFUND'
                        )->sum('netamount');

                        $grandInvoice += $invoice;
                        $grandReceive += $receive;
                    @endphp
                    @if ($invoice == 0 || $receive == 0)
                        @continue
                    @endif
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $rows->first()->student->student_id }}</td>
                    <td>{{ $rows->first()->student->fname }} {{ $rows->first()->student->lname }}</td>
                    <td></td>
                    <td class="text-right">{{ $invoice }}</td>
                    <td class="text-right">{{ $receive }}</td>
                    <td class="text-right">{{ $invoice - $receive }}</td>
                </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="4" class="text-right">Grand Total</td>
                    <td class="text-right">{{ $grandInvoice }}</td>
                    <td class="text-right">{{ $grandReceive }}</td>
                    <td class="text-right">{{ $grandInvoice - $grandReceive }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;text-align:center;font-size:10px;color:#6b7280">
        This report is system generated and does not require signature.
    </div>

</body>

</html>