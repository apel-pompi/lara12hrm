<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Chart of Accounts</title>
    <style>
        /* PDF specific resets */
        @page { margin: 0.5cm; }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        /* Header Styling */
        .company-header {
            width: 100%;
            border-bottom: 2px solid #444;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .company-logo { width: 100px; }
        .company-info { text-align: right; }
        .company-name { font-size: 20px; font-weight: bold; color: #1a202c; }
        
        .report-title {
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 20px;
            color: #2d3748;
            border-left: 5px solid #4a5568;
            padding-left: 10px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            padding: 10px 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        td {
            padding: 8px 5px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }

        /* Hierarchical Indentation & Styling */
        .level-1 { background-color: #f1f5f9; font-weight: bold; font-size: 13px; }
        .level-2 { font-weight: bold; font-size: 12px; color: #1a202c; }
        .level-2 td { padding-left: 15px; }
        .level-3 { font-weight: bold; color: #1a202c; }
        .level-3 td { padding-left: 30px; }
        .account-row td { padding-left: 45px; color: #1a202c; }

        .text-center { text-align: center; }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            text-transform: uppercase;
        }
        .status-active { color: #047857; }
        .status-inactive { color: #b91c1c; }
    </style>
</head>

<body>

    <table class="company-header">
        <tr>
            <td class="company-logo">
                @if($company->companylogo)
                    <img src="{{ public_path('storage/company/' . $company->companylogo) }}" width="100">
                @endif
            </td>
            <td class="company-info">
                <div class="company-name">{{ $company->companyname }}</div>
                <div>{{ $company->address_one }}</div>
                <div>{{ $company->address_two }}</div>
                <div>{{ $company->company_phone }} | {{ $company->company_email }}</div>
            </td>
        </tr>
    </table>

    <div class="report-title">Chart of Accounts List</div>

    <table>
        <thead>
            <tr>
                <th width="15%">Code</th>
                <th width="45%">Description</th>
                <th width="10%">Usage</th>
                <th width="20%">Cash Nature</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $groupOne)
                <tr class="level-1">
                    <td colspan="5">{{ $groupOne->groupone }} — {{ $groupOne->description }}</td>
                </tr>

                @foreach ($groupOne->GroupTwo as $groupTwo)
                    <tr class="level-2">
                        <td colspan="5">{{ $groupTwo->grouptwo }} — {{ $groupTwo->description }}</td>
                    </tr>

                    @foreach ($groupTwo->GroupThree as $groupThree)
                        <tr class="level-3">
                            <td colspan="5">{{ $groupThree->groupthree }} — {{ $groupThree->description }}</td>
                        </tr>

                        @foreach ($groupThree->chartOfAccounts as $chart)
                            <tr class="account-row">
                                <td>{{ $chart->accountcode }}</td>
                                <td>{{ $chart->description }}</td>
                                <td>{{ $chart->accountusage }}</td>
                                <td>{{ $chart->analyticalcode }}</td>
                                <td class="text-center">
                                    <span class="{{ $chart->active ? 'status-active' : 'status-inactive' }}">
                                        {{ $chart->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>