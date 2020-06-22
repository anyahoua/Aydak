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
            $table->bigIncrements('id');
            $table->string('mobile', 30);
            $table->string('code');
            $table->timestamp("date_activation");
            $table->integer('etat');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('groupe_id')->references('id')->on('groupes');
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
