<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Employee Personal Information Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
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
            color: #7f8c8d;
            margin: 3px 0;
        }

        .company-contact {
            font-size: 11px;
            color: #7f8c8d;
            margin: 2px 0;
        }


        .main-container {
            display: table;
            width: 100%;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-header {
            background-color: #2c3e50;
            color: white;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 6px;
            vertical-align: top;
            border-bottom: 1px solid #ecf0f1;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            width: 180px;
            padding-right: 10px;
        }

        .info-value {
            color: #34495e;
        }

        .photo-section {
            width: 100px;
            text-align: center;
            vertical-align: top;
            padding-left: 20px;
        }

        .employee-photo {
            width: 100px;
            height: 100px;
            /* border: 1px solid #bdc3c7;
            background-color: #f8f9fa; */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
        }

        .photo-placeholder {
            font-size: 10px;
            text-align: center;
        }

        .two-column {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .signature-section {
            margin-top: 20px;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .signature-line {
            border-top: 1px solid #2c3e50;
            margin: 40px auto 5px auto;
            width: 80%;
        }

        .signature-label {
            font-size: 11px;
            color: #2c3e50;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <!-- Company Header -->
    <div class="header">
        <h1 class="company-name">{{ $company->companyname }}</h1>
        <p class="company-address">{{ $company->address_one }} {{ $company->address_two }}</p>
        <p class="company-contact">Phone: {{ $company->company_phone }} | Email: {{ $company->company_email }}| Web:
            www.glendonedu.com</p>

        <h2 class="report-title">Employee Personal Information</h2>
    </div>

    <div class="main-container">
        <!-- Personal Information Section -->
        <div class="section">
            <div class="section-header">Personal Information</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Employee Name</td>
                    <td class="info-value">{{ $employees->empname }}</td>
                    <td rowspan="6" class="photo-section">
                        <div class="employee-photo">
                            <div class="photo-placeholder">
                               <img src="{{ public_path('storage/employee/' . $employees->photo) }}" width="110"
                                    alt="">
                            </div>
                        </div>
                        <div class="photo-placeholder">ID: {{ $employees->empid }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Date of Birth</td>
                    <td class="info-value">{{ $employees->dateofbirth }}</td>
                </tr>
                <tr>
                    <td class="info-label">Gender</td>
                    <td class="info-value">
                        @if ($employees->gender == 1)
                            Male
                        @elseif($employees->gender == 2)
                            Female
                        @else
                            Other
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Blood Group</td>
                    <td class="info-value">{{ $employees->blood }}</td>
                </tr>
                <tr>
                    <td class="info-label">Personal Phone</td>
                    <td class="info-value">{{ $employees->phonepersonal }}</td>
                </tr>
                <tr>
                    <td class="info-label">Email ID</td>
                    <td class="info-value">{{ $employees->email }}</td>
                </tr>
            </table>

            <div class="two-column">
                <div class="column">
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Present Address</td>
                        </tr>
                        <tr>
                            <td class="info-value" style="padding-top: 5px;">{{ $employees->present }}</td>
                        </tr>
                    </table>
                </div>
                <div class="column">
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Permanent Address</td>
                        </tr>
                        <tr>
                            <td class="info-value" style="padding-top: 5px;">{{ $employees->permanent }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Official Information Section -->
        <div class="section">
            <div class="section-header">Official Information</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Employee ID</td>
                    <td class="info-value">{{ $employees->empid }}</td>
                    <td rowspan="5" class="photo-section">
                        <div class="employee-photo">
                            <div class="photo-placeholder">
                                <img src="{{ public_path('storage/company/' . $company->companylogo) }}" width="110"
                                    alt="logo">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Joining Date</td>
                    <td class="info-value">{{ $employees->joindate }}</td>
                </tr>
                <tr>
                    <td class="info-label">Branch</td>
                    <td class="info-value">{{ $employees->branch->branchname }}</td>
                </tr>
                <tr>
                    <td class="info-label">Department</td>
                    <td class="info-value">{{ $employees->department->deptname }}</td>
                </tr>
                <tr>
                    <td class="info-label">Designation</td>
                    <td class="info-value">{{ $employees->designation->desname }}</td>
                </tr>
                <tr>
                    <td class="info-label">Official Phone</td>
                    <td class="info-value" colspan="2">{{ $employees->phoneoffice }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Employee Signature</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">HR Manager</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorized Signatory</div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
