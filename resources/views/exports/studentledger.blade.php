<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Ledger</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif !important;
            box-sizing: border-box;
        }

        body {
            font-size: 12px;
            color: #111827;
            margin: 10px;
            background: #fff;
        }

        /* ── Header ───────────────────────────────── */
        .report-header {
            width: 100%;
            border-bottom: 3px solid #1e3a5f;
            margin-bottom: 18px;
            padding-bottom: 12px;
        }

        .header-row {
            width: 100%;
            display: table;
        }

        .header-col {
            display: table-cell;
            vertical-align: middle;
        }

        .company-logo img {
            max-width: 110px;
        }

        .company-info {
            text-align: left;
            padding-left: 10px;
        }

        .company-info h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 11px;
            color: #374151;
        }

        .report-title {
            text-align: center;
        }

        .report-title h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #1e3a5f;
        }

        .report-title p {
            margin: 4px 0 0 0;
            font-size: 10px;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        .report-meta {
            text-align: right;
            font-size: 11px;
            color: #374151;
        }

        .report-meta p {
            margin: 2px 0;
        }

        /* ── Student Info Card ────────────────────── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
        }

        .info-table th {
            width: 180px;
            text-align: left;
            background: #eef2f7;
            padding: 7px 12px;
            font-weight: 600;
            font-size: 11px;
            color: #1e3a5f;
            border-bottom: 1px solid #d1d5db;
            border-right: 1px solid #d1d5db;
        }

        .info-table td {
            padding: 7px 12px;
            font-size: 11px;
            border-bottom: 1px solid #e5e7eb;
        }

        /* ── Section Label ────────────────────────── */
        .section-label {
            font-size: 13px;
            font-weight: 700;
            color: #1e3a5f;
            margin: 16px 0 8px 0;
            padding-bottom: 4px;
            border-bottom: 2px solid #dbeafe;
        }

        /* ── Ledger Table ─────────────────────────── */
        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            border: 1px solid #c7d2e0;
        }

        .ledger-table thead th {
            background: #1e3a5f;
            color: #ffffff;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-right: 1px solid #2d4a6f;
        }

        .ledger-table thead th:last-child {
            border-right: none;
        }

        .ledger-table thead th.text-right {
            text-align: right;
        }

        .ledger-table thead th.text-center {
            text-align: center;
        }

        .ledger-table tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #eef0f2;
        }

        .ledger-table tbody td:last-child {
            border-right: none;
        }

        .ledger-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .ledger-table tbody tr.refund-row {
            background: #fff1f2;
        }

        .ledger-table tbody tr:hover {
            background: #eef2f7;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ── Status Badges ────────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .badge-posted {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-unposted {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-refund {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* ── Total / Summary Footer ───────────────── */
        .summary-row {
            width: 100%;
            display: table;
            margin-top: 15px;
        }

        .summary-left {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-right: 20px;
        }

        .summary-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }

        .summary-box {
            border: 1px solid #c7d2e0;
            border-radius: 6px;
            overflow: hidden;
        }

        .summary-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-box table td {
            padding: 8px 12px;
            font-size: 11px;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-box table td:first-child {
            font-weight: 600;
            color: #374151;
            background: #f9fafb;
            width: 60%;
            border-right: 1px solid #e5e7eb;
        }

        .summary-box table td:last-child {
            text-align: right;
            font-weight: 700;
            color: #111827;
        }

        .summary-box .total-row td {
            background: #1e3a5f;
            color: #ffffff !important;
            border-top: 2px solid #0f172a;
            font-size: 13px;
        }

        .summary-box .total-row td:first-child {
            background: #1e3a5f;
            color: #ffffff;
        }

        .summary-box .total-row td:last-child {
            color: #ffffff;
        }

        /* ── Note / Empty State ───────────────────── */
        .empty-state {
            text-align: center;
            padding: 30px 0;
            color: #9ca3af;
            font-size: 12px;
            border: 1px dashed #d1d5db;
            border-radius: 6px;
            margin-top: 8px;
        }

        .note-text {
            font-size: 10px;
            color: #4b5563;
            margin-top: 10px;
            padding: 8px 12px;
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            border-radius: 0 4px 4px 0;
            line-height: 1.4;
        }

        /* ── Page Footer ──────────────────────────── */
        .page-footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #9ca3af;
            display: table;
            width: 100%;
        }

        .page-footer .left {
            display: table-cell;
            text-align: left;
        }

        .page-footer .right {
            display: table-cell;
            text-align: right;
        }

        /* ── Print Styles ─────────────────────────── */
        @media print {
            body {
                margin: 0;
                padding: 5mm;
            }

            .ledger-table thead th {
                background: #1e3a5f !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }

            .summary-box .total-row td {
                background: #1e3a5f !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="report-header">
        <div class="header-row">
            <!-- LEFT: Logo & Company -->
            <div class="header-col company-logo">
                @if($company->companylogo)
                <img src="{{ public_path('storage/company/' . $company->companylogo) }}" alt="Company Logo">
                @endif
            </div>

            <!-- CENTER: Report Title -->
            <div class="header-col report-title">
                <h1>Student Ledger</h1>
                <p>Financial Transaction Statement</p>
            </div>

            <!-- RIGHT: Meta Info -->
            <div class="header-col report-meta">
                <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>
                <p><strong>Report ID:</strong> SL-{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Generated By:</strong> {{ auth()->user()->name ?? 'System' }}</p>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <table class="info-table">
        <tr>
            <th>Student ID</th>
            <td>{{ $student->student_id }}</td>
            <th>Full Name</th>
            <td>{{ $student->fname }} {{ $student->lname }}</td>
        </tr>
        <tr>
            <th>Email Address</th>
            <td>{{ $student->email ?? '-' }}</td>
            <th>Contact Number</th>
            <td>{{ $student->phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>Destination</th>
            <td colspan="3">{{ $student->country->name ?? '-' }}</td>
        </tr>
    </table>

    <!-- Section Label -->
    <div class="section-label">Statement of Transactions</div>

    <!-- Ledger Data Table -->
    @if ($data && count($data) > 0)
    @php
    $totalReceived = 0;
    $totalRefund = 0;
    @endphp
    <table class="ledger-table">
        <thead>
            <tr>
                <th style="width:35px" class="text-center">#</th>
                <th style="width:85px">Date</th>
                <th style="width:95px">M.R No</th>
                <th>Particulars</th>
                <th style="width:110px" class="text-right">Received</th>
                <th style="width:110px" class="text-right">Refund</th>
                <th style="width:85px" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $value)
            @php
            $isRefund = (strtoupper($value['mrstatus']) == "REFUND");
            if ($isRefund) {
            $totalRefund += (float)$value['primeamt'];
            } else {
            $totalReceived += (float)$value['primeamt'];
            }
            @endphp
            <tr class="{{ $isRefund ? 'refund-row' : '' }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $value['mrdate'] }}</td>
                <td>{{ $value['mrno'] }}</td>
                <td>
                    {{ $value['feesname'] }}
                    @if($isRefund)
                    <small style="color: #991b1b; display: block;">(Refund Adjustment)</small>
                    @endif
                </td>
                <td class="text-right">
                    {{ !$isRefund ? number_format($value['primeamt'], 2) : '0.00' }}
                </td>
                <td class="text-right">
                    {{ $isRefund ? number_format($value['primeamt'], 2) : '0.00' }}
                </td>
                <td class="text-center">
                    @if ($value['primeamt'])
                    <span class="badge badge-posted">
                        GL Posted
                    </span>
                    @else
                    <span class="badge badge-unposted">Unposted</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-row">
        <div class="summary-left">
            <div class="note-text">
                <strong>Important Note:</strong><br>
                This statement summarizes all financial interactions for the student.
                Amounts marked as "Refunded" indicate payments returned to the student or adjustments made.
                The Net Balance reflects the final confirmed amount held by the institution.
            </div>
        </div>
        <div class="summary-right">
            <div class="summary-box">
                <table>
                    <tr>
                        <td>Total Received Amount</td>
                        <td>{{ number_format($totalReceived, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Refund Amount</td>
                        <td>{{ number_format($totalRefund, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Net Balance</td>
                        <td>{{ number_format($totalReceived - $totalRefund, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="empty-state">
        No financial transactions found for this student record.
    </div>
    @endif

    <!-- Page Footer -->
    <div class="page-footer">
        <div class="left">
            Confidentially Managed by {{ config('app.name', 'HRM System') }} &copy; {{ date('Y') }}
        </div>
        <div class="right">
            Print Timestamp: {{ now()->format('d M Y, h:i A') }}
        </div>
    </div>

</body>

</html>