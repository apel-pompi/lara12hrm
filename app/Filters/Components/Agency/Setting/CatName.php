<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency\Setting;

use App\Filters\Components\ComponentInterface;
use Closure;

class CatName implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['catname'])) {

            $content['builder']->where('catname', $content['params']['catname']);
        }
        return $next($content);
    }
}
