<?php

namespace App\Listeners;

use App\Repositories\MonthlyDistributionRepository;
use App\Events\AgenWasProperlyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SeedMonthlyDistribution
{

    protected $monthlies;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MonthlyDistributionRepository $monthlies)
    {
        $this->monthlies = $monthlies;
    }

    /**
     * Handle the event.
     *
     * @param  AgenWasProperlyCreated  $event
     * @return void
     */
    public function handle(AgenWasProperlyCreated $event)
    {
        $agen = $event->agen;
        $station = $agen->station;

        $this->monthlies->seed($station, $agen, 12);
    }
}
