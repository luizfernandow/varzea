<?php

namespace App\Services;

use App\Models\Championship;

final class ChampionshipRanking
{
    public function generate(Championship $championship): void {
        foreach ($championship->race() as $race) {
            info("Race", ['race' => $race]);
        }
    }
}
