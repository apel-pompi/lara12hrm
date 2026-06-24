<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <title>Cash Book Statement Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
        }

        /* ================= HEADER ================= */
        .header {
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .logo img {
            max-height: 60px;
        }

        .company-name {
            font-size: 17px;
            font-weight: bold;
        }

        .company-info {
            font-size: 10px;
            line-height: 1.4;
            color: #555;
        }

        /* ================= TITLE ================= */
        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0 10px;
            padding: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-title-section {
            margin-bottom: 25px;
        }


        .meta-table {
            width: 100%;
            background: #D7D7D7;
            border-radius: 8px;
            padding: 12px;
        }

        .meta-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .meta-value {
            font-size: 12px;
            color: #1e293b;
            font-weight: 500;
        }

        /* ================= TABLE ================= */

        .cashbook-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .cashbook-table th,
        .cashbook-table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }

        .cashbook-table th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .cashbook-table td {
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Opening Balance */
        .bf-row td {
            font-weight: bold;
            background: #fafafa;
        }

        /* Normal rows */
        .data-row td {
            background: #fff;
        }

        /* Receipt / Payment emphasis */
        .receipt {
            color: #0f5132;
            font-weight: 600;
        }

        .payment {
            color: #842029;
            font-weight: 600;
        }

        /* Balance column */
        .balance {
            font-weight: 600;
        }

        /* Total row */
        .total-row td {
            font-weight: bold;
            background: #eaeaea;
            border-top: 2px solid #000;
        }

        /* Closing balance */
        .closing-row td {
            font-weight: bold;
            background: #d9d9d9;
            border-top: 2px solid #000;
        }



        /* ================= FOOTER ================= */
        .footer {
            margin-top: 45px;
            width: 100%;
            font-size: 10px;
        }

        .sign {
            width: 33%;
            text-align: center;
            padding-top: 40px;
        }

        .sign span {
            display: block;
            border-top: 1px solid #444;
            margin-top: 5px;
            padding-top: 3px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header">
        <tr>
            <td class="logo" width="30%">
                @if ($company->companylogo)
                <img src="{{ public_path('storage/company/' . $company->companylogo) }}">
                @endif
            </td>
            <td align="right">
                <div class="company-name">{{ $company->companyname }}</div>
                <div class="company-info">
                    {{ $company->address_one }}<br>
                    {{ $company->address_two }}<br>
                    {{ $company->company_phone }} | {{ $company->company_email }}
                </div>
            </td>
        </tr>
    </table>

    <!-- TITLE -->
    <div class="report-title">
        Cash Book Statement Report
    </div>
    <div class="report-title-section">
        <table class="meta-table">
            <tr>
                <td width="50%">
                    <div class="meta-label">Report Period</div>
                    <div class="meta-value">{{ date('M d, Y', strtotime($startdate)) }} - {{ date('M d, Y', strtotime($enddate)) }}</div>
                </td>
                <td align="right">
                    <div class="meta-label">Branch</div>
                    <div class="meta-value">{{ $opening->branchname ?? 'All Branches' }}</div>
                </td>
            </tr>
        </table>
    </div>
    <!-- TABLE -->
    <table class="cashbook-table">
        <thead>
            <tr>
                <th style="width:12%">Date</th>
                <th style="width:33%">Particulars</th>
                <th style="width:15%">Voucher No</th>
                <th style="width:13%">Receipts</th>
                <th style="width:13%">Payments</th>
                <th style="width:14%">Balance</th>
            </tr>
        </thead>

        <tbody>
            @php
            $balance = $opening->opening ?? 0;
            $totalReceipt = 0;
            $totalPayment = 0;
            @endphp

            <!-- Opening Balance -->
            <tr class="bf-row">
                <td colspan="5" class="text-center">Balance B/F</td>
                <td class="text-right">{{ $balance }}</td>
            </tr>

            @foreach ($cashBook as $row)
            @php
            $receipt = $row->primeamt > 0 ? $row->primeamt : 0;
            $payment = $row->primeamt < 0 ? abs($row->primeamt) : 0;

                $balance = $balance + $receipt - $payment;
                $totalReceipt += $receipt;
                $totalPayment += $payment;
                @endphp

                <tr class="data-row">
                    <td class="text-center">
                        {{ date('d-m-Y', strtotime($row->voucherdate)) }}
                    </td>
                    <td>
                        {{ $row->description }}
                    </td>
                    <td class="text-center">
                        {{ $row->vouchernumber }}
                    </td>
                    <td class="text-right receipt">
                        {{ $receipt > 0 ? number_format($receipt,2) : '' }}
                    </td>
                    <td class="text-right payment">
                        {{ $payment > 0 ? number_format($payment,2) : '' }}
                    </td>
                    <td class="text-right balance">
                        {{ number_format($balance,3) }}
                    </td>
                </tr>
                @endforeach

                <!-- Totals -->
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td class="text-right">{{ number_format($totalReceipt,2) }}</td>
                    <td class="text-right">{{ number_format($totalPayment,2) }}</td>
                    <td></td>
                </tr>

                <!-- Closing Balance -->
                <tr class="closing-row">
                    <td colspan="5">Closing Balance</td>
                    <td class="text-right">{{ number_format($balance,2) }}</td>
                </tr>
        </tbody>
    </table>


    <!-- FOOTER -->
    <table class="footer">
        <tr>
            <td class="sign">
                <span>Prepared By</span>
            </td>
            <td class="sign">
                <span>Checked By</span>
            </td>
            <td class="sign">
                <span>Approved By</span>
            </td>
        </tr>
    </table>

</body>

</html>