<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payment;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'uuid' => Str::uuid(),
        'payment_date' => $faker->randomElement([null, now()]),
        'expires_at' => $faker->dateTime($max = 'now', $timezone = 'UTC'),
        'status' => $faker->randomElement(['paid', 'pending', 'dued','cancelled']),
        'user_id' => rand(1, 5),
        'clp_usd' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2000),
    ];
});
