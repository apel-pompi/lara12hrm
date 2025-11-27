<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class Source implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['source_id'])) {

            $content['builder']->where('source_id', $content['params']['source_id']);
        }
        return $next($content);
    }
}
