<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class CountryID implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['country'])) {

            if (!empty($content['params']['country'])) {
                $country = $content['params']['country'];
                $content['builder']->whereHas('state.country', function ($q) use ($country) {
                    $q->where('country_id', $country);
                });
            }
        }
        return $next($content);
    }
}
