<?php

namespace App\Models\SocialMedia;

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaActivity extends Model
{
    use HasFactory;

    protected $casts = [

        'meta' => 'array',

    ];

    protected $fillable = [
        'conversation_id',
        'student_id',
        'user_id',
        'activity',
        'title',
        'description',
        'meta'
    ];

    public function conversation()
    {
        return $this->belongsTo(SocialMediaConversation::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
