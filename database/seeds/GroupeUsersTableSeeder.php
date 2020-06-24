<?php

use App\Models\Groupe;
use App\Models\GroupeUser;
use Illuminate\Database\Seeder;

class GroupeUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(GroupeUser::class, 3)->create();
    }
}
