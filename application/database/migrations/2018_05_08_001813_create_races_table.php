<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateRacesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('races', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name', 256);
            $table->string('type', 32);
            $table->smallInteger('laps')->nullable();
            $table->smallInteger('hours')->nullable();
            $table->date('date_start');
            $table->time('time_start');
            $table->boolean('locked')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('races');
    }
}
