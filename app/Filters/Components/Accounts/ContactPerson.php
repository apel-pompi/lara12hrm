<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class ContactPerson implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['contact_person'])) {

             $content['builder']->where('contact_person', $content['params']['contact_person']);
        }
        return $next($content);
    }
}
