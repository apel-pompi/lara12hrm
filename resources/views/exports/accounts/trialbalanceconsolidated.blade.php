<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Trial Balance (Consolidated)</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
        }

        /* ===== HEADER ===== */
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
        }

        .company-info {
            font-size: 10px;
            color: #444;
        }

        /* ===== TITLE ===== */
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0 5px;
            text-transform: uppercase;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
            color: #555;
        }

        /* ===== META ===== */
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .meta-table td {
            padding: 4px;
        }

        .meta-label {
            font-weight: bold;
            color: #333;
        }

        /* ===== TABLE ===== */
        table.trial-table {
            width: 100%;
            border-collapse: collapse;
        }

        .trial-table th,
        .trial-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .trial-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .account-name {
            padding-left: 5px;
        }

        .total-row td {
            font-weight: bold;
            background: #e8e8e8;
            border-top: 2px solid #000;
        }

        /* ===== FOOTER ===== */
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
        <td width="70%">
            <div class="company-name">{{ $company->companyname }}</div>
            <div class="company-info">
                {{ $company->address_one }}<br>
                {{ $company->company_phone }} | {{ $company->company_email }}
            </div>
        </td>
        <td align="right">
            @if($company->companylogo)
                <img src="{{ public_path('storage/company/'.$company->companylogo) }}" height="50">
            @endif
        </td>
    </tr>
</table>

<!-- TITLE -->
<div class="title">Trial Balance (Consolidated)</div>
<div class="subtitle">
    For the period {{ date('d M Y', strtotime($startdate)) }}
    to {{ date('d M Y', strtotime($enddate)) }}
</div>

<!-- META -->
<table class="meta-table">
    <tr>
        <td width="25%">
            <span class="meta-label">Branch:</span> {{ $branch->branchname }}
        </td>
        <td width="25%">
            <span class="meta-label">Currency:</span> BDT
        </td>
        <td width="25%">
            <span class="meta-label">Generated:</span> {{ date('d M Y') }}
        </td>
    </tr>
</table>

<!-- TRIAL BALANCE TABLE -->
<table class="trial-table">
    <thead>
        <tr>
            <th width="15%">Account Code</th>
            <th width="45%">Account Name</th>
            <th width="20%">Debit (BDT)</th>
            <th width="20%">Credit (BDT)</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalDebit = 0;
            $totalCredit = 0;
        @endphp
        @foreach($trialBalance as $row)
            @php
                $debit = $row->balance > 0 ? $row->balance : 0;
                $credit = $row->balance < 0 ? abs($row->balance) : 0;

                $totalDebit += $debit;
                $totalCredit += $credit;
            @endphp
            <tr>
                <td>{{ $row->accountcode }}</td>
                <td class="account-name">{{ $row->description }}</td>
                <td class="text-right">{{ $debit }}</td>
                <td class="text-right">{{ $credit }}</td>
            </tr>
        @endforeach

        <!-- TOTAL -->
        <tr class="total-row">
            <td colspan="2" align="right">TOTAL</td>
            <td class="text-right">{{ $totalDebit }}</td>
            <td class="text-right">{{ $totalCredit }}</td>
        </tr>
    </tbody>
</table>

<!-- FOOTER -->
<table class="footer">
    <tr>
        <td class="sign"><span>Prepared By</span></td>
        <td class="sign"><span>Checked By</span></td>
        <td class="sign"><span>Approved By</span></td>
    </tr>
</table>

</body>
</html>
