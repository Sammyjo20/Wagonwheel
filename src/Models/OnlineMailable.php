<?php

namespace Sammyjo20\Wagonwheel\Models;

use Database\Factories\OnlineMailableFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineMailable extends Model
{
    use HasFactory;

    protected $casts = [
        'expires_at' => 'timestamp',
    ];

    protected static function newFactory()
    {
        return new OnlineMailableFactory;
}
}
