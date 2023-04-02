<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Championship;
use App\Services\ChampionshipRanking;

class ProcesssChampionshipRanking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Championship $championship,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(ChampionshipRanking $championshipRanking): void
    {
        $championshipRanking->generate($this->championship);
    }
}
