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
