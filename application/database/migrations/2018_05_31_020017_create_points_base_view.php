<?php

use Illuminate\Database\Migrations\Migration;

final class CreatePointsBaseView extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::statement('CREATE VIEW base_points AS
        //     SELECT unnest(ARRAY[1,     2,   3,   4,   5,   6,   7,   8,   9,  10, 11, 12, 13, 14, 15, 16, 17, 18, 19]) AS position,
        //            unnest(ARRAY[300, 250, 200, 180, 160, 140, 130, 120, 110, 100, 90, 80, 70, 60, 50, 40, 30, 20, 10]) AS point
        // ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement('DROP VIEW base_points');
    }
}
