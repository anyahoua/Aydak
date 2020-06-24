<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\DocUser::class, function (Faker $faker) {
    $users = App\User::pluck('id')->toArray();
    return [
            'doc'                   => $faker->file('C:\Users\salim3\Pictures\Screenpresso', 'D:\laragon\www\aydak\storage\app\public\users', false),
            'etat'                  => '1',
            'user_id'               => $faker->randomElement($users),
    ];
});