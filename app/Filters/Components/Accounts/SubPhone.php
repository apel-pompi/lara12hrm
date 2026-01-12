<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class SubPhone implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['subphone'])) {

             $content['builder']->where('subphone', $content['params']['subphone']);
        }
        return $next($content);
    }
}
