<?php

namespace App\Jobs;

use App\Models\Championship;
use App\Services\ChampionshipRanking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesssChampionshipRanking implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Championship $championship,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(ChampionshipRanking $championshipRanking): void
    {
        $championshipRanking->generate($this->championship);
    }
}
