<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <title>Journal Transaction Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
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


        .title-table {
            width: 100%;
            background: #D7D7D7;
            border-radius: 8px;
            padding: 12px;
        }

        .title-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .title-value {
            font-size: 12px;
            color: #1e293b;
            font-weight: 500;
        }

        /* ================= META ================= */
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .meta-table td {
            padding: 4px;
        }

        .meta-label {
            font-weight: bold;
            width: 15%;
        }

        /* ================= TABLE ================= */
        .journal-table {
            width: 100%;
            border-collapse: collapse;
        }

        .journal-table th,
        .journal-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .journal-table th {
            background: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .debit {
            padding-left: 18px;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #000;
            background: #f9f9f9;
        }

        /* ================= FOOTER ================= */
        .footer {
            width: 100%;
            margin-top: 40px;
            font-size: 10px;
        }

        .sign {
            width: 33%;
            text-align: center;
            padding-top: 35px;
        }

        .sign span {
            display: block;
            border-top: 1px solid #000;
            margin-top: 5px;
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
        Journal Transaction Report
    </div>
    <div class="report-title-section">
        <table class="title-table">
            <tr>
                <td width="50%">
                    <div class="title-label">Report Period</div>
                    <div class="title-value">{{ date('M d, Y', strtotime($startdate)) }} - {{ date('M d, Y', strtotime($enddate)) }}</div>
                </td>
                <td align="right">
                    <div class="title-label">Branch</div>
                    <div class="title-value">{{ $branch->branchname ?? 'All Branches' }}</div>
                </td>
            </tr>
        </table>
    </div>


    @foreach ($jurnalTransactions as $transaction)
    <!-- META -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Voucher No</td>
            <td>{{ $transaction->vouchernumber }}</td>

            <td class="meta-label">Voucher Date</td>
            <td>{{ $transaction->voucherdate }}</td>

            <td class="meta-label">Year</td>
            <td>{{ $transaction->yearname }}</td>

            <td class="meta-label">Period </td>
            <td>{{ $transaction->monthname }}</td>
        </tr>
        <tr>
            <td class="meta-label">Branch</td>
            <td>{{ $transaction->branch->branchname }}</td>

            <td class="meta-label">Reference</td>
            <td colspan="3">{{ $transaction->referance }}</td>

            <td class="meta-label">Status </td>
            <td>{{ $transaction->status }}</td>
        </tr>
    </table>

    <!-- JOURNAL TABLE -->
    <table class="journal-table">
        <thead>
            <tr>
                <th width="15%">Account Code</th>
                <th width="20%">Account Description</th>
                <th width="40%">Notes</th>
                <th width="10%">Debit (Dr)</th>
                <th width="10%">Credit (Cr)</th>
            </tr>

        </thead>

        <tbody>
            @php
            $totalDr = 0;
            $totalCr = 0;
            @endphp
            @foreach ($transaction->voucherdt as $index => $detail)
            @php
            $dr = $detail->primeamt < 0 ? abs($detail->primeamt) : 0;
                $cr = $detail->primeamt > 0 ? $detail->primeamt : 0;

                $totalDr += $dr;
                $totalCr += $cr;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    <td class="{{ $dr > 0 ? 'debit' : '' }}">
                        {{ $detail->ChartOfAccount->description }}
                    </td>

                    <td class="{{ $cr < 0 ? 'credit' : '' }}">
                        {{ $detail->notes }}
                    </td>

                    <td class="text-right">{{ $dr > 0 ? number_format($dr,2) : '' }}</td>
                    <td class="text-right">{{ $cr > 0 ? number_format($cr,2) : '' }}</td>
                </tr>

                @endforeach

                <tr class="total-row">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-right">{{ number_format($totalDr,2) }}</td>
                    <td class="text-right">{{ number_format($totalCr,2) }}</td>
                </tr>
        </tbody>
    </table>
    @endforeach
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