<?php

use Illuminate\Database\Seeder;

class UniteMesuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unite_mesures')->insert([
            [
                'libely'        => 'Unite',
                'etat'          => '1',
            ],
            [
                'libely'        => 'Kg',
                'etat'          => '1',
            ],
            [
                'libely'        => 'Littre',
                'etat'          => '1',
            ]
        ]);
    }
}
