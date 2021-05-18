<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMechanicVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mechanic_votes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('mechanic_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();

            $table->foreign('mechanic_id')
                ->references('id')
                ->on('mechanics')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
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
        Schema::dropIfExists('mechanic_votes');
    }
}
