<?php

namespace App\Console\Commands;

use App\Models\Championship;
use App\Services\ChampionshipRanking;
use Illuminate\Console\Command;

class GenerateRanking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-ranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all championship rankings';

    /**
     * Execute the console command.
     */
    public function handle(ChampionshipRanking $championshipRanking): void
    {
        foreach (Championship::lazy() as $championship) {
            $championshipRanking->generate($championship);
            $this->info("Championship {$championship->name} processed");
        }
    }
}
