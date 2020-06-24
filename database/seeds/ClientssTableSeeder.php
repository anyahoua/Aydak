<?php

use Illuminate\Database\Seeder;

class ClientssTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\Client::class, 3)->create()
        ->each(function ($client) {

            $clientinfo   = factory(App\Models\ClientInfo::class)->make();
            $client->clientInfo()->save($clientinfo);
            
            $clientcompte = factory(App\Models\ClientCompte::class)->make();
            $client->clientCompte()->save($clientcompte);

            //$docuser    = factory(App\Models\DocUser::class, 2)->make();
            //$user->documents()->saveMany($docuser);
            
        });

    }
}
