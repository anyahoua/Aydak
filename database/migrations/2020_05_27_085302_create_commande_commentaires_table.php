<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandeCommentairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commande_commentaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->text('commentaire');
            $table->integer('etat');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('groupe_id')->references('id')->on('groupes');
            $table->foreignId('commande_id')->references('id')->on('commandes');
            $table->foreignId('profil_id')->references('id')->on('profils');
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
        Schema::dropIfExists('commande_commentaires');
    }
}