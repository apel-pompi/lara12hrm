<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class StudentPhone implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['phone'])) {
            $phone = $content['params']['phone'];
            $content['builder']->whereHas('student', function ($q) use ($phone) {
                $q->where('phone', 'like', "%{$phone}%");
            });
        }

        return $next($content);
    }
}
