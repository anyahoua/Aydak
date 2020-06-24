<?php

use Illuminate\Database\Seeder;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'libely'    => 'Alimentaires',
                'etat'      => '1',
                'icon'      => 'snow-outline'
            ],
            [
                'libely'    => 'Netoyages',
                'etat'      => '1',
                'icon'      => 'train-outline'
            ],
            [
                'libely'    => 'BEBE',
                'etat'      => '1',
                'icon'      => 'umbrella-outline'
            ],
            [
                'libely'    => 'Bricolage',
                'etat'      => '1',
                'icon'      => 'thunderstorm-outline'
            ],
            [
                'libely'    => 'Frais',
                'etat'      => '1',
                'icon'      => 'watch-outline'
            ],
            [
                'libely'    => 'Textile',
                'etat'      => '1',
                'icon'      => 'server-outline'
            ]
        ]);
    }
}
