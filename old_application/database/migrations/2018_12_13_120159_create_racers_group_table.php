<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRacersGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('racers_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('racer_id');
            $table->unsignedInteger('race_id');
            $table->smallInteger('group');
            $table->smallInteger('number');
            $table->timestamps();
            
            $table->foreign('racer_id')->references('id')->on('racers');
            $table->foreign('race_id')->references('id')->on('races');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('racers_groups');
    }
}
