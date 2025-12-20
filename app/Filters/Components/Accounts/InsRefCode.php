<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class InsRefCode implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['refe_code'])) {

            $content['builder']->where('refe_code', $content['params']['refe_code']);
        }
        return $next($content);
    }
}
