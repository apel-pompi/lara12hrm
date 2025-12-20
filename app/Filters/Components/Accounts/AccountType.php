<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class AccountType implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['accounttype'])) {

            $content['builder']->where('accounttype', $content['params']['accounttype']);
        }
        return $next($content);
    }
}
