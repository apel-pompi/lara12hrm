<?php

namespace App\Models\SocialMedia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class SocialMediaSetup extends Model
{
    use HasFactory;

    public const FACEBOOK = 'facebook';

    public const MESSENGER = 'messenger';

    public const INSTAGRAM = 'instagram';

    public const WHATSAPP = 'whatsapp';

    protected $fillable = [

        'platform',

        'page_id',

        'phone_number_id',

        'whatsapp_business_account_id',

        'access_token',

        'verify_token',

        'status',

        'meta',

    ];

    protected $casts = [

        'status' => 'boolean',

        'meta' => 'array',

    ];

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            true
        );
    }

    public static function platform(string $platform): self
    {
        // Log::info('Looking for platform', [
        //     'platform' => $platform
        // ]);

        $setup = static::active()
            ->where('platform', $platform)
            ->first();

        // Log::info('Setup Found', [
        //     'setup' => $setup?->toArray()
        // ]);

        if (!$setup) {
            throw new \Exception("Setup not found for {$platform}");
        }

        return $setup;
    }
}
