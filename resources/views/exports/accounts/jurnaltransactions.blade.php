<!DOCTYPE html>
<html>
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
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 15px;
            font-weight: bold;
        }

        .company-info {
            font-size: 10px;
        }

        /* ================= TITLE ================= */
        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 10px 0 5px;
            text-transform: uppercase;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
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
        <td>
            <div class="company-name">{{ $company->companyname }}</div>
            <div class="company-info">
                {{ $company->address_one }}<br>
                {{ $company->company_phone }} | {{ $company->company_email }}
            </div>
        </td>
        <td align="right">
            <strong>Date:</strong> {{ date('d M Y') }}<br>
            <strong>Printed By:</strong> {{ auth()->user()->name }}
        </td>
    </tr>
</table>

<!-- TITLE -->
<div class="title">Journal Transaction Report</div>
<div class="subtitle">
    For the period {{ date('d M Y', strtotime($startdate)) }} to {{ date('d M Y', strtotime($enddate)) }}
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
            <th width="10%">SL</th>
            <th width="45%">Account Head</th>
            <th width="15%">Debit (Dr)</th>
            <th width="15%">Credit (Cr)</th>
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
            <td class="{{ $dr > 0 ? 'debit' : '' }}">{{ $detail->ChartOfAccount->description }}</td>
            <td class="text-right">{{ $dr > 0 ? $dr : '' }}</td>
            <td class="text-right">{{ $cr > 0 ? $cr : '' }}</td>
        </tr>

        @endforeach

        <tr class="total-row">
            <td colspan="2" class="text-right">Total</td>
            <td class="text-right">{{ $totalDr }}</td>
            <td class="text-right">{{ $totalCr }}</td>
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
