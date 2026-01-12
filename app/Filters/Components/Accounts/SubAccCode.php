<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class SubAccCode implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['subacccode'])) {

            $content['builder']->whereHas('voucherdt', function ($q) use ($content) {
                $q->where('subacccode', $content['params']['subacccode']);
            });
        }

        return $next($content);
    }
}
