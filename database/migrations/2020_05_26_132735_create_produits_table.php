<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libely', 100);
            $table->string('commentaire', 100);
            $table->text('unite_val');
            $table->foreignId('famille_id')->references('id')->on('familles');
            $table->foreignId('unite_mesure_id')->references('id')->on('unite_mesures');
            $table->integer('etat');
            $table->string('photo', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produits');
    }
}
