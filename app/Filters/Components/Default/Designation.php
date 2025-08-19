<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;

use Closure;

class Designation implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['des_id'])) {
            $content['builder']->where('des_id', $content['params']['des_id']);
        }

        return $next($content);
    }
}
