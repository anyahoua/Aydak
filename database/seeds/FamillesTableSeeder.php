<?php

use Illuminate\Database\Seeder;

class FamillesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('familles')->insert([
            [
                'sous_categorie_id'     => '13',
                'libely'                => 'ALIMENTAIRE ASIATIQUE', 
                'etat'                  => '1', 
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '13',
                'libely'                => 'AUTRES ASIATIQUE', 
                'etat'                  => '1', 
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '13',
                'libely'                => 'GESTION R13', 
                'etat'                  => '0', 
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '63',
                'libely'                => 'GESTION R63', 
                'etat'                  => '0', 
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '12',
                'libely'                => 'PARFUMERIE', 
                'etat'                  => '1', 
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '44',
                'libely'                => 'PERIPHERIQUE INFORMATIQUE', 
                'etat'                  => 1, 
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '44',
                'libely'                => 'SUPPORT ENREGISTREMENT',
                'etat'                  => '1',
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '44',
                'libely'                => 'CONSOMMABLE IMPRESSION',
                'etat'                  => '1',
                'icon'                  => NULL
            ],
            [
                'sous_categorie_id'     => '44',
                'libely'                => 'LOGICIEL',
                'etat'                  => '1',
                'icon'                  => NULL
            ]


            
        ]);





    }
}