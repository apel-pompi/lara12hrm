<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
        }

        .company-info {
            font-size: 10px;
            color: #555;
        }

        /* ================= TITLE ================= */
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0 5px;
        }

        .period {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
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

    {{-- HEADER --}}
    <div class="header">
        <div class="company-name">{{ $company->companyname }}</div>
        <div class="company-info">
            {{ $company->address_one }} | {{ $company->company_phone }}
        </div>
    </div>

    {{-- TITLE --}}
    <div class="title">Cash Flow Statement</div>
    <div class="period">
        For the period {{ date('d M Y', strtotime($from_date)) }}
        to {{ date('d M Y', strtotime($to_date)) }}
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
                <td class="amount">{{ number_format($openingCash, 2) }}</td>
            </tr>

            {{-- OPERATING ACTIVITIES --}}
            <tr>
                <td class="section">Cash Flow from Operating Activities</td>
                <td></td>
            </tr>

            @foreach ($operating as $row)
            <tr>
                <td class="sub">{{ $row->description }}</td>
                <td class="amount">{{ number_format($row->amount, 2) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td>Net Cash from Operating Activities</td>
                <td class="amount">{{ number_format($netOperating, 2) }}</td>
            </tr>

            {{-- INVESTING ACTIVITIES --}}
            <tr>
                <td class="section">Cash Flow from Investing Activities</td>
                <td></td>
            </tr>

            @foreach ($investing as $row)
            <tr>
                <td class="sub">{{ $row->description }}</td>
                <td class="amount">{{ number_format($row->amount, 2) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td>Net Cash from Investing Activities</td>
                <td class="amount">{{ number_format($netInvesting, 2) }}</td>
            </tr>

            {{-- FINANCING ACTIVITIES --}}
            <tr>
                <td class="section">Cash Flow from Financing Activities</td>
                <td></td>
            </tr>

            @foreach ($financing as $row)
            <tr>
                <td class="sub">{{ $row->description }}</td>
                <td class="amount">{{ number_format($row->amount, 2) }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td>Net Cash from Financing Activities</td>
                <td class="amount">{{ number_format($netFinancing, 2) }}</td>
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