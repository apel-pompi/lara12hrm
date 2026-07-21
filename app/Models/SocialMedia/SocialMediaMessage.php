<?php

namespace App\Models\SocialMedia;

use App\Models\SocialMedia\SocialMediaConversation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaMessage extends Model
{
    use HasFactory;

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Relations
        |--------------------------------------------------------------------------
        */

        'conversation_id',
        'contact_id',

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        'platform',

        /*
        |--------------------------------------------------------------------------
        | Meta
        |--------------------------------------------------------------------------
        */

        'meta_message_id',

        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */

        'direction',
        'sender_type',
        'message_type',
        'message',

        /*
        |--------------------------------------------------------------------------
        | Attachment
        |--------------------------------------------------------------------------
        */

        'attachment',
        'attachment_type',
        'attachment_size',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status',

        /*
        |--------------------------------------------------------------------------
        | Payload
        |--------------------------------------------------------------------------
        */

        'payload',

        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        'sent_at',
        'delivered_at',
        'read_at',

    ];

    protected $casts = [

        'payload' => 'array',

        'sent_at' => 'datetime',

        'delivered_at' => 'datetime',

        'read_at' => 'datetime',

        'attachment_size' => 'integer',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function conversation()
    {
        return $this->belongsTo(
            SocialMediaConversation::class,
            'conversation_id'
        );
    }

    public function contact()
    {
        return $this->belongsTo(
            SocialMediaContact::class,
            'contact_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeInbound($query)
    {
        return $query->where(
            'direction',
            'inbound'
        );
    }

    public function scopeOutbound($query)
    {
        return $query->where(
            'direction',
            'outbound'
        );
    }

    public function scopeUnread($query)
    {
        return $query->where(
            'status',
            '!=',
            'read'
        );
    }

    public function scopeWhatsapp($query)
    {
        return $query->where(
            'platform',
            'whatsapp'
        );
    }

    public function scopeMessenger($query)
    {
        return $query->where(
            'platform',
            'messenger'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isInbound(): bool
    {
        return $this->direction === 'inbound';
    }

    public function isOutbound(): bool
    {
        return $this->direction === 'outbound';
    }

    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function hasAttachment(): bool
    {
        return !empty($this->attachment);
    }
}
