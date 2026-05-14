<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        /* ================= FIXED HEADER ================= */
        /* হেডার টেবিলের বর্ডার এবং প্যাডিং ফিক্স করা হয়েছে */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 15px;
            table-layout: fixed;
            /* কলাম সাইজ ফিক্সড রাখার জন্য */
        }

        .header-table td {
            border: none;
            /* হেডারের ভেতরের বর্ডার রিমুভ করা হয়েছে */
            padding: 0 0 10px 0;
            vertical-align: middle;
        }

        .logo-cell {
            width: 30%;
            /* লোগোর জন্য জায়গা */
            text-align: left;
        }

        .company-info-cell {
            width: 70%;
            /* তথ্যের জন্য জায়গা */
            text-align: right;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
        }

        .company-details {
            font-size: 10px;
            color: #555;
            margin-top: 4px;
        }

        /* ================= TITLE & INFO ================= */
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            color: #d35400;
            text-decoration: underline;
        }

        .info-container {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-box {
            width: 50%;
            float: left;
        }

        .info-box p {
            margin: 2px 0;
        }

        .info-box strong {
            width: 70px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* ================= MAIN TABLE ================= */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .main-table th {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 8px;
            font-size: 10px;
            border: 1px solid #2c3e50;
            text-transform: uppercase;
        }

        .main-table td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            word-wrap: break-word;
        }

        /* Helpers */
        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .row-total {
            background-color: #eee;
            font-weight: bold;
        }

        .row-closing {
            background-color: #e8f6f3;
            font-weight: bold;
            color: #16a085;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    @foreach($report as $r)
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @if ($company->companylogo)
                <img src="{{ public_path('storage/company/' . $company->companylogo) }}" style="max-height: 70px; max-width: 150px;">
                @else
                <div style="font-size: 24px; font-weight: bold; color: #ccc;">LOGO</div>
                @endif
            </td>
            <td class="company-info-cell">
                <div class="company-name">{{ $company->companyname }}</div>
                <div class="company-details">
                    {{ $company->address_one }} {{ $company->address_two }}<br>
                    Phone: {{ $company->company_phone }} | Email: {{ $company->company_email }}
                </div>
            </td>
        </tr>
    </table>

    <div class="report-title">SUPPLIER LEDGER REPORT</div>

    <div class="info-container clearfix">
        <div class="info-box">
            <p><strong>Supplier:</strong> {{ $r['info']->name }}</p>
            <p><strong>Address:</strong> {{ $r['info']->subaddress }}</p>
        </div>
        <div class="info-box right">
            <p><strong>Branch:</strong> {{ $r['info']->branchname }}</p>
            <p><strong>Period:</strong> {{ $from_date }} to {{ $to_date }}</p>
            <p><strong>Print Date:</strong> {{ date('d-M-Y H:i A') }}</p>
        </div>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="12%">Date</th>
                <th width="15%">Voucher</th>
                <th width="18%">Description</th>
                <th>Note</th>
                <th width="13%" class="right">Payable</th>
                <th width="13%" class="right">Paid</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-light">
                <td colspan="4" class="right bold">Opening Balance</td>
                <td class="right bold">
                    {{ $r['opening'] < 0 ? number_format(abs($r['opening']),3) : '0.000' }}
                </td>
                <td class="right bold">
                    {{ $r['opening'] > 0 ? number_format($r['opening'],3) : '0.000' }}
                </td>
            </tr>

            @php
            $totalPay = $r['opening'] < 0 ? abs($r['opening']) : 0;
                $totalPaid=$r['opening']> 0 ? $r['opening'] : 0;
                @endphp

                @foreach($r['transactions'] as $t)
                @php
                $pay = $t->baseamt < 0 ? abs($t->baseamt) : 0;
                    $paid = $t->baseamt > 0 ? $t->baseamt : 0;
                    $totalPay += $pay;
                    $totalPaid += $paid;
                    $desc = str_starts_with($t->vouchernumber, 'AP--') ? 'Account Payable' : 'Payment';
                    @endphp
                    <tr>
                        <td class="center">{{ $t->voucherdate }}</td>
                        <td class="bold">{{ $t->vouchernumber }}</td>
                        <td>{{ $desc }}</td>
                        <td><small>{{ $t->notes }}</small></td>
                        <td class="right">{{ number_format($pay,3) }}</td>
                        <td class="right">{{ number_format($paid,3) }}</td>
                    </tr>
                    @endforeach

                    <tr class="row-total">
                        <td colspan="4" class="right">Current Period Total</td>
                        <td class="right">{{ number_format($totalPay,3) }}</td>
                        <td class="right">{{ number_format($totalPaid,3) }}</td>
                    </tr>

                    <tr class="row-closing">
                        <td colspan="4" class="right">Final Closing Balance</td>
                        <td class="right">
                            {{ ($totalPay - $totalPaid) > 0 ? number_format($totalPay - $totalPaid, 3) : '0.000' }}
                        </td>
                        <td class="right">
                            {{ ($totalPaid - $totalPay) > 0 ? number_format($totalPaid - $totalPay, 3) : '0.000' }}
                        </td>
                    </tr>
        </tbody>
    </table>

    @if (!$loop->last)
    <div style="page-break-after: always;"></div>
    @endif
    @endforeach

    <div class="footer">
        This is a computer-generated report. Generated on {{ date('Y-m-d H:i:s') }}
    </div>

</body>

</html>