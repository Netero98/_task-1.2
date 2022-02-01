<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomStart = (1643580001 + rand(0,9)*3600);
        $startStamp = date('Y-m-d H:i:s', $randomStart);
        $endStamp = date('Y-m-d H:i:s', $randomStart + 3000);
        return [
            'name' => $this->faker->sentence(),
            'startstamp' => $startStamp,
            'endstamp' => $endStamp
        ];
    }
}
