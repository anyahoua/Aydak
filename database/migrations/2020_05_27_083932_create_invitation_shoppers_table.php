<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationShoppersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_shoppers', function (Blueprint $table) {
            $table->id();
            $table->string('mobile', 30);
            $table->string('code');
            $table->dateTime('date_envoie');
            $table->dateTime('date_activation');
            $table->integer('etat');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('invitation_shoppers');
    }
}
