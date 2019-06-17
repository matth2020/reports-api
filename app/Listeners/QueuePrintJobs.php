<?php

namespace App\Listeners;

use App\Models\PrintQueue;
use App\Events\ReportGenerated;
use App\Events\PrintJobQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuePrintJobs implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(ReportGenerated $event)
    {
        $Report = $event->Report;
        foreach ($Report->prints as $print) {
            // issue an event to handle each individual print
            event(new PrintJobQueued($print));
        }
    }
}
