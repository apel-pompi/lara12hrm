<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Overview Report</title>
    <style>
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        header {
            position: fixed;
            top: -10px;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            line-height: 20px;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 11px;
        }


        /* Remove dark mode for PDF generation */
        /* DOMPDF doesn't support CSS @class directives well */

        /* Header Section */
        .header {
            border-bottom: 2px solid #2c5282;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }


        /* Report Title */
        .report-title {
            background: linear-gradient(135deg, #2c5282 0%, #4299e1 100%);
            color: black;
            padding: 1px 20px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Summary Info */
        .summary-info {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px 20px;
            margin-top: 25px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 120px;
        }

        .info-value {
            color: #2d3748;
            font-weight: 500;
        }

        .info-separator {
            color: #a0aec0;
            margin: 0 8px;
        }

        /* Data Table - FIXED STRUCTURE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .data-table thead {
            background: linear-gradient(135deg, #2c5282 0%, #4299e1 100%);
            color: black;
        }

        .data-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #2c5282;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table td {
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-lead {
            background: #bee3f8;
            color: #2c5282;
        }

        .status-prospect {
            background: #c6f6d5;
            color: #276749;
        }

        .status-onboard {
            background: #fed7d7;
            color: #c53030;
        }

        .status-achieved {
            background: #e9d8fd;
            color: #553c9a;
        }

        .status-pending {
            background: #fefcbf;
            color: #975a16;
        }

        /* Progress Bar */
        .progress-container {
            width: 100%;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            height: 8px;
            margin-top: 5px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
        }

        /* Totals Section */
        .totals-section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .total-item {
            text-align: center;
        }

        .total-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .total-value {
            font-size: 20px;
            font-weight: 700;
            color: #2c5282;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #e2e8f0;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 20px;
        }

        .signature-item {
            text-align: center;
        }

        .signature-line {
            height: 1px;
            background: #718096;
            margin: 40px auto 8px auto;
            width: 80%;
        }

        .signature-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .signature-name {
            font-size: 11px;
            font-weight: 600;
            color: #2d3748;
            margin-top: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #718096;
        }

        .print-date {
            margin-top: 5px;
        }

        /* Fix for DOMPDF - Ensure proper table structure */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        /* Remove problematic CSS for DOMPDF */
        .dark {
            display: none !important;
        }

        .page-break {
            page-break-after: always;
        }

        /* Print Styles */
        @media print {
            body {
                font-size: 10px;
                margin: 0;
                padding: 0;
            }

            .report-title {
                box-shadow: none;
                margin: 10px 0;
            }

            .data-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="report-title">
            SALES OVERWIEW REPORT
        </div>
    </header>

    
        @php
            // Employee Name
            $employeeName = $employee['empname'] ?: '';

            // Status array
            $statusesArray = $statuses->toArray() ?? [];

            // Total leads
            $totalLeads = array_sum($statusesArray);

            // Status names
            $statusNames = [
                1 => 'Lead',
                2 => 'Prospect',
                3 => 'OnBoard',
                4 => 'Achieved',
                null => 'Pending',
            ];

            // Status colors
            $colors = [
                1 => '#4299e1',
                2 => '#48bb78',
                3 => '#f56565',
                4 => '#9f7aea',
                null => '#ecc94b',
            ];
        @endphp
        <!-- Summary Information -->
        <div class="summary-info">
            <div class="info-item">
                <span class="info-label">Employee Name</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $employeeName }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Report Period</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $monthName ?? 'November' }}, {{ $year ?? '2025' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Generated On</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ now()->format('F j, Y h:i A') }}</span>
            </div>

        </div>
        <!-- Lead Details Table - FIXED STRUCTURE -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center">#</th>
                    <th style="width: 30%">Lead Status</th>
                    <th style="width: 20%; text-align: center">Quantity</th>
                    <th style="width: 20%; text-align: center">Percentage</th>
                    <th style="width: 25%">Progress</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ([1, 2, 3, 4, null] as $status)
                    @php
                        $count = $statusesArray[$status] ?? 0;
                        $percentage = $totalLeads > 0 ? round(($count / $totalLeads) * 100, 1) : 0;
                        $color = $colors[$status] ?? '#ecc94b';
                    @endphp
                    <tr>
                        <td style="text-align: center">{{ $i++ }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($statusNames[$status] ?? 'pending') }}">
                                {{ $statusNames[$status] ?? 'Pending' }}
                            </span>
                        </td>
                        <td style="text-align: center; font-weight: 600">{{ $count }}</td>
                        <td style="text-align: center">{{ $percentage }}%</td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar"
                                    style="width: {{ $percentage }}%; background: {{ $color }};"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center">#</th>
                    <th style="width: 30%">Item Name</th>
                    <th style="width: 20%; text-align: center">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>Quotations</td>
                    <td>{{ number_format($sumQuoat, 2) }}</td>
                </tr>
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>Invoice</td>
                    <td>{{ number_format($sumInvoice, 2) }}</td>
                </tr>
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>Money Recived</td>
                    
                    <td>
                        {{ number_format(
                            $sumMR instanceof \Illuminate\Support\Collection ? $sumMR->sum('netamount') : $sumMR,
                            2,
                        ) }}
                    </td>
                    
                </tr>
            </tbody>
        </table>
       
   

    <!-- Footer -->
    <footer>
        <div class="footer">
            <div>Confidential Document - For Internal Use Only</div>
            <div class="print-date">Printed on: {{ now()->format('F j, Y \a\t h:i A') }}</div>

        </div>
    </footer>
</body>

</html>
