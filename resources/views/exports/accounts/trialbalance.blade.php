<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trial Balance - Branch Wise</title>

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
            margin: 5px 0 5px;
            text-transform: uppercase;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            margin-bottom: 5px;
            color: #555;
        }

        /* ===== META ===== */
        .meta-table {
            width: 100%;
            margin-bottom: 5px;
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
            margin-top: 20px;
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
<div class="title">Trial Balance (Branch Wise)</div>
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
            
        </td>
        <td width="25%" align="right">
            <span class="meta-label">Generated:</span> {{ date('d M Y') }}
        </td>
    </tr>
</table>

<!-- TRIAL BALANCE TABLE -->
<table class="trial-table">
    <thead>
        <tr>
            <th rowspan="2" width="5%">Sl</th>
            <th rowspan="2" width="35%">Account Code & Description</th>
            <th colspan="2">Balance B/F</th>
            <th colspan="2">Current Date Range</th>
            <th colspan="2">Balance C/F</th>
        </tr>
        <tr>
            <th width="7%">Debit</th>
            <th width="7%">Credit</th>
            <th width="7%">Debit</th>
            <th width="7%">Credit</th>
            <th width="7%">Debit</th>
            <th width="7%">Credit</th>
        </tr>
    </thead>

    <tbody>
        @php
            $sl = 1;
            $totBfDr = $totBfCr = 0;
            $totCurDr = $totCurCr = 0;
            $totCfDr = $totCfCr = 0;
        @endphp

        @foreach($trialBalance as $row)
            @php
                $totBfDr += $row->bf_debit;
                $totBfCr += $row->bf_credit;
                $totCurDr += $row->cur_debit;
                $totCurCr += $row->cur_credit;
                $totCfDr += $row->cf_debit;
                $totCfCr += $row->cf_credit;
            @endphp

            <tr>
                <td class="text-center">{{ $sl++ }}</td>
                <td class="text-left">
                    {{ $row->accountcode }} — {{ $row->description }}
                </td>
                <td class="text-right">{{ $row->bf_debit ?: '' }}</td>
                <td class="text-right">{{ $row->bf_credit ?: '' }}</td>
                <td class="text-right">{{ $row->cur_debit ?: '' }}</td>
                <td class="text-right">{{ $row->cur_credit ?: '' }}</td>
                <td class="text-right">{{ $row->cf_debit ?: '' }}</td>
                <td class="text-right">{{ $row->cf_credit ?: '' }}</td>
            </tr>
        @endforeach

        <!-- TOTAL -->
        <tr class="total-row">
            <td colspan="2">Total</td>
            <td class="text-right">{{ number_format($totBfDr,2) }}</td>
            <td class="text-right">{{ number_format($totBfCr,2) }}</td>
            <td class="text-right">{{ number_format($totCurDr,2) }}</td>
            <td class="text-right">{{ number_format($totCurCr,2) }}</td>
            <td class="text-right">{{ number_format($totCfDr,2) }}</td>
            <td class="text-right">{{ number_format($totCfCr,2) }}</td>
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
