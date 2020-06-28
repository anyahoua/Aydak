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
            $table->bigIncrements('id');
            $table->string('mobile', 10);
            $table->string('adresse_residence')->comment('Billing address');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('profil_id')->references('id')->on('profils');
            $table->string('avatar')->nullable();
            $table->integer('etat');
            $table->integer('etape');
            $table->timestamps();
            
            /*
            //$table->string('latitude');
            //$table->string('longitude');
            //$table->string('deg2rad_longitude')->nullable();
            //$table->string('deg2rad_latitude')->nullable();
            //$table->string('quartier_livraison', 100)->nullable();
            //$table->string('ville_livraison')->nullable();
            //$table->string('daira_livraison')->nullable();
            //$table->string('wilaya_livraison')->nullable();
            //$table->string('pays_livraison')->nullable();
            $table->string('quartier_residence');
            $table->string('ville_residence');
            $table->string('daira_residence');
            $table->string('wilaya_residence');
            $table->string('pays_residence');
            */

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
