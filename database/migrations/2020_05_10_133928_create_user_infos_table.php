<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('mobile', 50);
            //$table->string('quartier');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('deg2rad_longitude');
            $table->string('deg2rad_latitude');
            $table->string('quartier_livraison', 100);
            $table->string('ville_livraison');
            $table->string('daira_livraison');
            $table->string('wilaya_livraison');
            $table->string('pays_livraison');
            $table->string('quartier_residence');
            $table->string('ville_residence');
            $table->string('daira_residence');
            $table->string('wilaya_residence');
            $table->string('pays_residence');
            $table->foreignId('user_id');
            $table->foreignId('profil_id');
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
        Schema::dropIfExists('user_infos');
    }
}
