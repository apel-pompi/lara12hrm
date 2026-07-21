<?php

namespace App\Models\SocialMedia;

use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\SocialMedia\SocialMediaMessage;
use App\Models\Student\Student;
use App\Models\Student\StudentSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaContact extends Model
{
    use HasFactory;

    protected $fillable = [

        'student_id',

        'platform',

        'platform_user_id',

        'social_name',

        'phone_number',

        'phone_number_id',

        'page_id',

        'email',

        'profile_picture',

        'last_platform',

        'last_seen_at',

        'is_blocked',

        'is_archived',

        'meta',

    ];

    protected $casts = [

        'meta' => 'array',

        'last_seen_at' => 'datetime',

        'is_blocked' => 'boolean',

        'is_archived' => 'boolean',

    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function conversations()
    {
        return $this->hasMany(
            SocialMediaConversation::class,
            'contact_id'
        );
    }

    public function messages()
    {
        return $this->hasMany(
            SocialMediaMessage::class,
            'contact_id'
        );
    }

    public function assignedUser()
    {
        return $this->belongsTo(
            User::class,
            'assain_user'
        );
    }

    public function source()
    {
        return $this->belongsTo(
            StudentSource::class,
            'source_id'
        );
    }
}
