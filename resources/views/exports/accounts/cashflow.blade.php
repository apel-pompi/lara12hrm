<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <title>Cash Flow Statement Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 8px;
            font-size: 11px;
        }

        th {
            border-bottom: 2px solid #000;
            text-align: left;
        }

        td.amount {
            text-align: right;
            width: 120px;
        }

        .section {
            font-weight: bold;
            padding-top: 10px;
        }

        .sub {
            padding-left: 20px;
        }

        .total {
            font-weight: bold;
            border-top: 1px solid #000;
        }

        .grand-total {
            font-weight: bold;
            border-top: 2px solid #000;
        }

        /* ================= FOOTER ================= */
        .footer {
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

    <div class="report-title">
        Cash Flow Statement Report
    </div>
    <div class="report-title-section">
        <table class="meta-table">
            <tr>
                <td width="50%">
                    <div class="meta-label">Report Period</div>
                    <div class="meta-value">{{ date('M d, Y', strtotime($from_date)) }} - {{ date('M d, Y', strtotime($to_date)) }}</div>
                </td>
                <td align="right">
                    <div class="meta-label">Branch</div>
                    <div class="meta-value">{{ $branch->branchname ?? 'All Branches' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Particulars</th>
                <th class="amount">Amount (BDT)</th>
            </tr>
        </thead>

        <tbody>

            {{-- OPENING --}}
            <tr>
                <td class="section">Opening Cash & Cash Equivalent</td>
                <td class="amount">{{ number_format($openingCash, 3) }}</td>
            </tr>

            {{-- OPERATING ACTIVITIES --}}
            <tr>
                <td class="section">Cash Flow from Operating Activities</td>
                <td></td>
            </tr>

            @foreach ($operating as $row)
            <tr>
                <td class="sub">{{ $row->description }}</td>
                <td class="amount">{{ number_format($row->amount, 3) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td>Net Cash from Operating Activities</td>
                <td class="amount">{{ number_format($netOperating, 3) }}</td>
            </tr>

            {{-- INVESTING ACTIVITIES --}}
            <tr>
                <td class="section">Cash Flow from Investing Activities</td>
                <td></td>
            </tr>

            @foreach ($investing as $row)
            <tr>
                <td class="sub">{{ $row->description }}</td>
                <td class="amount">{{ number_format($row->amount, 3) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td>Net Cash from Investing Activities</td>
                <td class="amount">{{ number_format($netInvesting, 3) }}</td>
            </tr>

            {{-- FINANCING ACTIVITIES --}}
            <tr>
                <td class="section">Cash Flow from Financing Activities</td>
                <td></td>
            </tr>

            @foreach ($financing as $row)
            <tr>
                <td class="sub">{{ $row->description }}</td>
                <td class="amount">{{ number_format($row->amount, 3) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td>Net Cash from Financing Activities</td>
                <td class="amount">{{ number_format($netFinancing, 3) }}</td>
            </tr>

            {{-- CLOSING --}}
            <tr class="grand-total">
                <td>Closing Cash & Cash Equivalent</td>
                <td class="amount">
                    {{ number_format(
                    $openingCash + $netOperating + $netInvesting + $netFinancing,
                2) }}
                </td>
            </tr>

        </tbody>
    </table>


    <!-- FOOTER -->
    <!-- <table class="footer">
        <tr>
            <td class="sign"><span>Prepared By</span></td>
            <td class="sign"><span>Checked By</span></td>
            <td class="sign"><span>Approved By</span></td>
        </tr>
    </table> -->

</body>

</html>