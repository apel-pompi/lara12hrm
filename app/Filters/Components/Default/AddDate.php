<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class AddDate implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['adddate'])) {

            $content['builder']->where('adddate', $content['params']['adddate']);
        }
        return $next($content);
    }
}
