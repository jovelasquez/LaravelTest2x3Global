<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
    	'email' => $faker->unique()->safeEmail,
        'join_date' => now(),
    ];
});
