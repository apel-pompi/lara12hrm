<?php

declare(strict_types=1);

namespace App\Filters\Components\Attendance;

use App\Filters\Components\ComponentInterface;
use Closure;

class AttendanceEmployeeID implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['empid'])) {
            $content['builder']->whereHas('employee', function ($q) use ($content) {
                $q->where('empid', $content['params']['empid']);
            });
        }

        return $next($content);
    }
}
