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

    public function expired()
    {
        return $this->state([
            'expires_at' => Carbon::parse('1 year ago'),
        ]);
    }

    public function expiresIn(string $expirePeriod)
    {
        return $this->state([
            'expires_at' => now()->add($expirePeriod),
        ]);
    }
}
