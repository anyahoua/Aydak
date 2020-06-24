<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\UserInfo;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(UserInfo::class, function (Faker $faker) {
    return [
            'mobile'                => $faker->unique()->regexify('^(05|06|07)[0-9]{8}$'),
            'latitude'              => $faker->latitude($min = -90, $max = 90),
            'longitude'             => $faker->longitude($min = -180, $max = 180),
            'quartier_residence'    => $faker->streetName,
            'ville_residence'       => $faker->city,
            'daira_residence'       => $faker->city,
            'wilaya_residence'      => $faker->state,
            'pays_residence'        => 'Algérie',
            'adresse_residence'     => $faker->address,
            'user_id'               => $faker->numberBetween(1, 8),
            'profil_id'             => $faker->numberBetween(1, 2),
            'etat'                  => '1',
            'etape'                 => '2',
    ];
});
