<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class VoucherDate implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['voucherdate'])) {

             $content['builder']->where('voucherdate', $content['params']['voucherdate']);
        }
        return $next($content);
    }
}
