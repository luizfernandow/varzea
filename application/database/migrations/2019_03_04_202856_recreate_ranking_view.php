<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

final class RecreateRankingView extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::statement('DROP VIEW position_races');

        // DB::statement('CREATE VIEW position_races AS WITH positions AS (
        //             select row_number() OVER (
        //                                      PARTITION BY race_id
        //                                     ORDER BY
        //                                     count(race_id) desc, sum(time)
        //              ) as position, r.id, race_id, count(race_id) as laps 
        //              from racers as r
        //             left join laps as l on l.racer_id = r.id
        //             left join races as rc on rc.id = l.race_id
        //             where rc.deleted_at is null
        //             and r.deleted_at is null
        //             group by r.id, race_id
        //             order by race_id)
        //             select p.* from positions as p 
        //             join races as r on r.id = p.race_id and r.laps = p.laps');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW position_races');

        DB::statement('CREATE VIEW position_races AS WITH positions AS (select row_number() OVER (
                     PARTITION BY race_id
                     ORDER BY
                     sum(time)
                     ) as position, r.id, race_id, count(race_id) as laps 
                     from racers as r
                    left join laps as l on l.racer_id = r.id
                    left join races as rc on rc.id = l.race_id
                    where rc.deleted_at is null
                    and r.deleted_at is null
                    group by r.id, race_id
                    order by race_id)
                    select p.* from positions as p 
                    join races as r on r.id = p.race_id and r.laps = p.laps');
    }
}
