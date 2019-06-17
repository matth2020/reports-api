<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\ReportQueued;
use App\Events\ReportGenerated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\BusinessLogic\Reports\TemplateEngine;
use App\BusinessLogic\Reports\ReportDataGenerator;

class GenerateReport implements ShouldQueue
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
    public function handle(ReportQueued $event)
    {
        $Report = $event->Report;
        $Template = $Report->template;
        $ReportDataGenerator = new ReportDataGenerator($Report);
        $ReportData = $ReportDataGenerator->generate();

        $document = null;
        switch (strtolower($Template->extension)) {
            case 'pdf':
                $document = new TemplateEngine(json_decode($Template->template), $ReportData);
                $document = $document->generate();
                break;
            case 'prn':
                break;
        }

        // update reports row
        $Report->xml = json_encode($ReportData);
        $Report->document = $document;
        $Report->complete_time = Carbon::now()->toDateTimeString();
        $Report->save();
        // issue report generated event
        event(new ReportGenerated($Report));
    }
}
