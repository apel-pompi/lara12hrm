<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class Referance implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['referance'])) {

             $content['builder']->where('referance', $content['params']['referance']);
        }
        return $next($content);
    }
}
