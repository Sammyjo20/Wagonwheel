<?php

namespace Sammyjo20\Wagonwheel\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineMailable extends Model
{
    protected $casts = [
        'expires_at' => 'timestamp',
    ];
}
