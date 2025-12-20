<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class Description implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['description'])) {

            $content['builder']->where('description', $content['params']['description']);
        }
        return $next($content);
    }
}
