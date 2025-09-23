<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class AssaignUser implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['user'])) {

            $content['builder']->where('assain_user', $content['params']['user']);
        }
        return $next($content);
    }
}
