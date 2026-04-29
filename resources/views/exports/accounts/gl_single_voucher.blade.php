<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <title>Single Voucher</title>

    <style>
        @page {
            margin: 0.8cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
            margin: 0;
        }

        /* ================= HEADER ================= */
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 5px;
        }

        .logo {
            width: 90px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .company-info {
            text-align: right;
            font-size: 10px;
            line-height: 1.4;
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
            padding: 5px;
        }

        /* ================= VOUCHER INFO ================= */
        .voucher-info {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .voucher-info td {
            padding: 4px 6px;
            font-size: 10px;
        }

        .label {
            font-weight: bold;
            width: 20%;
        }

        .value {
            width: 30%;
        }

        .text-right {
            text-align: right;
        }

        /* ================= TABLE ================= */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10px;
            text-align: center;
            background: #f2f2f2;
        }

        table.data td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row th {
            font-weight: bold;
            background: #f9f9f9;
        }

        /* ================= FOOTER ================= */
        .footer {
            margin-top: 65px;
            width: 100%;
        }

        .signature {
            width: 33%;
            text-align: center;
            font-size: 10px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 35px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header">
        <tr>
            <td class="logo">
                @if ($company->companylogo)
                <img src="{{ public_path('storage/company/' . $company->companylogo) }}" width="120">
                @endif
            </td>
            <td>
                <div class="company-name">{{ $company->companyname }}</div>
                <div class="company-info">
                    {{ $company->address_one }}<br>
                    {{ $company->address_two }}<br>
                    {{ $company->company_phone }} | {{ $company->company_email }}
                </div>
            </td>
        </tr>
    </table>
    @php
    $voucher_name = '';
    if (substr($voucher->vouchernumber, 0, 4) == 'PAY-') {
    $voucher_name = 'Payment Voucher';
    } elseif (substr($voucher->vouchernumber, 0, 4) == 'RCV-') {
    $voucher_name = 'Receipt Voucher';
    } elseif (substr($voucher->vouchernumber, 0, 4) == 'REV-') {
    $voucher_name = 'Reverse Voucher';
    } elseif (substr($voucher->vouchernumber, 0, 4) == 'JV--') {
    $voucher_name = 'Journal Voucher';
    } elseif (substr($voucher->vouchernumber, 0, 4) == 'OB--') {
    $voucher_name = 'Opening Balance Voucher';
    }
    @endphp
    <!-- TITLE -->
    <div class="title">{{ $voucher_name }}</div>

    <!-- VOUCHER INFO -->
    <table class="voucher-info">
        <tr>
            <td class="label">Voucher No</td>
            <td class="value">: {{ $voucher->vouchernumber }}</td>

            <td class="label">Date</td>
            <td class="value">: {{ $voucher->voucherdate }}</td>

            <td class="label text-right">Branch</td>
            <td class="value text-right">: {{ $voucher->branch->branchname }}</td>
        </tr>

        <tr>

            <td class="label">Status</td>
            <td class="value">: {{ $voucher->status }}</td>

            <td class="label">Year</td>
            <td class="value">: {{ $voucher->yearname }}</td>

            <td class="label text-right">Period</td>
            <td class="value text-right">: {{ $voucher->monthname }}</td>
        </tr>

        <tr>
            <td class="label">Reference</td>
            <td class="value" colspan="5">: {{ $voucher->referance }}</td>
        </tr>

        <tr>
            <td class="label">Notes</td>
            <td class="value" colspan="5">: {{ $voucher->notes }}</td>
        </tr>
    </table>

    <!-- DETAILS TABLE -->
    <table class="data">
        <thead>
            <tr>
                <th width="15%">Account Code</th>
                <th width="20%">Account Description</th>
                <th width="40%">Notes</th>
                <th width="10%">Debit</th>
                <th width="10%">Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
            <tr>
                <td>{{ $row['accountcode'] }}</td>
                <td>{{ $row['description'] }}</td>
                <td>{{ $row['notes'] }}</td>
                <td class="text-right">{{ $row['debit'] ? number_format($row['debit'], 3) : '' }}</td>
                <td class="text-right">{{ $row['credit'] ? number_format($row['credit'], 3) : '' }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <th colspan="3" class="text-right">Grand Total</th>
                <th class="text-right">{{ number_format($totalDebit, 3) }}</th>
                <th class="text-right">{{ number_format($totalCredit, 3) }}</th>
            </tr>
        </tbody>
    </table>

    <!-- FOOTER / SIGNATURE -->
    <table class="footer">
        <tr>
            <td class="signature">
                <div class="signature-line"></div>
                Prepared By
            </td>
            <td class="signature">
                <div class="signature-line"></div>
                Checked By
            </td>
            <td class="signature">
                <div class="signature-line"></div>
                Authorized By
            </td>
        </tr>
    </table>

</body>

</html>