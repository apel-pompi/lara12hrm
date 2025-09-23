<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class IDName implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['name'])) {

            $content['builder']->where('id', $content['params']['name']);
        }
        return $next($content);
    }
}
