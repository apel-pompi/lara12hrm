<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class User implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['user_id'])) {

            $content['builder']->where('user_id', $content['params']['user_id']);
        }
        return $next($content);
    }
}
