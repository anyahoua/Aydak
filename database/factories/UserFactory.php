<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Profil;
use App\Models\UserInfo;

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
            'nom'               => $faker->lastName,
            'prenom'            => $faker->firstName,
            'email'             => NULL,
            'email_verified_at' => NULL,
            'username'          => $faker->unique()->phoneNumber,//$faker->randomNumber($nbDigits = NULL, $strict = false),
            'password'          => bcrypt('12345678'), // password
            'remember_token'    => NULL,
    ];
});

$factory->define(Profil::class, function (Faker $faker) {
    return [
            'nom'               => $faker->name,
            'etat'              => '1',
    ];
});

$factory->define(UserInfo::class, function (Faker $faker) {
    return [
            'mobile'                => $faker->phoneNumber,//$faker->randomNumber($nbDigits = NULL, $strict = false),
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
