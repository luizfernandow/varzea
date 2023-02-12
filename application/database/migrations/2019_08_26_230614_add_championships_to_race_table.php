<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class AddChampionshipsToRaceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('races', function (Blueprint $table): void {
            $table->unsignedInteger('championship_id')->nullable();
            $table->foreign('championship_id')->references('id')->on('championships');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('races', function (Blueprint $table): void {
            $table->dropColumn('championship_id');
        });
    }
}
