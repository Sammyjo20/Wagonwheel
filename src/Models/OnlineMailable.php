<?php

namespace Sammyjo20\Wagonwheel\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineMailable extends Model
{
    /**
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
