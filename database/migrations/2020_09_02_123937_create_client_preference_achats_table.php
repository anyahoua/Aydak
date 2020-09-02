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
            $table->foreignId('clientid')->references('id')->on('clients');
            $table->foreignId('produitid')->references('id')->on('produits');
            $table->unique(["clientid", "produitid"], 'client_produit_unique');
            $table->timestamps();
            $table->softDeletes();
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
