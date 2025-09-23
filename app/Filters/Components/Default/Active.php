<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class Active implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['active'])) {

            $content['builder']->where('active', $content['params']['active']);
        }
        return $next($content);
    }
}
