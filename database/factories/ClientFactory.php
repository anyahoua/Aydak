<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use App\Models\ClientInfo;
use App\Models\ClientCompte;

use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {

    $groupes = App\Models\Groupe::pluck('id')->toArray();

    return [
        'nom'               => $faker->lastName,
        'prenom'            => $faker->firstName,
        'username'          => $faker->unique()->regexify('^(05|06|07)[0-9]{8}$'),
        'password'          => bcrypt('12345678'),
        'groupe_id'         => $faker->randomElement($groupes),
    ];
});


// Client Infos
$factory->define(ClientInfo::class, function (Faker $faker) {
    
    //$faker->addProvider(new Faker\Provider\fr_FR\Address($faker));

    return [
            'mobile'                => $faker->unique()->regexify('^(05|06|07)[0-9]{8}$'),
            'quartier'              => $faker->streetName,
            'latitude'              => $faker->latitude($min = -90, $max = 90),
            'longitude'             => $faker->longitude($min = -180, $max = 180),
            'etat'                  => '1',
            'client_id'             => $faker->numberBetween(1, 3),
            'ville'                 => $faker->city,
            'daira'                 => $faker->city,
            'wilaya'                => $faker->state,
            'pays'                  => 'Algérie',
    ];
});

// Client Compte
$factory->define(ClientCompte::class, function (Faker $faker) {

    $groupes = App\Models\Groupe::pluck('id')->toArray();
    $clients = App\Client::pluck('id')->toArray();

    return [
        'debit'             => '0',
        'credit'            => '5000',
        'ancien_solde'      => '0',
        'nouveau_solde'     => '0',
        'etat'              => '1',
        'client_id'         => $faker->randomElement($clients),
        'groupe_id'         => $faker->randomElement($groupes),
    ];
});


