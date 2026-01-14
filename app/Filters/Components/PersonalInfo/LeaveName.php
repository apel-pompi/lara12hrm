<?php

declare(strict_types=1);

namespace App\Filters\Components\PersonalInfo;

use App\Filters\Components\ComponentInterface;

use Closure;

class LeaveName implements ComponentInterface
{

    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['leavename'])) {
            $leavename = $content['params']['leavename'];
            $content['builder']->whereHas('leavePlan', function ($q) use ($leavename) {
                $q->where('leavename', 'like', "%{$leavename}%");
            });
        }

        return $next($content);
    }


}
