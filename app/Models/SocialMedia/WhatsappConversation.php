<?php

namespace App\Models\SocialMedia;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappConversation extends Model
{

    use HasFactory;

    protected $fillable = [
        'student_id',
        'social_media_setup_id',
        'phone',
        'name',
        'last_message_at',
        'is_read'
    ];

    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function setup()
    {
        return $this->belongsTo(SocialMediaSetup::class);
    }
}
