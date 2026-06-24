<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="UTF-8">
    <title>Balance Sheet</title>

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            color: #000;
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
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-title-section {
            margin-bottom: 25px;
        }

        .meta-table {
            width: 100%;
            background: #E5E7EB;
            border-radius: 6px;
            padding: 10px;
        }

        .meta-label {
            font-size: 11px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
        }

        .meta-value {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        .text-right {
            text-align: right;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            font-size: 14px;
            padding: 6px;
            border-bottom: 2px solid #000;
            text-align: center;
        }

        .col-particular {
            text-align: left;
        }

        .col-amount {
            text-align: right;
            width: 180px;
        }

        .account-type {
            font-size: 18px;
            font-weight: bold;
            padding-top: 15px;
            padding-bottom: 5px;
        }

        .group-name {
            font-size: 15px;
            font-weight: bold;
            padding-left: 10px;
            padding-top: 6px;
        }

        .group-three-name {
            font-size: 13px;
            font-weight: bold;
            padding-left: 20px;
            padding-top: 4px;
            color: #333;
        }

        .group-four-name {
            font-size: 12px;
            font-weight: bold;
            padding-left: 32px;
            padding-top: 3px;
            color: #444;
        }

        .ledger-name {
            padding-left: 48px;
        }

        .amount {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 6px;
        }

        .grand-total td {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    @php
    $yearmonth = date("M Y", strtotime($enddate));

    function formatAmount($amount) {
    return $amount < 0
        ? '(' . number_format(abs($amount), 2) . ')'
        : number_format($amount, 2);
        }
        @endphp

        {{-- ================= HEADER ================= --}}
        <!--=================HEADER=================-->
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

        <!-- ================= TITLE ================= -->
        <div class="report-title">
            Balance Sheet
        </div>

        <div class="report-title-section">
            <table class="meta-table">
                <tr>

                    <td width="33%">
                        <div class="meta-label">Branch</div>
                        <div class="meta-value">{{ $branch->branchname ?? 'All Branch' }}</div>
                    </td>
                    <td width="33%">
                        <div class="meta-label" align="center">As At</div>
                        <div class="meta-value" align="center">{{ $yearmonth }}</div>
                    </td>
                    <td width="34%" class="text-right">
                        <div class="meta-label">Period</div>
                        <div class="meta-value">{{ $startdate }} to {{ $enddate }}</div>
                    </td>
                </tr>
            </table>
        </div>


        {{-- ================= TABLE ================= --}}
        <table>
            <thead>
                <tr>
                    <th class="col-particular">PARTICULARS</th>
                    <th class="col-amount">CURRENT PERIOD</th>
                    <th class="col-amount">YEAR-TO-DATE</th>
                </tr>
            </thead>

            <tbody>
                @foreach($groupedAssets as $groupone_name => $groupTwos)

                {{-- LEVEL 1: ACCOUNT TYPE (ASSETS / LIABILITIES) --}}
                <tr>
                    <td colspan="3" class="account-type">{{ strtoupper($groupone_name) }}</td>
                </tr>

                @foreach($groupTwos as $groupTwoName => $groupThrees)

                {{-- LEVEL 2: GROUP TWO --}}
                <tr>
                    <td colspan="3" class="group-name">{{ strtoupper($groupTwoName) }}</td>
                </tr>

                @foreach($groupThrees as $groupThreeName => $groupFours)

                {{-- LEVEL 3: GROUP THREE --}}
                <tr>
                    <td colspan="3" class="group-three-name">{{ strtoupper($groupThreeName) }}</td>
                </tr>

                @foreach($groupFours as $groupFourName => $ledgers)

                {{-- LEVEL 4: GROUP FOUR --}}
                <tr>
                    <td colspan="3" class="group-four-name">{{ strtoupper($groupFourName) }}</td>
                </tr>

                {{-- LEDGER ITEMS --}}
                @foreach($ledgers as $row)
                @if(isset($row->ledger_name))
                <tr>
                    <td class="ledger-name">{{ $row->ledger_name }}</td>
                    <td class="amount">{{ formatAmount($row->balance) }}</td>
                    <td class="amount">{{ formatAmount($row->balance) }}</td>
                </tr>
                @else
                {{-- Summary mode: show balance at groupfour level --}}
                <tr>
                    <td class="ledger-name"></td>
                    <td class="amount">{{ formatAmount($row->balance) }}</td>
                    <td class="amount">{{ formatAmount($row->balance) }}</td>
                </tr>
                @endif
                @endforeach

                @endforeach {{-- end groupFours --}}

                @endforeach {{-- end groupThrees --}}

                {{-- SUBTOTAL: GROUP TWO --}}
                <tr class="total-row">
                    <td style="text-align:right; padding-left:10px;">Total {{ strtoupper($groupTwoName) }}</td>
                    <td class="amount">
                        {{ formatAmount(collect($groupThrees)->flatten()->sum('balance')) }}
                    </td>
                    <td class="amount">
                        {{ formatAmount(collect($groupThrees)->flatten()->sum('balance')) }}
                    </td>
                </tr>

                @endforeach {{-- end groupTwos --}}

                {{-- GRAND TOTAL: ACCOUNT TYPE --}}
                <tr class="grand-total">
                    <td style="text-align:right;">Total {{ strtoupper($groupone_name) }}</td>
                    <td class="amount">
                        {{ formatAmount(collect($groupTwos)->flatten()->sum('balance')) }}
                    </td>
                    <td class="amount">
                        {{ formatAmount(collect($groupTwos)->flatten()->sum('balance')) }}
                    </td>
                </tr>

                @endforeach {{-- end groupedAssets --}}
            </tbody>
        </table>

</body>

</html>