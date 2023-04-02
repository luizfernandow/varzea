<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('championship_rankings', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedInteger('championship_id');
            $table->unsignedInteger('racer_id');
            $table->smallInteger('points');

            $table->foreign('championship_id')->references('id')->on('championships');
            $table->foreign('racer_id')->references('id')->on('racers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('championship_rankings');
    }
};
