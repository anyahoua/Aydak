<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupe_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('date_annulation');
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
        Schema::dropIfExists('groupe_users');
    }
}
