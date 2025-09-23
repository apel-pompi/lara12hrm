<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class TrnType implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['trnname_id'])) {

            $content['builder']->where('trnname_id', $content['params']['trnname_id']);
        }
        return $next($content);
    }
}
