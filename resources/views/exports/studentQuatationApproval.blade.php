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

                    <tr>
                        <td><strong>SL:</strong> {{ $quatHd->quotation_no }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date:</strong> {{ $quatHd->adddate }}</td>
                    </tr>
                    <tr>
                        <td><strong>By:</strong> {{ $quatHd->user->name }}</td>
                    </tr>

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

    <div class="card">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <strong>Partner:</strong>
                {{ $service->partner }}<br>
                <small>{{ $service->partnerbranch }}</small>
            </div>
            <div>
                <strong>Product:</strong>
                {{ $service->product }}
            </div>
        </div>


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
                @foreach ($fees as $key)
                    @php
                        $totalAmount += $key->quoatamount;
                    @endphp
                    <tr>
                        <td>{{ $key->name }}</td>
                        <td>{{ number_format($key->quoatamount, 2) }}</td>
                        <td>
                            @if ($key->pay_type == 'Revenue')
                                Non Refundable
                            @else
                                {{$key->pay_type}}
                            @endif
                        </td>
                        <td>
                            @if ($key->name == 'File Opening Fee')
                                At the beginning of process
                            @elseif($key->name == 'Application Fee')
                                During process direct payment to University
                            @elseif($key->name == 'Visa Process Fee')
                                After offer letter of services needed
                            @elseif($key->name == 'VISA Fee')
                                After offer letter direct payment to VFS/Embassy
                            @elseif($key->name == 'Service Fee')
                                After Visa
                            @elseif($key->name == 'Tuition Fee')
                                Tuition fees are refundable if the visa application is refused**
                            @endif
                        </td>
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

    <p>in word: {{ $numberTransformer->toWords($totalAmount ?? 0) }} only</p>
    <!-- Note -->
    <div class="note">
        <strong>Note:</strong>

            {{ $quatHd->notes }}

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
