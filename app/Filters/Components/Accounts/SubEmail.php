<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class SubEmail implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['subemail'])) {

             $content['builder']->where('subemail', $content['params']['subemail']);
        }
        return $next($content);
    }
}
