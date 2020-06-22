<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPreferenceAchatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_preference_achats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quantite_produit');
            $table->integer('etat');
            $table->foreignId('client_id')->references('id')->on('clients');
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
        Schema::dropIfExists('client_preference_achats');
    }
}
