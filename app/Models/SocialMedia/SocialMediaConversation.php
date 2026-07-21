<?php

namespace App\Models\SocialMedia;

use App\Models\SocialMedia\SocialMediaContact;
use App\Models\SocialMedia\SocialMediaMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class SocialMediaConversation extends Model
{
    use HasFactory;

    protected $fillable = [

        'contact_id',

        'platform',

        'conversation_id',

        'last_message',

        'last_message_at',

        'unread_count',

        'status'

    ];

    protected $casts = [

        'last_message_at' => 'datetime'

    ];

    public function contact()
    {
        return $this->belongsTo(
            SocialMediaContact::class,
            'contact_id'
        );
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function lastMessage()
    {
        return $this->belongsTo(SocialMediaMessage::class, 'last_message_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(
            SocialMediaMessage::class,
            'conversation_id'
        );
    }
}
