<?php

namespace Sammyjo20\Jockey\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineMailable extends Model
{
    /**
     * @var array
     */
    public $guarded = [];

    /**
     * @var string[]
     */
    public $timestamps = [
        'created_at',
        'updated_at',
        'expires_at'
    ];

    /**
     * OnlineMailable constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable('online_mailables')
            ->setConnection(
                config('database.default', null)
            );

        parent::__construct($attributes);
    }
}
