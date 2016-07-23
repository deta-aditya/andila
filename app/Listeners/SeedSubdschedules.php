<?php

namespace App\Listeners;

use App\Models\Agent;
use App\Repositories\SubscheduleRepository;
use App\Events\OrderWasAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Helper;

class SeedSubdschedules
{
    protected $subschedules;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SubscheduleRepository $subschedules)
    {
        $this->subschedules = $subschedules;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasAccepted  $event
     * @return void
     */
    public function handle(OrderWasAccepted $event)
    {
        $order = $event->order;
        $subagents = $order->schedule->agent->subagents()->schedulable()->get();
        $range = Helper::getDatesUntilNextMonth($order->schedule->scheduled_date);
        $data = [];

        foreach ($subagents as $s) {
            foreach ($range as $date) {
                $data[] = [
                    'order_id' => $order->id,
                    'subagent_id' => $s->id,
                    'scheduled_date' => $date
                ];
            }
        }

        $this->subschedules->multiple($data);
    }
}
