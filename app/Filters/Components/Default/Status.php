<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class Status implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (array_key_exists('status', $content['params'])) {

            $status = $content['params']['status'];

            if (is_null($status)) {
                // Pending
                $content['builder']->whereNull('status');
            } else {
                // Lead / Prospect / OnBoard / Archive
                $content['builder']->where('status', $status);
            }
        }

        return $next($content);
    }
}
