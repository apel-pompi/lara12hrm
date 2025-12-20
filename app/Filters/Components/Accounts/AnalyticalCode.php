<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class AnalyticalCode implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['analyticalcode'])) {

            $content['builder']->where('analyticalcode', $content['params']['analyticalcode']);
        }
        return $next($content);
    }
}
