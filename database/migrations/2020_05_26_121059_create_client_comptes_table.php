<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientComptesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_comptes', function (Blueprint $table) {
            $table->id();
            $table->double('debit');
            $table->double('credit');
            $table->double('ancien_solde');
            $table->double('nouveau_solde');
            $table->integer('etat');
            $table->foreignId('client_id');
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
        Schema::dropIfExists('client_comptes');
    }
}
