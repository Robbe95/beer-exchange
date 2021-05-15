<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->string('image')->nullable();
            $table->string('information')->nullable();
            $table->string('private_information')->nullable();
            $table->boolean('votes_locked')->default(false);
            $table->timestamp('start_time')->nullable();

            $table->bigInteger('host_id')->unsigned();

            $table->foreign('host_id')
                ->references('id')
                ->on('users');

            $table->bigInteger('location_id')->unsigned()->nullable();

            $table->foreign('location_id')
                ->references('id')
                ->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
