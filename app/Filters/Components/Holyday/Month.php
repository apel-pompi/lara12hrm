<?php

declare(strict_types=1);

namespace App\Filters\Components\Holyday;

use App\Filters\Components\ComponentInterface;
use Closure;

class Month implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['holimonth'])) {

            $content['builder']->where('holimonth', $content['params']['holimonth']);
        }
        return $next($content);
    }
}
