<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;

use Closure;

class Department implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['dept_id'])) {
            $content['builder']->where('dept_id', $content['params']['dept_id']);
        }

        return $next($content);
    }
}
