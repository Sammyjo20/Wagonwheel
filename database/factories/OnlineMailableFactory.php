<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;


class OnlineMailableFactory extends Factory
{
    protected $model = OnlineMailable::class;

    public function definition()
    {
        $expiration = config('wagonwheel.message_expires_in_days', 30);

        return [
            'uuid' => $this->faker->uuid,
            'expires_at' => Carbon::now()->addDays($expiration),
            'content' => $this->faker->sentence(6),
        ];
    }
}
