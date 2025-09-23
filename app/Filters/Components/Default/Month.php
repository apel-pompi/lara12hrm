<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class Month implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['monthname'])) {

            $content['builder']->where('monthname', $content['params']['monthname']);
        }
        return $next($content);
    }
}
