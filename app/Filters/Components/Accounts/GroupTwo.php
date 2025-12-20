<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class GroupTwo implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['grouptwo'])) {

            $content['builder']->where('grouptwo', $content['params']['grouptwo']);
        }
        return $next($content);
    }
}
