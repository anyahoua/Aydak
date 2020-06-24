<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Groupe;
use Faker\Generator as Faker;

$factory->define(Groupe::class, function (Faker $faker) {
    return [
        'nom'               => $faker->name,
        'photo'             => $faker->file('C:\Users\salim3\Pictures\Screenpresso', 'D:\laragon\www\aydak\storage\app\public\users', false),
        'daira'             => $faker->city,
        'latitude'          => $faker->latitude($min = -90, $max = 90),
        'longitude'         => $faker->longitude($min = -180, $max = 180),
        'etat'              => '1',
    ];
});
