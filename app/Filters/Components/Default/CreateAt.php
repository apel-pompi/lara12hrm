<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class CreateAt implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['created_at'])) {

            $dateParam = $content['params']['created_at'];
            if (is_array($dateParam) && isset($dateParam['text'])) {
                $dateText = $dateParam['text'];
            } else {
                $dateText = $dateParam;
            }
            
            $converted = date('Y-m-d', strtotime($dateText));
            $content['builder']->whereDate('created_at', $converted);
        }
        return $next($content);
    }
}
