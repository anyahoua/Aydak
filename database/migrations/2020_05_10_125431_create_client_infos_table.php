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
            $table->id();
            $table->string('mobile', 50);
            $table->string('quartier');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('deg2rad_longitude');
            $table->string('deg2rad_latitude');
            $table->foreignId('ville_id');
            $table->foreignId('daira_id');
            $table->foreignId('wilaya_id');
            $table->foreignId('pays_id');
            $table->foreignId('client_id');
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
        Schema::dropIfExists('client_infos');
    }
}
