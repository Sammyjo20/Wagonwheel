<?php

namespace Database\Factories;

use Carbon\Carbon;
use Sammyjo20\Wagonwheel\Models\OnlineMailable;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnlineMailableFactory extends Factory
{
    protected $model = OnlineMailable::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'expires_at' => OnlineMailable::getExpirationDate(),
            'content' => $this->faker->sentence(6),
        ];
    }

    public function expired()
    {
        return $this->expiresIn(now()->subYear());
    }

    public function expiresIn(Carbon $date)
    {
        return $this->state([
            'expires_at' => $date,
        ]);
    }
}
