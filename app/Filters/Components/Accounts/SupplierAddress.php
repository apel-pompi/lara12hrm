<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class SupplierAddress implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['subaddress'])) {

             $content['builder']->where('subaddress', $content['params']['subaddress']);
        }
        return $next($content);
    }
}
