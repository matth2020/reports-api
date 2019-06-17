<?php

namespace App\Http\Controllers\Lock;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\StreamableController;
use App\Models\PatientConfig;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Padlock;
use Carbon\Carbon;

class LockController extends StreamableController
{
    /**
     * read all patient locks.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Lock"},
     *     path="/patient/{patient_id}/lock",
     *     summary="Read all patient.",
     *     description="",
     *     operationId="api.lock.readPatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose locks are to be viewed.",
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
    public function index(request $request)
    {
        $this->clearExpiredLocks($request);
        return $this::handleRequest($request, new Padlock);
    }

    /**
     * create a patient locks.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Lock"},
     *     path="/patient/{patient_id}/lock",
     *     summary="Create a patient lock.",
     *     description="",
     *     operationId="api.lock.createPatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose locks are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="patient config object",
     *        in="body",
     *        description="A patient config object with value set to any details of the lock. name will automatically be set to 'lock', patient_id will automatically be set to the id from the url and lock_id will automatically be assigned regardlesss of those provided.)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/PatientConfig"),
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
    public function createLock(request $request, $patient_id, $RequestOptions = null)
    {
        $this->clearExpiredLocks($request);
        $request->merge([
        'patient_id' => $this->RequestOptions->patient_id,
        'name' => 'lock',
        'value' => $request->input('type')
        ]);
        $this->getRequestOptions($request);
        if (!is_null($RequestOptions)) {
            $this->RequestOptions = $RequestOptions;
        }
        return $this->createFromRequest($request, new Padlock);
    }

    /**
     * create a patient locks.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Lock"},
     *     path="/patient/{patient_id}/lock/{patient_config_id}",
     *     summary="Update a patient lock.",
     *     description="",
     *     operationId="api.lock.updatePatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose locks are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="patient_config_id",
     *         in="path",
     *         description="The id of the lock to be updated.",
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
    public function updateLock(request $request)
    {
        $this->clearExpiredLocks($request);
        try {
            $Lock = Padlock::findOrFail($this->RequestOptions->id);
            $isExpired = Carbon::createFromFormat('Y-m-d H:i:s', $Lock->locked_until)->lt(Carbon::now());
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }
        if ($isExpired) {
            $Lock->markDeleted($this->RequestOptions);
            return response()->json('Resource could not be located.', 404);
        }

        $request->merge([
        'patient_id' => $this->RequestOptions->patient_id,
        'name' => 'lock',
        'value' => null,
        'lock_id' => null
        ]);
        return $this::updateFromRequest($request, new Padlock);
    }

   
    /**
     * Delete all locks.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Lock"},
     *     path="/patient/{patient_id}/lock/{patient_config_id}",
     *     summary="Remove a lock from the patient if owned by current user.",
     *     description="",
     *     operationId="api.lock",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose locks are to be updated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *      @SWG\Parameter(
     *         name="patient_config_id",
     *         in="path",
     *         description="The id of the lock to be updated.",
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
    public function deleteLock(request $request)
    {
        $this->clearExpiredLocks($request);
        return $this::handleRequest($request, new Padlock);
    }
    /**
     * Delete all locks.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Lock"},
     *     path="/patient/{patient_id}/lock",
     *     summary="Remove all of the users locks on this patient.",
     *     description="",
     *     operationId="api.deleteAlllock",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose locks are to be updated.",
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
    public function deleteAllUserLocks(request $request)
    {
        $this->clearExpiredLocks($request);
        $this->getRequestOptions($request);
        $Locks = PatientConfig::where('patient_id', $this->RequestOptions->patient_id)
            ->where('name', 'lock')
            ->where('created_by', $this->RequestOptions->user_id)
            ->with('padlock', 'patient')
            ->each(function ($lock) {
                if (!is_null($lock->patient->lock_id)) {
                    $lock->patient->lock_id = null;
                    $lock->patient->save();
                }
                if (!is_null($lock->padlock)) {
                    $lock->padlock->delete();
                }
                $lock->delete();
            });
        return response()->json($Locks, 200);
    }

    private function clearExpiredLocks($request)
    {
        $this::getRequestOptions($request);
        $Locks = Padlock::with('patient', 'patientConfig')->where('locked_until', '<=', Carbon::now()->toDateTimeString())->get();
        foreach ($Locks as $Lock) {
            if (isset($Lock->patientConfig)) {
                $Patient = $Lock->patientConfig->patient;
                $Patient->lock_id = null;
                $Patient->save();
                $Lock->patientConfig->markDeleted($this->RequestOptions);
            } elseif (isset($Lock->patient)) {
                $Lock->patient->lock_id = null;
                $Lock->patient->save();
            }
            $Lock->markDeleted($this->RequestOptions);
        }
    }

    public function streamPatientLocks(Request $request)
    {
        // add updated at to user table, then query where updated at greater
        // than last updated at and only send those.
        // probably need to include redis as cache for api calls at some point
        // although probably not this one?
        return response()->stream(function () use ($request) {
            $lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
            if ($lastEventId == 0) {
                $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
            }
            $FirstUpdate = null;

            $Id = $lastEventId;
            $maxId = $Id + 20; // 20 updates per stream before its reopened
            echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
            echo "retry: 2000\n"; // set retry

            while ($Id++ < $maxId) {
                $now = \DB::select('select now()')[0]->{'now()'};
                if (is_null($FirstUpdate)) {
                    $FirstUpdate = $now;
                }
                //override php timelimit to ensure the loop iteration
                //has time to complete
                set_time_limit(10);
                $this->clearExpiredLocks($request);
                //for all subsequent requests, we only need items with an updated_at
                //greater than or equal to the last updated at.
                $Query = $this->queryWith(new Padlock);
                $Query = $this->queryWhere($Query);
                // Other modifiers looking to get
                $Object = $this->queryModifier($Query)
                ->get();
                $result = $this->finishAndFilter($Object)->getData();
                $packet = [
                            'event' => 'update',
                            'data' => $result
                        ];
                $this->sendPacket($Id, $packet);
                sleep(2); //update every 2 seconds
            }
        }, 200, [
        'Content-Type' => 'text/event-stream',
        'X-Accel-Buffering' => 'no',
        'Cache-Control' => 'no-cache, no-store',
        ]);
    }

    protected function queryWhere($Query)
    {
        return $Query->whereHas('patientConfig', function ($innerQuery) {
            return $innerQuery->where('name', 'lock')
                ->where('patient_id', $this->RequestOptions->patient_id);
        });
    }

    protected function finalize($Object)
    {
        $Object->type = $Object->patientConfig->value;
        $Object->patient_id = $Object->patientConfig->patient_id;
        unset($Object->patientConfig);
        $Object->username = $Object->user->displayname;
        unset($Object->user);
        return $Object;
    }

    protected function queryWith($Query)
    {
        return $Query->with('patientConfig.patient', 'user');
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);

        // add locked_until details
        $Object->locked_until = Carbon::now()->addMinutes(5)->toDateTimeString();

        if (isset($Object->patientConfig)) {
            // this is an update
            $Object->updated_at = Carbon::now()->toDateTimeString();
            $Object->save();

            $PatientConfig = $Object->patientConfig;
            $PatientConfig->updated_at = Carbon::now()->toDateTimeString();
            $PatientConfig->updated_by = $request->user->user_id;
            $PatientConfig->save();

            $Patient = $PatientConfig->patient;
            if ($Patient->lock_id != $PatientConfig->lock_id) {
                $Patient->lock_id = $PatientConfig->lock_id;
                $Patient->save();
            }
        } else {
            // this is a create... we have already validated the inputs against
            // padlock requirements and know they are ok but now we should
            // validate them against patientConfig to ensure a valid lock name etc
            $PatientConfig = new PatientConfig;
            if ($PatientConfig->validate($request->all(), null)) {
                $Object->locked_by = $this->RequestOptions->user_id;
                $Object->created_at = Carbon::now()->toDateTimeString();
                $Object->save();
                // New entry so make a patient config row
                $PatientConfig->created_at = Carbon::now()->toDateTimeString();
                $PatientConfig->created_by = $this->RequestOptions->user_id;
                $PatientConfig->name = 'lock';
                $PatientConfig->value = $request->input('value');
                $PatientConfig->patient_id = $this->RequestOptions->patient_id;
                $PatientConfig->lock_id = $Object->lock_id;
                $PatientConfig->save();

                $Object->patientConfig();

                //find and update the patient
                $Patient = Patient::find($this->RequestOptions->patient_id);
                $Patient->lock_id = $Object->lock_id;
                $Patient->save();
            } elseif (isset($PatientConfig->errors()->toArray()["name"]) && $PatientConfig->errors()->toArray()["name"] == ["validation.valid_patient_lock"] && sizeOf($PatientConfig->errors()->toArray()) === 1) {
                // the only validation error is that the lock still exists. This should never happen but in case it does
                // just return the lock
                try {
                    $PatientConfig = PatientConfig::where('name', 'lock')
                        ->where('value', $request->input('type'))
                        ->where('patient_id', $this->RequestOptions->patient_id)
                        ->whereHas('padlock', function ($Query) {
                            $Query->where('created_by', $this->RequestOptions->user_id);
                        })->firstOrfail();
                    $Object->lock_id = $PatientConfig->lock_id;
                } catch (ModelNotFoundException $e) {
                    return response()->json($PatientConfig->errors(), 400);
                }
            } else {
                return response()->json($PatientConfig->errors(), 400);
            }
        }

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($Object->lock_id);
        return $this->finishAndFilter($newObject);
    }

    protected function deleteFromRequest($Object)
    {
        try {
            $Query = $this->queryWith($Object);
            $Query = $this->queryWhere($Query);
            $Lock = $Query->findOrFail($this->RequestOptions->id);
            // update patient row
            if (isset($Lock->patientConfig)) {
                $Patient = $Lock->patientConfig->patient;
                $Patient->lock_id = null;
                $Patient->save();
                $Lock->patientConfig->markDeleted($this->RequestOptions);
            } elseif (isset($Lock->patient)) {
                $Lock->patient->lock_id = null;
                $Lock->patient->save();
            }
            $Lock->markDeleted($this->RequestOptions);
            return $this->finishAndFilter($Lock);
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }
    }
}
