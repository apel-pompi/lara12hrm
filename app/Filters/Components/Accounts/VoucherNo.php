<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class VoucherNo implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['vouchernumber'])) {

             $content['builder']->where('vouchernumber', $content['params']['vouchernumber']);
        }
        return $next($content);
    }
}
