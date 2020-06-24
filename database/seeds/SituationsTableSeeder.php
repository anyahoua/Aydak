<?php

use Illuminate\Database\Seeder;

class SituationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('situations')->insert([
            [
                'libely'        => 'Non traitée',
                'description'   => NULL,
            ],
            [
                'libely'        => 'Refusée',
                'description'   => NULL,
            ],
            [
                'libely'        => 'Acceptée non assignée',
                'description'   => NULL,
            ],
            [
                'libely'        => 'Assignée non achetée',
                'description'   => NULL,
            ],
            [
                'libely'        => 'Acheté non contrôlée',
                'description'   => NULL,
            ],
            [
                'libely'        => 'Contrôlé non livré',
                'description'   => NULL,
            ],
            [
                'libely'        => 'Livré',
                'description'   => NULL,
            ]
        ]);
    }
}
