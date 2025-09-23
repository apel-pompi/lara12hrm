<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class Country implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['country'])) {

            $content['builder']->where('descountry_id', $content['params']['country']);
        }
        return $next($content);
    }
}
