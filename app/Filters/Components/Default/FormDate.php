<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class FormDate implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['fromdate'])) {

            $content['builder']->where('fromdate', $content['params']['fromdate']);
        }
        return $next($content);
    }
}
