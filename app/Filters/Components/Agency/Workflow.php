<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class Workflow implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['workflow'])) {

            $content['builder']->where('workflow_id', $content['params']['workflow']);
        }
        return $next($content);
    }
}
