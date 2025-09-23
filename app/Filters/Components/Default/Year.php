<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class Year implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['yearname'])) {
            $content['builder']->where('yearname', $content['params']['yearname']);
        }

        return $next($content);
    }
}
