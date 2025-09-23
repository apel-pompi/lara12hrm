<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class TrnCode implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['trncode'])) {

            $content['builder']->where('trncode', $content['params']['trncode']);
        }
        return $next($content);
    }
}
