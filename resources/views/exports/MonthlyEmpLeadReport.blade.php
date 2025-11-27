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


        .report-title {
            background-color: #2c3e50;
            color: white;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 4px;
            text-align: center;
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

    <h2 class="report-title">Monthly Lead Information</h2>

    <table>
        <tr>
            <th>Employee Name</th>
            <th>:</th>
            <th>Md. Kawsar</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>:</th>
            <th>Nov, 2025</th>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <th>Sl</th>
            <th>Lead Name</th>
            <th>Lead Qty</th>
            <th>%(All Lead)</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

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
                    <div class="signature-label">Accounts</div>
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
