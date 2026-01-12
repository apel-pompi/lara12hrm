<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Balance Sheet</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            color: #000;
        }

        /* ================= HEADER ================= */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 4px;
        }

        .report-subtitle {
            font-size: 11px;
            margin-top: 2px;
        }

        /* ================= META ================= */
        .meta {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .meta td {
            padding: 2px 4px;
        }

        /* ================= TABLE ================= */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 4px 5px;
        }

        th {
            font-weight: bold;
            border-bottom: 1.5px solid #000;
        }

        .amount {
            text-align: right;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-top: 6px;
        }

        .group-title {
            font-weight: bold;
            padding-top: 6px;
        }

        .total-row {
            font-weight: bold;
            border-top: 1.5px solid #000;
        }

        .grand-total {
            font-weight: bold;
            border-top: 2.5px solid #000;
            border-bottom: 2.5px solid #000;
        }

        /* ================= LAYOUT ================= */
        .two-column {
            width: 100%;
        }

        .column {
            width: 50%;
            vertical-align: top;
        }

        .divider {
            border-right: 1px solid #000;
        }

        /* ================= FOOTER ================= */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9px;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <div class="company-name">{{ $company }}</div>
        <div class="report-title">BALANCE SHEET</div>
        <div class="report-subtitle">
            As at 
        </div>
    </div>

    {{-- ================= META ================= --}}
    <table class="meta">
        <tr>
            <td width="50%">
                <strong>Branch:</strong> {{ $branch }}
            </td>
            <td width="50%" align="right">
                <strong>Currency:</strong> BDT
            </td>
        </tr>
    </table>

    
    <table class="two-column">
        <tr>
           
            <td class="column divider">
                <table>
                    <tr>
                        <th align="left">ASSETS</th>
                        <th class="amount">Amount</th>
                    </tr>

                    <tr>
                        <td colspan="2" class="group-title">Non-Current Assets</td>
                    </tr>
                   
                        <tr>
                            <td></td>
                            <td class="amount">/td>
                        </tr>
                   
                    <tr class="total-row">
                        <td>Total Non-Current Assets</td>
                        <td class="amount"></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <tr>
                        <td colspan="2" class="group-title">Current Assets</td>
                    </tr>
                   
                        <tr>
                            <td></td>
                            <td class="amount"></td>
                        </tr>
                    
                    <tr class="total-row">
                        <td>Total Current Assets</td>
                        <td class="amount"></td>
                    </tr>

                    <tr class="grand-total">
                        <td>TOTAL ASSETS</td>
                        <td class="amount"></td>
                    </tr>
                </table>
            </td>

           
            <td class="column">
                <table>
                    <tr>
                        <th align="left">LIABILITIES & EQUITY</th>
                        <th class="amount">Amount</th>
                    </tr>

                    <tr>
                        <td colspan="2" class="group-title">Equity</td>
                    </tr>
                    
                        <tr>
                            <td></td>
                            <td class="amount"></td>
                        </tr>
                    
                    <tr class="total-row">
                        <td>Total Equity</td>
                        <td class="amount"></td>
                    </tr>

                    <tr><td colspan="2">&nbsp;</td></tr>

                    <tr>
                        <td colspan="2" class="group-title">Liabilities</td>
                    </tr>

                    
                        <tr>
                            <td></td>
                            <td class="amount"></td>
                        </tr>
                    

                    
                        <tr>
                            <td></td>
                            <td class="amount"></td>
                        </tr>
                    

                    <tr class="grand-total">
                        <td>TOTAL LIABILITIES & EQUITY</td>
                        <td class="amount"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ================= FOOTER ================= --}}
    <div class="footer">
        Printed on 
    </div>

</body>
</html>
