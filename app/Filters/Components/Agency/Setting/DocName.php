<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency\Setting;

use App\Filters\Components\ComponentInterface;
use Closure;

class DocName implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['docname'])) {

            $content['builder']->where('docname', $content['params']['docname']);
        }
        return $next($content);
    }
}
