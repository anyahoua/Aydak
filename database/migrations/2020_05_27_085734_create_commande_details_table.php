<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commande_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quantite_commande');
            $table->bigInteger('quantite_achat');
            $table->double('prix_u_commande');
            $table->double('prix_u_achat');
            $table->integer('etat');
            $table->foreignId('commande_id');
            $table->foreignId('produit_id');
            //$table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commande_details');
    }
}
