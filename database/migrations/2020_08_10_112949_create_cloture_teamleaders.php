<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClotureTeamleaders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cloture_teamleaders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('montant_achat');
            $table->integer('pourcentage');
            $table->decimal('commission');
            $table->string('nom_groupe');
            $table->foreignId('groupe_id')->references('id')->on('groupes');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('nom');
            $table->string('prenom');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cloture_teamleaders');
    }
}
