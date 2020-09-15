<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('vote');
            $table->foreignId('client_id')->references('id')->on('clients');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('groupe_id')->references('id')->on('groupes');
            $table->unique(["client_id", "user_id"], 'client_user_unique');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_votes');
    }
}
