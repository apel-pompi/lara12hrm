<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class SupplierID implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['suppliercode'])) {

             $content['builder']->where('suppliercode', $content['params']['suppliercode']);
        }
        return $next($content);
    }
}
