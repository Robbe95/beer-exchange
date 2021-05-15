<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followed_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('follower_id')->unsigned();

            $table->foreign('follower_id')
                ->references('id')
                ->on('users');

            $table->bigInteger('followed_id')->unsigned();

            $table->foreign('followed_id')
                ->references('id')
                ->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followed_users');
    }
}
