<?php

namespace Sammyjo20\Wagonwheel\Models;

use Carbon\Carbon;
use Database\Factories\OnlineMailableFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineMailable extends Model
{
    use HasFactory;

    protected const STORE_INDEFINITELY = 0;

    protected $casts = [
        'expires_at' => 'timestamp',
    ];

    protected static function newFactory()
    {
        return OnlineMailableFactory::new();
    }

    public function getSignedUrl()
    {
        if ($this->expires_at !== null) {
            return URL::temporarySignedRoute('mail.view-online', Carbon::parse($this->expires_at), [
                'onlineMailable' => $this
            ]);
        }

        return URL::signedRoute('mail.view-online', [
            'onlineMailable' => $this
        ]);
    }

    public static function getExpirationDate(): ?Carbon
    {
        if (self::storeIndefinitely()) {
            return null;
        }

        return now()->addDays(config('wagonwheel.message_expires_in_days'));
    }

    private static function storeIndefinitely()
    {
        return config('wagonwheel.message_expires_in_days') === self::STORE_INDEFINITELY;
    }
}
