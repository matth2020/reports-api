<?php

namespace App\Listeners;

use App\Events\PrintQueued;
use App\Services\PrintNode;
use App\Events\PrintJobQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPrintJob implements ShouldQueue
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
    public function handle(PrintJobQueued $event)
    {
        $Print = $event->Print;
        $Printer = $Print->printer;
        switch (strtoupper($Printer->type)) {
            case 'EMR':
                $Print->status = 'pending';
                $Print->save();
                break;
            case 'PRINTNODE':
                if (is_null($Print->printer->external_id)) {
                    $Print->stats = 'error';
                } else {
                    $Print->status = 'pending';
                }
                $Print->save();
                if (!is_null($Print->printer->external_id)) {
                    PrintNode::createJob($Print, $title = 'Xtract print');
                }
        }
    }
}
