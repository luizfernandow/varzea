<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateRacersGroupTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('racers_groups', function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('racers_groups');
    }
}
