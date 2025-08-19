<?php

declare(strict_types=1);

namespace App\Filters\Components\PersonalInfo;

use App\Filters\Components\ComponentInterface;

use Closure;

class Blood implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['blood'])) {
            $content['builder']->where('blood', $content['params']['blood']);
        }

        return $next($content);
    }
}
