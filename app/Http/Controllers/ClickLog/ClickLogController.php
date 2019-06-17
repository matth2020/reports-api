<?php

namespace App\Http\Controllers\ClickLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\XisLog;
use Carbon\Carbon;

class ClickLogController extends Controller
{
    /**
         * Add a batch of click logs.
         *
         * @return \Illuminate\Http\JsonResponse
         *
         * @SWG\Post(
         *     tags={"ClickLog"},
         *     path="/click_log",
         *     summary="adds a batch of click logs to the database",
         *     description="",
         *     operationId="api.clicklog.searchClickLog",
         *     produces={
         *        "application/json"
         *     },
         *     consumes={
         *        "application/json"
         *     },
         *     @SWG\Parameter(
         *        name="clicklog object",
         *        in="body",
         *        description="An array of ClickLog objects",
         *        required=true,
         *        @SWG\Schema(ref="#/definitions/XisLog"),
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
    public function createClickLogs(request $request)
    {
        $data = $request->input('data');
        if (!is_array($data)) {
            return response()->json('Click logs must be submitted as an array of log objects.', 400);
        }
        $Error = false;
        foreach ($data as $log) {
            $Entry = new XisLog;
            if (isset($log['event'])) {
                $Entry->event = substr($log['event'], 0, 256);
            }
            if (isset($log['username'])) {
                $Entry->userName = substr($log['username'], 0, 45);
            }
            $Entry->compName = $request->ip();
            if (isset($log['patient_id'])) {
                $Entry->patient_id = $log['patient_id'];
            }
            if (isset($log['prescription_id'])) {
                $Entry->prescription_id = $log['prescription_id'];
            }
            if (isset($log['timestamp'])) {
                try {
                    $Entry->timestamp = Carbon::parse($log['timestamp'])->toDateTimeString();
                } catch (\Exception $e) {
                    // couldn't parse date so don't add it
                }
            }
            try {
                $Entry->save();
            } catch (\Exception $e) {
                // we missed this one but continue on anyway
                $Error = true;
            }
        }

        if ($Error) {
            return response()->json('One or more errors occured.', 500);
        } else {
            return response()->json('Success', 200);
        }
    }
}
