<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Groupe;
use App\Models\GroupeUser;
use Faker\Generator as Faker;

$factory->define(GroupeUser::class, function (Faker $faker) {
    return [
        'date_annulation'   => null,
        'etat'              => '1',
        'user_id'           => User::pluck('id')->random(),
        'groupe_id'         => Groupe::pluck('id')->random(),
    ];
});
