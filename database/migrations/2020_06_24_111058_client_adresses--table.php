<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClientAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_adresses', function (Blueprint $table) {
            $table->bigIncrements('id'); // Livraison
            $table->string('latitude');
            $table->string('longitude');
            $table->string('quartier');
            $table->string('commune')->nullable();
            $table->string('daira')->nullable();
            $table->string('wilaya')->nullable();
            $table->foreignId('pays_id');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->integer('etat');
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
        Schema::dropIfExists('client_adresses');
    }
}
