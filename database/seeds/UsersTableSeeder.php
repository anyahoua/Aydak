<?php
/*
use App\User;
use App\Models\Profil;
use App\Models\Groupe;
use App\Models\GroupeUser;
use App\Models\UserInfo;
use App\Models\DocUser;
*/
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
        
        factory(App\Models\Profil::class, 3)->create();
        factory(App\Models\Groupe::class, 3)->create();

        factory(App\User::class, 12)->create()
        ->each(function ($user) {

            $userinfo   = factory(App\Models\UserInfo::class)->make();
            $user->userInformation()->save($userinfo);
            
            $groupeuser = factory(App\Models\GroupeUser::class)->make();
            $user->groupeUser()->save($groupeuser);

            //$docuser    = factory(App\Models\DocUser::class, 2)->make();
            //$user->documents()->saveMany($docuser);
            
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