<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class AdvancePhone implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['advancePhone']) && $content['params']['advancePhone'] !== '') {
            $phone = trim((string) $content['params']['advancePhone']);
            $content['builder']->where('phone', 'like', "%{$phone}%");
        }
        return $next($content);
    }
}
