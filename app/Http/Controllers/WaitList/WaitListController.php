<?php

namespace App\Http\Controllers\WaitList;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\StreamableController;
use Illuminate\Http\Request;
use App\Models\WaitList;
use Carbon\Carbon;

class WaitListController extends StreamableController
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"WaitList"},
    *     path="/waitlist",
    *     summary="Returns a list of all patients currently waiting.",
    *     description="",
    *     operationId="api.login.waitlist",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="WaitList object fields to return.",
    *        required=false,
    *        type="string",
    *        collectionFormat="csv"
    *     ),
    *     @SWG\Parameter(
    *        name="offset",
    *        in="query",
    *        description="Offset past first match. (Requires a limit value)",
    *        required=false,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *         name="limit",
    *         in="query",
    *         description="Maximum number of results to return.",
    *         required=false,
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
    *     )
    * )
    */
    public function index(request $request)
    {
        $Waitlist = new WaitList;
        $this::getRequestOptions($request, $Waitlist);
        return $this::getManyFromRequest($Waitlist);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"WaitList"},
     *     path="/waitlist/{id}",
     *     summary="Returns a single WaitList in the system identified by {id}.",
     *     description="",
     *     operationId="api.waitlist.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the waitlist entry to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="WaitList object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
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
     *     )
     * )
     */
    public function getWaitList(request $request)
    {
        return $this->handleRequest($request, new WaitList);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"WaitList"},
     *     path="/waitlist/_search",
     *     summary="Returns a list of WaitLists in the system matching the requested fields.",
     *     description="",
     *     operationId="api.login.searchWaitList",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="WaitList object",
     *        in="body",
     *        description="WaitList object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/WaitList"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="WaitList object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Parameter(
     *        name="offset",
     *        in="query",
     *        description="Offset past first match. (Requires a limit value)",
     *        required=false,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of results to return.",
     *         required=false,
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
     *     )
     * )
     */
    public function searchWaitList(Request $request)
    {
        $this::getRequestOptions($request, new Waitlist);

        //manually construct search object since there is no "waitList" DB object
        $Name = $request->json('name');
        $WaitListTime = $request->json('login_time');
        $ExcuseTime = $request->json('excuse_time');
        $TimeOut = $request->json('scheduled_departure');
        $PatientID = $request->json('patient_id');
        $State = is_null($request->json('state')) ? '%' : $request->json('state');
        $Location = $request->json('clinic_id');
        $Location = (strtolower($Location) === 'all' || (int) $Location === -1) ? '%' : $Location;

        $WaitLists = WaitList::with('patient.prescriptions.compounds.vials', 'patient.locks')
            ->like('loginTime', $WaitListTime)
            ->like('name', $Name)
            ->like('excuseTime', $ExcuseTime)
            ->like('timeOut', $TimeOut)
            ->like('patient_id', $PatientID)
            ->searchLoginState('state', $State)
            ->like('clinic_id', $Location)
            ->limitOffset($this->RequestOptions->limit, $this->RequestOptions->offset);
        if (sizeOf($this->RequestOptions->sort) <= 0) {
            $Waitlists = $WaitLists->orderBy('loginTime', 'ASC');
        } else {
            $WaitLists = $this->querySort($WaitLists);
        }
        $Waitlists = $Waitlists->get();

        return $this->finishAndFilter($WaitLists);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"WaitList"},
     *     path="/waitlist/{id}",
     *     summary="Update a waitlist object.",
     *     description="WaitList_time is automatically generated by the system and will be ignored if present in the JSON object.",
     *     operationId="api.login.updateWaitList",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the waitlist entry to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="waitlist object",
     *        in="body",
     *        description="WaitList object containing only the fields that need to be updated. (The login_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/SwaggerWaitList"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="WaitList object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
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
    public function updateWaitList(Request $request)
    {
        return $this->handleRequest($request, new WaitList);
    }

    public function streamWaitList(Request $request)
    {
        // add updated at to user table, then query where updated at greater
        // than last updated at and only send those.
        // probably need to include redis as cache for api calls at some point
        // although probably not this one?
        $this::getRequestOptions($request);
        return response()->stream(function () {
            $lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
            if ($lastEventId == 0) {
                $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
            }
            // for the initial dataset, we need all of the patients who are currently
            // not logged out
            $LastUpdate = null;
            $FirstUpdate = null;

            $Id = $lastEventId;
            $maxId = $Id + 20; // 20 updates per stream before its reopened
            echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
            echo "retry: 2000\n"; // set retry

            while ($Id++ < $maxId) {
                if (is_null($FirstUpdate)) {
                    $FirstUpdate = $LastUpdate;
                }
                //override php timelimit to ensure the loop iteration
                //has time to complete
                set_time_limit(10);

                $results = $this->doStreamQuery($FirstUpdate, $LastUpdate);

                //first time through last update is null I think
                $now = \DB::select('select now()')[0]->{'now()'};

                if (sizeOf($results) === 0 && carbon::parse($now)->gt(carbon::parse($LastUpdate)->addSeconds(30))) {
                    $LastUpdate = $now;
                    //if its been more than 30 seconds and we haven't sent
                    //anything, send empty data to keep the connection alive.
                    $packet = [
                        'event' => 'heartbeat',
                        'data' => $LastUpdate
                    ];
                    $this->sendPacket($Id, $packet);
                } elseif (sizeOf($results) > 0) {
                    foreach ($results as $idx => $result) {
                        if (!is_null($LastUpdate)) {
                            // find the max of LastUpdate or updated_at of any
                            // new rows
                            $LastUpdate = carbon::parse($LastUpdate)->gt(carbon::parse($result->updated_at)) ? $LastUpdate : $result->updated_at;
                        } else {
                            $LastUpdate = $result->updated_at;
                        }
                        $packet = [
                            'event' => 'update',
                            'data' => $result
                        ];
                        $this->sendPacket($Id, $packet);
                    }
                    // ob_flush();
                    // flush();
                } elseif (is_null($LastUpdate)) {
                    $LastUpdate = $now;
                }
                sleep(2);
            }
        }, 200, [
        'Content-Type' => 'text/event-stream',
        'Content-Encoding' => 'none',
        'X-Accel-Buffering' => 'no',
        'Cache-Control' => 'no-cache, no-store',
        ]);
    }

    protected function doStreamQuery($FirstUpdate, $LastUpdate)
    {
        $Query = $this->queryWith(new WaitList);
        $Query = $this->queryWhere($Query);

        if ($FirstUpdate === $LastUpdate) {
            $Query = $this->queryModifier($Query);
        } else {
            $Query = $Query->OrderBy('loginTime', 'ASC');
        }

        if (isset($this->RequestOptions->clinic_id)) {
            // handles the clinic specific endpoint
            $Query = $Query->where('clinic_id', $this->RequestOptions->clinic_id);
        }
        if (!is_null($LastUpdate)) { // queries will fail if null
            // Other modifiers looking to get
            $Query = $Query->where(function ($inner) use ($LastUpdate, $FirstUpdate) {
                // want rows where created_at or updated_at indicate
                // the row is new since the last update
                $inner->where(function ($innerQuery) use ($LastUpdate) {
                    $innerQuery->where('login.updated_at', '>', $LastUpdate)
                            ->orWhere('login.created_at', '>', $LastUpdate);
                })
                    // or where the rows have a patient lock that has been updated
                    // since the last update and the login was created after the
                    // initial check
                    ->orWhere(function ($innerQuery) use ($LastUpdate, $FirstUpdate) {
                        $innerQuery->where(function ($innerQuery2) use ($FirstUpdate) {
                            $innerQuery2->where('login.updated_at', '>', $FirstUpdate)
                                ->orWhere('login.created_at', '>', $FirstUpdate);
                        })
                        ->whereHas('patient', function ($innerQuery2) use ($LastUpdate) {
                            $innerQuery2->whereHas('locks', function ($innerQuery3) use ($LastUpdate) {
                                $innerQuery3->where('patient_config.updated_at', '>', $LastUpdate);
                            });
                        });
                    });
            });
        }
        $Object = $Query->get();
        return $this->finishAndFilter($Object)->getData();
    }

    protected function queryWith($Object)
    {
        return $Object->with('patient.prescriptions.compounds.vials', 'patient.locks.user');
    }

    protected function queryModifier($Object)
    {
        return $Object->where('state', '<>', 0)
            ->orderBy('loginTime', 'ASC');
    }

    /**
     * alter the object from its DB structure to the API structure
     * @param  WaitLIst $WaitList WaitList object from the database
     * @return WaitList object to return from the API
     */
    protected function finalize($WaitList)
    {
        $Patient = $WaitList->patient;
        unset($WaitList->patient);
        $Prescriptions = $Patient->prescriptions;

        $NumBottles = 0;
        $TrayLocation = '';
        if (count($Prescriptions) !== 0) {
            foreach ($Prescriptions as $Prescription) {
                if ($Prescription->strikethrough === 'F' && $Prescription->source !== 'NON-XPS') {
                    $NewBottles = $Prescription->compounds->where('active', 'T');
                    $NumBottles = $NumBottles + $NewBottles->count();

                    //only need to get tray location once so only do it if its still blank
                    if ($TrayLocation === '') {
                        //since all tray locations should be the same, we just get the
                        //first compound_id of the first prescription and then use that
                        //to get the first vial of that compound and look at the tray
                        //location of that vial

                        foreach ($Prescription->compounds as $key => $compound) {
                            foreach ($compound->vials as $key => $vial) {
                                $TrayLocation = $vial->traylocation;
                                if ($TrayLocation !== '') {
                                    //if we found a tray location, no need to keep looking
                                    break;
                                }
                            }
                            if ($TrayLocation !== '') {
                                //if we found a tray location, no need to keep looking
                                break;
                            }
                        }
                    }
                }
            }
        }

        if (!is_null($Patient)) {
            $WaitList->patient_id = $Patient->patient_id;
            foreach ($Patient->locks as $Lock) {
                $Lock->username = isset($Lock->user) ? $Lock->user->displayname : 'n/a';
                unset($Lock->user);
            }
            $WaitList->locks = $Patient->locks;
            if ($Patient->displayname === 'manualIn') {
                $WaitList->manual_login = true;
            }
        }

        if (!isset($WaitList->name) || $WaitList->name == '') {
            $config = app()->make('config');
            $FirstInitial = strtoupper(substr($Patient->firstname, 0, 1));
            $Firstname = $FirstInitial.strtolower(substr($Patient->firstname, 1));
            $LastInitial = strtoupper(substr($Patient->lastname, 0, 1).'.');
            switch ($config->get('app.lobbyNameFix')) {
                case 1:
                    $WaitList->name = $Firstname.' '.$LastInitial;
                    break;
                case 2:
                    $WaitList->name = $FirstInitial.' '.$LastInitial;
                    break;
                default:
                    $WaitList->name = "Patient ".$WaitList->login_id;
            }
        }

        $WaitList->login_notes = $Patient->login_notes;
        $WaitList->tray_location = $TrayLocation;
        $WaitList->number_vials = $NumBottles;

        return $WaitList;
    }
}
