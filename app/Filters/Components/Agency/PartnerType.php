<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class PartnerType implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['partnerType'])) {

            $content['builder']->where('partner_type_id', $content['params']['partnerType']);
        }
        return $next($content);
    }
}
