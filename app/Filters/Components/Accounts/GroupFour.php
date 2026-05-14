<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class GroupFour implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['groupfour'])) {

            $content['builder']->where('groupfour', $content['params']['groupfour']);
        }
        return $next($content);
    }
}
