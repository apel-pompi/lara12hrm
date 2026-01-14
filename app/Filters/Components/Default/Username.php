<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class Username implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['username'])) {

            $content['builder']->where('username', $content['params']['username']);
        }
        return $next($content);
    }
}
