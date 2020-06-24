<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Profil;
use App\Models\UserInfo;
use App\Models\DocUser;

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

// Users
$factory->define(User::class, function (Faker $faker) {
    return [
            'nom'               => $faker->lastName,
            'prenom'            => $faker->firstName,
            'email'             => NULL,
            'email_verified_at' => NULL,
            //'username'          => $faker->unique()->phoneNumber,//$faker->randomNumber($nbDigits = NULL, $strict = false),
            'username'          => $faker->unique()->regexify('^(05|06|07)[0-9]{8}$'),
            'password'          => bcrypt('12345678'), // password
            'remember_token'    => NULL,
    ];
});

// Profil
$factory->define(Profil::class, function (Faker $faker) {
    return [
            'nom'               => $faker->randomElement($array = array ('Teamleader','Coursier','Aydak')),
            'etat'              => '1',
    ];
});

// Users Infos
$factory->define(UserInfo::class, function (Faker $faker) {
    
    $faker->addProvider(new Faker\Provider\fr_FR\Address($faker));

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
            'user_id'               => $faker->numberBetween(1, 12),
            'profil_id'             => $faker->numberBetween(1, 2),
            'etat'                  => '1',
            'etape'                 => '2',
    ];
});

/*
// Documents users
$factory->define(DocUser::class, function (Faker $faker) {
    return [
            'doc'                   => $faker->file('C:\Users\salim3\Pictures\Screenpresso', 'D:\laragon\www\aydak\storage\app\public\users', false),
            'etat'                  => '1',
            'user_id'               => $faker->numberBetween(1, 12),
    ];
});
*/