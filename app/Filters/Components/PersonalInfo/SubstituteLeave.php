<?php

declare(strict_types=1);

namespace App\Filters\Components\PersonalInfo;

use App\Filters\Components\ComponentInterface;

use Closure;

class SubstituteLeave implements ComponentInterface
{

    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['subemp'])) {
            $empname = $content['params']['subemp'];
            $content['builder']->whereHas('substituteEmployee', function ($q) use ($empname) {
                $q->where('empname', 'like', "%{$empname}%");
            });
        }

        return $next($content);
    }


}
