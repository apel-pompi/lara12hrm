<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class InsNumber implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['insnumber'])) {

            $content['builder']->where('insnumber', $content['params']['insnumber']);
        }
        return $next($content);
    }
}
