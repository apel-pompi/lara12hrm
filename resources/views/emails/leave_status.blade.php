<x-mail::message>
# Leave Request Update

Hello {{ $leaveData['empname'] }},

Your leave request for the period **{{ $leaveData['fromdate'] }}** to **{{ $leaveData['todate'] }}** has been updated.

**Status:** {{ $leaveData['status'] }}  
**Reason:** {{ $leaveData['reason'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>