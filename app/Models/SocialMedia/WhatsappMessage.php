<?php

namespace App\Models\SocialMedia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'meta_message_id',
        'direction',
        'status',
        'message_type',
        'message',
        'media_url',
        'media_mime',
        'media_size',
        'media_name',
        'reply_to',
        'read_at',
        'payload',
        'message_time'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'message_time' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(WhatsappConversation::class);
    }

    public function replyTo()
    {
        return $this->belongsTo(WhatsappMessage::class, 'reply_to');
    }
}
