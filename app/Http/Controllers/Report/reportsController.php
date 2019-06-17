<?php

namespace App\Http\Controllers\Report;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use function DeepCopy\deep_copy;
use App\Models\ReportRequest;
use Illuminate\Http\Request;
use App\Events\ReportQueued;
use App\Models\PrintQueue;
use App\Models\Printer;
use App\Models\Report;
use Carbon\Carbon;
use DB;

class reportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Report"},
     *     path="/patient/{patient_id}/print_queue/{print_queue_id}",
     *     summary="Get details of a queued printjob.",
     *     description="",
     *     operationId="api.Report.printStatus",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose report is to be downloaded.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *         name="print_queue_id",
     *         in="path",
     *         description="The id of the queued print.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */

    public function printStatus(request $request)
    {
        $this->getRequestOptions($request);

        try {
            $PrintQueue = PrintQueue::whereHas('report', function ($query) {
                $query->where('patient_id', $this->RequestOptions->patient_id);
            })->findOrFail($this->RequestOptions->print_queue_id);
            $PrintQueue->getExternalStatus();
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located', 404);
        }
        return response()->json($PrintQueue, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Report"},
     *     path="/patient/{patient_id}/report/{report_id}/download",
     *     summary="Download an existing patient report.",
     *     description="",
     *     operationId="api.Report.downloadReport",
     *     produces={
     *        "application/pdf"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose report is to be downloaded.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *         name="report_id",
     *         in="path",
     *         description="The id of the report to be downloaded.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */

    public function downloadReport(request $request)
    {
        $this->getRequestOptions($request);

        try {
            $Report = Report::with('template')->where('patient_id', $this->RequestOptions->patient_id)->findOrFail($this->RequestOptions->report_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located', 404);
        }
        $Type = strtoupper($Report->template->extension) === 'PDF' ? 'application/pdf' : 'application/text';
        $Data = json_decode($Report->xml);
        
        return response($Report->document, 200)->header('Content-Type', $Type)->header('Content-Disposition', 'attachment;filename='.$Data->file_name);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Report"},
     *     path="/patient/{patient_id}/report/{report_id}",
     *     summary="Read details of an existing report.",
     *     description="",
     *     operationId="api.Report.getReport",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose report details are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *         name="report_id",
     *         in="path",
     *         description="The id of the report to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */
    public function getReport(request $request)
    {
        return $this->handleRequest($request, new Report());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Report"},
     *     path="/patient/{patient_id}/report",
     *     summary="Creates a report and initiates printing.",
     *     description="",
     *     operationId="api.Report.createReport",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Report is to be created.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="report request object",
     *        in="body",
     *        description="",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/ReportRequest"),
     *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Report"},
     *     path="/patient/{patient_id}/report/{reports_id}",
     *     summary="Creates reprints of an existing report.",
     *     description="",
     *     operationId="api.Report.recreateReport",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose Report is to be created.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
    *     @SWG\Parameter(
     *         name="reports_id",
     *         in="path",
     *         description="The id of the report to reprint.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="report request object",
     *        in="body",
     *        description="",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/ReportRequest"),
     *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */

    public function createReport(request $request)
    {
        $this->getRequestOptions($request);

        $ReportRequest = new ReportRequest();

        if (!$ReportRequest->validate($request->input(), null)) {
            return response()->json($ReportRequest->errors(), 400);
        }

        return $this->initiatePrints($request->input());
    }

    // there is a route for this but no swagger spec since this isn't to
    // be used by developers. It is part of the printNode integration.
    public function printNodeDownload(request $request, $print_queue_id)
    {
        // this is a printnode specific route only called by the printNode
        // service when a printjob has been initiated from our side during
        // the ProcessPrintJob event.
        try {
            $Job = PrintQueue::findOrFail($print_queue_id);
            $AuthString = str_replace('Basic ', $Auth);
            $AuthArray = explode(':', $AuthString);
            $Username = $AuthArray[0];
            $Password = $AuthArray[1];
            if (password_verify($Username, $Job->auth_id) &&
                password_verify($Password, $Job->auth_key) &&
                Carbon::parse($Job->created_at)->gt(Carbon::now()->subHour()) &&
                is_null($Job->processed_at)
                && $Job->status === 'pending'
            ) {
                // Auth checked out and it was created within the hour.
                $pdf = $Job->file;
                $Type = strToUpper($Job->template->type) === 'PDF' ? 'application/pdf' : 'application/text';
                $Job->status = 'sent';
                // initiate event to monitor printNode progress?
                $Job->save();
                return response($pdf, 200)->header('Content-Type', $Type)->header('Content-Disposition', 'attachment');
            }
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }
    }

    private function initiatePrints($RequestData)
    {
        $queuedPrints = [];
        if (isset($RequestData['prints'])) {
            $RequestedPrints = deep_copy($RequestData['prints']);
            unset($RequestData['prints']);
        } else {
            $RequestedPrints = [];
        }
        $Report;
        // multiple rows saved so make it a transaction
        DB::transaction(function () use (&$queuedPrints, &$Report, $RequestedPrints, $RequestData) {
            // one report row per request... this is equivalent to the single
            // report of which, multiple copies might be sent various places.
            // This only gets recorded if a report Id wasn't provided...if one
            // was provided... we are doing a reprint.
            if (isset($this->RequestOptions->reports_id)) {
                $Report = Report::find($this->RequestOptions->reports_id);
            } else {
                $Report = $this->recordReportRow($RequestData);
            }
            foreach ($RequestedPrints as $printRequest) {
                // possibly multiple printQueue rows per request... these are
                // essentially all the different places the user wants to send
                // the above report (emr, printer on my desk, etc.)
                $PrintJob = $this->recordPrintQueueRow($printRequest, $Report->reports_id);
            }
        });
        // Signal that a report has been queued so that we can begin
        // generating the actual document.
        event(new ReportQueued($Report));
        $ReportRow = Report::select('reports_id', 'patient_id', 'user_id', 'submit_time', 'template_id')->find($Report->reports_id);
        return response()->json($ReportRow, 200);
    }

    private function recordReportRow($Data)
    {
        $Report = new Report;
        $Report->xml = json_encode($Data);
        $Report->patient_id = $this->RequestOptions->patient_id;
        $Report->user_id = $this->RequestOptions->user->user_id;
        $Report->app = 'API';
        $Report->retries = 0;
        $Report->report_type = 'template';
        $Report->submit_time = Carbon::now()->toDateTimeString();
        $Report->template_id = $Data['template_id'];
        $Report->save();
        return $Report;
    }

    private function recordPrintQueueRow($printRequest, $reportId)
    {
        $Copies = $printRequest['copies'];
        $Printer = Printer::find($printRequest['printer_id']);
        if (strtoupper($Printer->type) === 'EMR') {
            $Copies = 1;
        }
        $Print = new PrintQueue;
        $Print->reports_id = $reportId;
        $Print->printer_id = $printRequest['printer_id'];
        $Print->copies = $Copies;
        $Print->created_by = $this->RequestOptions->user_id;
        $Print->created_at = Carbon::now()->toDateTimeString();
        $Print->save();

        return $Print;
    }
}
