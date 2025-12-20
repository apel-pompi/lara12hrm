<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class AccountUsage implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['accountusage'])) {

            $content['builder']->where('accountusage', $content['params']['accountusage']);
        }
        return $next($content);
    }
}
