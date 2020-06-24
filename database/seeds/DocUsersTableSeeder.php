<?php

use Illuminate\Database\Seeder;

class DocUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\DocUser::class, 24)->create();
    }
}
