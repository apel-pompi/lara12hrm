<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <title>Accounts to General Ledger Report</title>

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
        table.ledger {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.ledger thead th {
            font-size: 12px;
            padding: 7px 6px;
            background: #f3f3f3;
            border-bottom: 1px solid #444;
            text-align: center;
        }

        table.ledger tbody td {
            font-size: 12px;
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bf-row td {
            font-weight: bold;
            background: #fafafa;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #333;
            background: #f6f6f6;
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
        Accounts to General Ledger Report
    </div>
    <div class="report-title-section">
        <table class="meta-table">
            <tr>
                <td width="25%">
                    <div class="meta-label">Account Name</div>
                    <div class="meta-value">{{ $opening->ChartOFAccount->description ?? '' }}</div>
                </td>
                <td width="25%">
                    <div class="meta-label">Report Period</div>
                    <div class="meta-value">{{ date('M d, Y', strtotime($startdate)) }} - {{ date('M d, Y', strtotime($enddate)) }}</div>
                </td>
                <td width="25%">
                    <div class="meta-label">Branch</div>
                    <div class="meta-value">{{ $opening->branch->branchname ?? '' }}</div>
                </td>
                <td width="25%" class="text-right">
                    <div class="meta-label">Currency</div>
                    <div class="meta-value">{{ $opening->currency ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>
    <!-- TABLE -->
    <table class="ledger">
        <thead>
            <tr>
                <th width="90">Voucher No</th>
                <th width="75">Date</th>
                <th width="90">Branch</th>
                <th>Description</th>
                <th width="90">Debit</th>
                <th width="90">Credit</th>
                <th width="90">Balance</th>
            </tr>
        </thead>

        <tbody>
            <!-- B/F Balance -->
            @php
            $balance = $opening->opening ?? 0;
            $totalDr = 0;
            $totalCr = 0;
            @endphp
            <tr class="bf-row">
                <td colspan="6" class="text-center">B/F Balance</td>
                <td class="text-right">{{ $balance }}</td>
            </tr>

            <!-- Data Row -->
            @foreach ($voucher as $item)

            @php
            $dr = 0;
            $cr = 0;

            if ($item->primeamt < 0) {
                $dr=abs($item->primeamt);
                $balance -= $dr;
                $totalDr += $dr;
                } else {
                $cr = $item->primeamt;
                $balance += $cr;
                $totalCr += $cr;
                }
                @endphp

                <tr>
                    <td>{{ $item->voucherheader->vouchernumber }}</td>
                    <td>{{ $item->voucherdate }}</td>
                    <td class="text-center">{{ $item->branch->branchname }}</td>
                    <td>{{ $item->referance }}</td>
                    <td class="text-right">{{ $dr > 0 ? number_format($dr,2) : '' }}</td>
                    <td class="text-right">{{ $cr > 0 ? number_format($cr,2) : '' }}</td>
                    <td class="text-right">{{ number_format($balance,2) }}</td>
                </tr>
                @endforeach
                <!-- Total -->
                <tr class="total-row">
                    <td colspan="4">Total</td>
                    <td class="text-right">{{ number_format($totalDr,2) }}</td>
                    <td class="text-right">{{ number_format($totalCr,2) }}</td>
                    <td></td>
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