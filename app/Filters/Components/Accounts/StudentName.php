<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class StudentName implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['fname']) || !empty($content['params']['lname'])) {
            $fname = $content['params']['fname'] ?? '';
            $lname = $content['params']['lname'] ?? '';

            $content['builder']->whereHas('student', function ($q) use ($fname, $lname) {
                if ($fname !== '' && $lname !== '') {
                    // fname AND lname  filter
                    $q->where('fname', 'like', "%{$fname}%")
                      ->where('lname', 'like', "%{$lname}%");
                } elseif ($fname !== '') {
                    $q->where('fname', 'like', "%{$fname}%");
                } elseif ($lname !== '') {
                    $q->where('lname', 'like', "%{$lname}%");
                }
            });
        }

        return $next($content);
    }
}
