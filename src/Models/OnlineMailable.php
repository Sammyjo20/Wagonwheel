<?php

namespace Sammyjo20\Jockey\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineMailable extends Model
{
    /**
     * @var string[]
     */
    public $timestamps = [
        'created_at',
        'updated_at',
        'expires_at'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
