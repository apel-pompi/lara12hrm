<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class DateTime implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['datetime'])) {

            $dateParam = $content['params']['datetime'];
            
            $converted = date('Y-m-d', strtotime($dateParam));
            $content['builder']->whereDate('datetime', $converted);
        }
        return $next($content);
    }
}
