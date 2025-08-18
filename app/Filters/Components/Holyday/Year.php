<?php

declare(strict_types=1);

namespace App\Filters\Components\Holyday;

use App\Filters\Components\ComponentInterface;
use Closure;

class Year implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['holiyear'])) {
            $content['builder']->where('holiyear', $content['params']['holiyear']);
        }

        return $next($content);
    }
}
