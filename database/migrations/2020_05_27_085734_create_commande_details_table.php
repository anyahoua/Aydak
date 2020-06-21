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
            $table->bigIncrements('id');
            $table->bigInteger('quantite_commande');
            $table->bigInteger('quantite_achat')->nullable();
            $table->double('prix_u_commande');
            $table->double('prix_u_achat')->nullable();
            $table->integer('etat');
            $table->foreignId('commande_id')->references('id')->on('commandes');
            $table->foreignId('produit_id')->references('id')->on('produits');
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
