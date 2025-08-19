<?php

declare(strict_types=1);

namespace App\Filters\Components\PersonalInfo;

use App\Filters\Components\ComponentInterface;

use Closure;

class EmployeeeName implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['empname'])) {
            $content['builder']->where('empname', $content['params']['empname']);
        }

        return $next($content);
    }
}
