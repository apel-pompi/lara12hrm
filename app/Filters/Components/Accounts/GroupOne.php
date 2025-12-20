<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class GroupOne implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['groupone'])) {

            $content['builder']->where('groupone', $content['params']['groupone']);
        }
        return $next($content);
    }
}
