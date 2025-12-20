<?php

declare(strict_types=1);

namespace App\Filters\Components\Accounts;

use App\Filters\Components\ComponentInterface;
use Closure;

class StudentID implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (!empty($content['params']['student_id'])) {
            $student_id = $content['params']['student_id'];
            $content['builder']->whereHas('student', function ($q) use ($student_id) {
                $q->where('student_id', 'like', "%{$student_id}%");
            });
        }

        return $next($content);
    }
}
