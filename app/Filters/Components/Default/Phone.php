<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class Phone implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['phone'])) {

            $content['builder']->where('phone', $content['params']['phone']);
        }
        return $next($content);
    }
}
