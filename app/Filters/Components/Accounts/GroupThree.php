<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class GroupThree implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['groupthree'])) {

            $content['builder']->where('groupthree', $content['params']['groupthree']);
        }
        return $next($content);
    }
}
