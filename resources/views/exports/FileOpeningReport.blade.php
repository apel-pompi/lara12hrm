<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>File Opening Fee Report</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            font-size: 11px;
            color: #111827;
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
            padding: 10px;
            margin-bottom: 12px;
            background: #fff;
        }

        th {
            background: #f3f4f6;
            font-weight: 600;
            font-size: 10px;
            border: 1px solid #d1d5db;
            padding: 6px;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 6px;
            font-size: 10px;
        }

        .total-row {
            background: #eef2ff;
            font-weight: 700;
        }

        .amount {
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    {{-- =========================
        Header
    ========================== --}}
    <div style="display:table;width:100%">

        {{-- Company Logo --}}
        <div style="display:table-cell;width:20%;vertical-align:middle">

            @if(isset($company) && $company->companylogo)
            <img src="{{ public_path('storage/company/' . $company->companylogo) }}"
                width="100">
            @endif

        </div>


        {{-- Report Title --}}
        <div style="display:table-cell;width:60%;text-align:center;vertical-align:middle">

            <h2 style="margin:0">
                File Opening Fee Report
            </h2>

            <p class="muted" style="margin:5px 0">
                Student File Opening Collection Report
            </p>

            <p class="muted" style="margin:0">
                From {{ \Carbon\Carbon::parse($formdate)->format('d M Y') }}
                To {{ \Carbon\Carbon::parse($todate)->format('d M Y') }}
            </p>

        </div>


        {{-- Report Information --}}
        <div style="display:table-cell;width:20%;text-align:right;vertical-align:middle">

            <p style="margin:3px 0">
                <strong>Date:</strong>
                {{ now()->format('d M Y') }}
            </p>

            <p style="margin:3px 0">
                <strong>Prepared By:</strong>
                {{ auth()->user()->name }}
            </p>

        </div>

    </div>


    <div class="divider"></div>
    @if($personalinfo)
    <div class="card">
        <table>
            <tr>
                <td>
                    <strong>Employee Name</strong>
                </td>

                <td class="text-right">
                    {{ $personalinfo->empname }}
                </td>

                <td>
                    <strong>Designation</strong>
                </td>

                <td class="text-right">
                    {{ $personalinfo->designation->desname ?? '-' }}
                </td>
            </tr>
        </table>
    </div>
    @endif


    {{-- =========================
        Report Summary
    ========================== --}}
    <div class="card">

        <table>

            <tr>

                <td>
                    <strong>Report Period</strong>
                </td>

                <td class="text-right">
                    {{ \Carbon\Carbon::parse($formdate)->format('d M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($todate)->format('d M Y') }}
                </td>


                <td>
                    <strong>Total Records</strong>
                </td>

                <td class="text-right">
                    {{ count($dataArray) }}
                </td>

            </tr>

            <tr>

                <td>
                    <strong>Report Type</strong>
                </td>

                <td class="text-right">
                    File Opening Fee
                </td>


                <td>
                    <strong>Total Collection</strong>
                </td>

                <td class="text-right amount">
                    {{ number_format($totalCollection, 2) }}
                </td>

            </tr>

        </table>

    </div>


    {{-- =========================
        Main Report
    ========================== --}}
    <div class="card">

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    @if($showEmployeeColumn)
                    <th>Employee</th>
                    @endif
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Fee Name</th>
                    <th>Invoice No.</th>
                    <th>Date</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>

            <tbody>

                @forelse($dataArray as $index => $data)

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    @if($showEmployeeColumn)
                    <td>
                        {{ $data['employee'] }}
                    </td>
                    @endif

                    <td>
                        {{ $data['student_id'] }}
                    </td>

                    <td>
                        <strong>
                            {{ $data['studentname'] }}
                        </strong>
                    </td>

                    <td>
                        {{ $data['fees_name'] }}
                    </td>

                    <td>
                        {{ $data['insnumber'] }}
                    </td>

                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($data['insdate'])->format('d M Y') }}
                    </td>

                    <td class="text-right">
                        {{ number_format($data['amount'], 2) }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center">
                        No File Opening Fee records found.
                    </td>
                </tr>

                @endforelse

                @if($totalRecords > 0)

                <tr class="total-row">

                    <td colspan="{{ $showEmployeeColumn ? 7 : 6 }}" class="text-right">
                        Grand Total
                    </td>

                    <td class="text-right">
                        {{ number_format($totalCollection, 2) }}
                    </td>

                </tr>

                @endif

            </tbody>
        </table>

    </div>


    {{-- =========================
        Footer
    ========================== --}}
    <div style="
        margin-top:20px;
        text-align:center;
        font-size:10px;
        color:#6b7280;
    ">

        This report is system generated and does not require signature.

    </div>

</body>

</html>