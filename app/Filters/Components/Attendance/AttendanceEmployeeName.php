<?php

declare(strict_types=1);

namespace App\Filters\Components\Attendance;

use App\Filters\Components\ComponentInterface;
use Closure;

class AttendanceEmployeeName implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['empname'])) {
            $content['builder']->whereHas('employee', function ($q) use ($content) {
                $q->where('empname', 'like', '%' . $content['params']['empname'] . '%');
            });
        }

        return $next($content);
    }
}
