<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class AdvanceStudentName implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['advanceStudentName']) && $content['params']['advanceStudentName'] !== '') {
            $name = trim((string) $content['params']['advanceStudentName']);
            $content['builder']->where(function ($query) use ($name) {
                $query->where('fname', 'like', "%{$name}%")
                    ->orWhere('lname', 'like', "%{$name}%")
                    ->orWhere('student_id', 'like', "%{$name}%");
            });
        }
        return $next($content);
    }
}
