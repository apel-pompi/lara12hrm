<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;

use App\Filters\Components\ComponentInterface;
use Closure;

class Email implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['email'])) {

            $content['builder']->where('email', $content['params']['email']);
        }
        return $next($content);
    }
}
