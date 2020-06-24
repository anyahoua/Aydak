<?php

use App\Models\Groupe;
use Illuminate\Database\Seeder;

class GroupesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Groupe::class, 3)->create();
    }
}
