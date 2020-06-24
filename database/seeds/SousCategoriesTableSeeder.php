<?php

use Illuminate\Database\Seeder;

class SousCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sous_categories')->insert([
            [
                'libely'        => 'PETIT ELECTROMENAGER',
                'categorie_id'  => '4',
                'etat'          => '1',
                'icon'          => 'snow-outline'
            ],
            [
                'libely'        => 'PHOTO COMMUNICATION',
                'categorie_id'  => '4',
                'etat'          => '1',
                'icon'          => 'snow-outline'
            ],
            [
                'libely'        => 'IMAGE & SON',
                'categorie_id'  => '4',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'INFORMATIQUE',
                'categorie_id'  => '4',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'RADIOTELEPHONIE',
                'categorie_id'  => '4',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'CHAUSSURE',
                'categorie_id'  => '5',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'BEBE',
                'categorie_id'  => '5',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'ENFANT',
                'categorie_id'  => '5',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'FEMME',
                'categorie_id'  => '5',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'HOMME',
                'categorie_id'  => '5',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'LINGE DE MAISON',
                'categorie_id'  => '5',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'ACCESSOIRE',
                'categorie_id'  => '5',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'BIJOUTERIE',
                'categorie_id'  => '5',
                'etat'          => '0',
                'icon'          => NULL
            ],
            [
                'libely'        => 'BOISSONS',
                'categorie_id'  => '1',
                'etat'          => '1',
                'icon'          => 'snow-outline'
            ],
            [
                'libely'        => 'DROGUERIE',
                'categorie_id'  => '1',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'PARFUMERIE HYGIENE',
                'categorie_id'  => '1',
                'etat'          => '0',
                'icon'          => NULL
            ],
            [
                'libely'        => 'EPICERIE',
                'categorie_id'  => '1',
                'etat'          => '1',
                'icon'          => 'snow-outline'
            ],
            [
                'libely'        => 'PRODUIT LAITIER SURGELE',
                'categorie_id'  => '1',
                'etat'          => '1',
                'icon'          => 'snow-outline'
            ],
            [
                'libely'        => 'CHARCUTERIE/TRAITEUR',
                'categorie_id'  => '2',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'POISSONNERIE',
                'categorie_id'  => '2',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'FRUITS ET LEGUMES',
                'categorie_id'  => '2',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'BOULANGERIE / PATISSERIE',
                'categorie_id'  => '2',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'BOUCHERIE / VOLAILLE',
                'categorie_id'  => '2',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'BRICOLAGE',
                'categorie_id'  => '3',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'CONFORT DE LA MAISON',
                'categorie_id'  => '3',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'CULTURE-PAPETRIE',
                'categorie_id'  => '3',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'LOISIR - JOUETS',
                'categorie_id'  => '3',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'JARDIN ET ANIMALERIE',
                'categorie_id'  => '3',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'AUTO',
                'categorie_id'  => '3',
                'etat'          => '1',
                'icon'          => NULL
            ],
            [
                'libely'        => 'CENTRE AUTO',
                'categorie_id'  => '3',
                'etat'          => '0',
                'icon'          => NULL
            ],
            [
                'libely'        => 'GRAND ELECTROMENAGER',
                'categorie_id'  => '4',
                'etat'          => '1',
                'icon'          => NULL
            ],
        ]);
    }
}