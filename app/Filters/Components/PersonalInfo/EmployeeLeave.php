<?php

declare(strict_types=1);

namespace App\Filters\Components\PersonalInfo;

use App\Filters\Components\ComponentInterface;

use Closure;

class EmployeeLeave implements ComponentInterface
{

    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['empname'])) {
            $empname = $content['params']['empname'];
            $content['builder']->whereHas('employee', function ($q) use ($empname) {
                $q->where('empname', 'like', "%{$empname}%");
            });
        }

        return $next($content);
    }


}
