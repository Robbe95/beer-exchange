<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenreVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genre_votes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('genre_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();

            $table->foreign('genre_id')
                ->references('id')
                ->on('genres');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('group_id')
                ->references('id')
                ->on('groups');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genre_votes');
    }
}
