<?php

declare(strict_types=1);

namespace App\Filters\Components\PersonalInfo;

use App\Filters\Components\ComponentInterface;

use Closure;

class EmployeeID implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['empid'])) {
            $content['builder']->where('empid', $content['params']['empid']);
        }

        return $next($content);
    }
}
