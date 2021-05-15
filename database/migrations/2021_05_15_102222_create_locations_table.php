<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');

            $table->string('country')->nullable();
            $table->string('street')->nullable();
            $table->string('street_nr')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('region')->nullable();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->boolean('public')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
