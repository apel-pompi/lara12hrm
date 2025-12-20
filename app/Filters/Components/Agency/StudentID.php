<?php

declare(strict_types=1);

namespace App\Filters\Components\Agency;

use App\Filters\Components\ComponentInterface;
use Closure;

class StudentID implements ComponentInterface
{
    public function handle(array $content, Closure $next): mixed
    {
        if (isset($content['params']['student_id'])) {

            $content['builder']->where('student_id', $content['params']['student_id']);
        }
        return $next($content);
    }
}
