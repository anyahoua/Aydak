<?php

use App\User;
use App\Models\Profil;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        factory(Profil::class, 3)->create();

        factory(User::class, 8)->create()
        ->each(function ($user) {

            $userinfo = factory(App\Models\UserInfo::class)->make();
            $user->userInformation()->save($userinfo);

        });
    }
}

/*
        // Create 10 records of customers
        factory(App\Customer::class, 10)->create()->each(function ($customer) {
            // Seed the relation with one address
            $address = factory(App\CustomerAddress::class)->make();
            $customer->address()->save($address);

            // Seed the relation with 5 purchases
            $purchases = factory(App\CustomerPurchase::class, 5)->make();
            $customer->purchases()->saveMany($purchases);
        });
*/