<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserComptesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_comptes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('debit');
            $table->double('credit');
            $table->double('ancien_solde');
            $table->double('nouveau_solde');
            $table->integer('etat');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('groupe_id');
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
        Schema::dropIfExists('user_comptes');
    }
}
