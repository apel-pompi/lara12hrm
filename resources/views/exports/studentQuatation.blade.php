<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Quotation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif !important;
        }

        body {
            font-size: 12px;
            color: #111827;
            margin: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: top;
        }

        .title-box {
            border: 1px solid #000;
            padding: 8px 20px;
            display: inline-block;
            font-weight: 700;
            font-size: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background: #f9fafb;
            border-radius: 6px;
            overflow: hidden;
        }

        .info-table th {
            width: 200px;
            text-align: left;
            background: #f3f4f6;
            padding: 6px 10px;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            background: #fff;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .grid strong {
            color: #374151;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 11px;
        }

        .table th,
        .table td {
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: right;
        }

        .table th {
            background: #f3f4f6;
            font-weight: 600;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 8px;
        }

        .note {
            margin-top: 15px;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        .grand-total {
            text-align: left;
            font-weight: bold;
            color: #1e3a8a;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <!-- LEFT -->
            <td style="width: 20%; text-align: left;">
                <img src="{{ public_path('storage/company/' . $company->companylogo) }}" alt="logo" width="120">
            </td>

            <!-- CENTER -->
            <td style="width: 55%; text-align: center;">
                <div class="title-box">Student Quotation</div>
            </td>

            <!-- RIGHT -->
            <td style="width: 25%;">
                <table cellpadding="3" cellspacing="0" width="100%" style="font-size: 12px;">
                    @foreach ($quatHd as $item)
                        <tr>
                            <td><strong>SL:</strong> {{ $item->quotation_no }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong> {{ $item->adddate }}</td>
                        </tr>
                        <tr>
                            <td><strong>By:</strong> {{ $item->user->name }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    <!-- Student Info -->
    <table class="info-table">
        <tr>
            <th>Student ID</th>
            <td>{{ $student->student_id }}</td>
        </tr>
        <tr>
            <th>Student Name</th>
            <td>{{ $student->fname }} {{ $student->lname }}</td>
        </tr>
        <tr>
            <th>Student Email</th>
            <td>{{ $student->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>Student Phone</th>
            <td>{{ $student->phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>Destination Country</th>
            <td>{{ $student->country->name ?? '-' }}</td>
        </tr>
    </table>

    <!-- Quotation Details -->
    @foreach ($feesDetails as $value)
        @foreach ($value->deatils as $item)
            <div class="card">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong>Partner:</strong>
                        {{ $item->service->partnerBranch->partner->name }}<br>
                        <small>{{ $item->service->partnerBranch->branch_name }}</small>
                    </div>
                    <div>
                        <strong>Product:</strong>
                        {{ $item->service->product->name }}
                    </div>
                </div>
        @endforeach

        <table class="table mt-2">
            <thead>
                <tr>
                    <th>Purticulars</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Remarks</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $totalAmount = 0;
                @endphp
                @foreach ($feename as $key)
                    @php
                        $totalAmount += $key->amount;
                    @endphp
                    <tr>
                        <td>{{ $key->name }}</td>
                        <td>{{ number_format($key->amount, 2) }}</td>
                        <td>
                            @if ($key->pay_type == 'Payable')
                                Non Refundable
                            @else
                                Refundable
                            @endif
                        </td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td><strong>Grand Total</strong></td>
                    <td colspan="3" class="grand-total">
                        {{ number_format($totalAmount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        </div>
    @endforeach
    <p>in word: {{ $numberTransformer->toWords($totalAmount ?? 0) }} only</p>
    <!-- Note -->
    <div class="note">
        <strong>Note:</strong>
        @foreach ($quatHd as $item)
            {{ $item->notes }}
        @endforeach
    </div>
    <!-- ======= Footer Section ======= -->
    <footer
        style="
    position: fixed;
    bottom: 5px;
    left: 0;
    right: 0;
    width: 100%;
    font-size: 12px;
    text-align: center;
">
        <table width="100%" style="border-collapse: collapse; font-size: 12px;">
            <tr>
                <th style="text-decoration: underline; padding: 5px;">Student</th>
                <th style="text-decoration: underline; padding: 5px;">Counselor</th>
                <th style="text-decoration: underline; padding: 5px;">Approved By</th>
                <th style="text-decoration: underline; padding: 5px;">Authorized By</th>
            </tr>
            <tr>
                <td style="height: 30px;"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th colspan="4" style="padding: 5px;">This is a system generated document</th>
            </tr>
        </table>
    </footer>
</body>

</html>
