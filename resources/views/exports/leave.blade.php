<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark'=> ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <title>Leave Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 5px 0;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
        }

        .company-address {
            font-size: 12px;
            margin: 3px 0;
        }

        .company-contact {
            font-size: 11px;
            margin: 2px 0;
        }

        .company-info {
            font-size: 10px;
            margin: 3px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 10px 0;
            vertical-align: top;
        }

        .label {
            width: 35%;
            font-weight: normal;
            padding-right: 5px;
        }

        .value {
            width: 65%;
            font-weight: bold;
            border-bottom: 1px dotted black;
        }

        .leave-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }

        .leave-table th,
        .leave-table td {
            border: 1px solid black;
            padding: 15px;
            text-align: center;
        }

        .leave-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .signature-section {
            margin-top: 15px;
        }

        .signature-row {
            margin-bottom: 12px;
        }

        .signature-line {
            border-bottom: 1px solid black;
            margin-top: 20px;
            width: 70%;
        }

        .signature-note {
            font-size: 9px;
            margin-top: 2px;
        }

        .notes {
            margin-top: 15px;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .notes-list {
            margin: 0;
            padding-left: 15px;
        }

        .notes-list li {
            margin-bottom: 13px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1 class="company-name">{{ $company->companyname }}</h1>
            <p class="company-address">{{ $company->address_one }} {{ $company->address_two }}</p>
            <p class="company-contact">Phone: {{ $company->company_phone }} | Email: {{ $company->company_email }}| Web:
                www.glendonedu.com</p>
            <div class="title">APPLICATION FOR LEAVE</div>
            <div class="company-info">Ref No: HR/LA/{{ date('Y') }}/{{ $leave->id }} | Date: {{ $monthname }},
                {{ $yearname }}</div>
        </div>

        <!-- Employee Information -->
        <table class="info-table">
            <tr>
                <td class="label">1. Name</td>
                <td class="value">{{ $leave->employee->empname }}</td>
            </tr>
            <tr>
                <td class="label">2. Designation, Department/Section/Place</td>
                <td class="value">{{ $leave->employee->designation->desname }},
                    {{ $leave->employee->department->deptname }}</td>
            </tr>
            <tr>
                <td class="label">3. Contact address during leave period</td>
                <td class="value">{{ $leave->contact_address }}</td>
            </tr>
            <tr>
                <td class="label">4. Leave Period</td>
                <td class="value">From: {{ $fromdate }} To: {{ $todate }} Total: {{ $leave->days }} Days
                </td>
            </tr>
            <tr>
                <td class="label">5. Leave Type</td>
                <td class="value">{{ $leave->leavePlan->leavename }}</td>
            </tr>
            <tr>
                <td class="label">6. Reason for availing leave</td>
                <td class="value">{{ $leave->reason }}</td>
            </tr>
            <tr>
                <td class="label">7. Signature of applicant with date</td>
                <td class="value">&nbsp;</td>
            </tr>
        </table>

        <!-- Leave Status -->
        <div style="font-weight: bold; margin: 8px 0;">8. Leave Status</div>
        <table class="leave-table">
            <tr>
                <th>Type</th>
                <th>Entitlement</th>
                <th>Availed</th>
                <th>Balance</th>
                <th>Now Applied</th>
                <th>Remarks</th>
            </tr>
            
            @foreach ($allleave as $item)
                <tr>
                    <td>{{ $item->leavename }}</td>
                    <td>{{ $item->allow_days }}</td>
                    <td>{{ $item->taken }}</td>
                    <td>{{ $item->balance }}</td>
                    <td>{{ $item->nowapply }}</td>
                    <td></td>
                </tr>
            @endforeach
        </table>

        <!-- Additional Information -->
        <table class="info-table">
            <tr>
                <td class="label">9. Officer in charge during absence</td>
                <td class="value">{{ $leave->substituteEmployee->empname }} ({{ $leave->substituteEmployee->designation->desname }},
                    {{ $leave->substituteEmployee->department->deptname }})</td>
            </tr>
        </table>

        <!-- Signature Section -->
        <div class="signature-section">
            <table class="info-table">
                <tr>
                    <td class="label">10. Recommendation by Department Head</td>
                    <td class="value">
                        <div class="signature-line"></div>
                        <div class="signature-note">Signature with Date & Stamp</div>
                    </td>
                </tr>
                <tr>
                    <td class="label">11. Verification by HR</td>
                    <td class="value">
                        <div class="signature-line"></div>
                        <div class="signature-note">Signature with Date & Stamp</div>
                    </td>
                </tr>
                <tr>
                    <td class="label">12. Approval by Sanctioning Authority</td>
                    <td class="value">
                        <div class="signature-line"></div>
                        <div class="signature-note">Signature with Date & Stamp</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Notes Section -->
        <div class="notes">
            <div class="notes-title">Note:</div>
            <ol class="notes-list">
                <li>Employee to apply prior to leave taken with the recommendation of departmental head.</li>
                <li>It must be verified by HR.</li>
                <li>Verified application is taken to leave sanctioning authority.</li>
                <li>Two copies of application be submitted one for HR and one for employee.</li>
            </ol>
        </div>
    </div>
</body>

</html>
