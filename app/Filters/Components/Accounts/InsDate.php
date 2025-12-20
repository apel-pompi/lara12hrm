<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class InsDate implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['insdate'])) {

            $content['builder']->where('insdate', $content['params']['insdate']);
        }
        return $next($content);
    }
}
