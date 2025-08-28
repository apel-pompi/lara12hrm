<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Application</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Leave Application #{{ $leave->id }}</h2>

   
    <table>
        <tr>
            <th>Status</th>
            <td>{{ $leave }}</td>
        </tr>
        <tr>
            <th>Applied on</th>
            <td>{{ $leave }}</td>
        </tr>
    </table>
</body>
</html>