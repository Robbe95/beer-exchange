<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameMechanicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_mechanic', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('game_id')->unsigned();

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');

            $table->bigInteger('mechanic_id')->unsigned();

            $table->foreign('mechanic_id')
                ->references('id')
                ->on('mechanics')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_mechanic');
    }
}
