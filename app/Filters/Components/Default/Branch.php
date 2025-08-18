<?php

declare(strict_types=1);

namespace App\Filters\Components\Default;
use App\Filters\Components\ComponentInterface;
use Illuminate\Database\Eloquent\Builder;

use Closure;

class Branch implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['branch_id'])) {
            $content['builder']->whereHas('branch', function (Builder $query) use ($content) {
                $query->where('id', $content['params']['branch_id']);
            });
        }

        return $next($content);
    }
}