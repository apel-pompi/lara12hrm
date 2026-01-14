<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class ToDate implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['todate'])) {

            $content['builder']->where('todate', $content['params']['todate']);
        }
        return $next($content);
    }
}
