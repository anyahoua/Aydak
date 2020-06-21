<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mobile', 50);
            $table->string('quartier');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('deg2rad_longitude')->nullable();
            $table->string('deg2rad_latitude')->nullable();
            $table->integer('etat');
            $table->foreignId('client_id')->references('id')->on('clients');
            $table->string('ville')->nullable();
            $table->string('daira')->nullable();
            $table->string('wilaya')->nullable();
            $table->string('pays')->nullable();
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
        Schema::dropIfExists('client_infos');
    }
}
