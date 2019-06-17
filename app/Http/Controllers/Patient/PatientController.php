<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\LockableController;
use Illuminate\Http\Request;
use App\Models\Injection;
use App\Models\Compound;
use App\Models\Xisprefs;
use App\Models\Xpsprefs;
use App\Models\Patient;
use App\Models\Login;
use Carbon\Carbon;
use App\Models\Image;

class PatientController extends LockableController
{
    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Patient"},
     *     path="/patient/{id}",
     *     summary="Returns a single patient in the system identified by {id}.",
     *     description="",
     *     operationId="api.patient.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the patient to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Patient object fields to return.",
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
    public function getPatient(request $request)
    {
        return $this::handleRequest($request, new Patient);
    }

    /**
     * Display a listing of the resource matching search criterion and using limit / offset.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Patient"},
     *     path="/patient/_search",
     *     summary="Returns a list of patients in the system matching the requested fields.",
     *     description="",
     *     operationId="api.patient.searchPatient.offset.limit",
     *     produces={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="patient object",
     *        in="body",
     *        description="Patient object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Patient"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Patient object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
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
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     * )
     */
    public function searchPatient(Request $request)
    {
        //Make an object to hold results and count details
        $resultDetails = app()->make('stdClass');

        // if we got a number do the number search
        if (($request->json('number') != null) && is_numeric($request->number)) {
            $Number = $request->number;
            $Patients = Patient::with('prescriptions.compounds.vials')
                ->where('archived', 'F')
                ->where(function ($query) use ($Number) {
                    $query->whereHas('prescriptions', function ($query) use ($Number) {
                        $query->whereHas('compounds.vials', function ($query) use ($Number) {
                            $query->where('barcode', $Number);
                        });
                    })
                    ->orWhereHas('prescriptions', function ($query) use ($Number) {
                        $query->where('prescription_num', $Number);
                    })
                    ->orWhere('patient_id', $Number);
                })
                ->get();

            $Result = array();
            foreach ($Patients as $Patient) {
                // Build standard objects with out all of the attached rx data
                $newPatient = app()->make('stdClass');
                $newPatient->firstname = $Patient->firstname;
                $newPatient->lastname = $Patient->lastname;
                $newPatient->mi = $Patient->mi;
                $newPatient->dob = $Patient->dob;
                $newPatient->mrn = $Patient->mrn;
                $newPatient->patient_id = $Patient->patient_id;
                array_push($Result, $newPatient);
            }

            $resultDetails->count = $Patients->count();
            $resultDetails->distinct_count = $Patients->count();
            $resultDetails->distinct = 'patient_id';
            $resultDetails->matches = $Result;

            return response()->json($resultDetails);
        }
        // else do string search
        $Search = $request->json('search');
        $Searches = explode(',', $Search);
        $Searches = array_map('trim', $Searches);

        // This checks for the case when the search string was something like ',,,'
        // with no actionable search data
        $ValidSearches = array_filter($Searches, function ($value) {
            return $value !== '';
        });

        if (sizeof($ValidSearches) <= 0) {
            return response()->json(array());
        }

        // The switch statement below is used to search for distinct patients based
        // on the search terms provided.
        // 1) The search comes as a comma separated list of lastname, firstname, mi,
        //    chart, dob but may be shorter (lastname, firstname only for example.)
        // 2) The fields of the left portion of the search must be exact matches
        //    with their respective values. (eg, "search = smith, joe, test". In
        //    this case 'lastname="smith" and firstname="joe"')
        // 3) The single right-most field it used in a like search for any remaining
        //    search fields (continuing previous example, lastname and firstname
        //    have exact searches so the remainder of the query is 'and (mi like
        //    "%test%" or chart like "%test%" or dob like "test")')
        // 4) If any csv search value is blank, it is excluded from an exact match
        //    (eg. search = ",joe,test". In this case the left most csv value is
        //    blank so no lastname match is added to the query. The final result
        //    would be 'where firstname="joe" and (mi like "%test%" or chart like
        //    "%test%" or dob like "test")')
        // 5) when the query executes, it selects distinct values for the left-most
        //    unknown search term. (eg, in all previous examples, lastname and
        //    firstname where provided, mi, chart, and dob where searched so all
        //    would select distinct mi)
        // 6) If a query returns only one distinct result, The search criterion is
        //    adapted to rerun the search based on if the user had provided the
        //    newly learned information in the original search term. The end result
        //    of this behaviour is that, if a user searches for lastname smith and
        //    the system has two matching users differing only by dob, the following
        //    series of queries would execute.
        //     initial search request = "smith"
        //     case 1 runs 'select distinct lastname from patient where archived="F"
        //         and (lastname like "%smith%" or firstname  like "%smith%" or mi
        //         like "%smith%" or chart like "%smith%" or dob like "%smith%")'
        //         Only one distinct match is returned for smith so search is
        //         automatically adapted to "smith," which causes...
        //     case 2 to run 'select distinct firstname from patient where
        //         archived="F" and lastname = "smith" and (firstname like "%" or mi
        //         like "%" or chart like "%" or dob like "%")'
        //         Only one distinct match is returned for firstname = joe so search
        //         is automatically adapted to "smith, joe," and carried forward to
        //         the next case
        //    This waterfall effect continues through the switch statement until
        //    either a single patient is selected or multiple distinct entries are
        //    returned.
        switch (sizeof($Searches)) {
            case 1:
                $Patients = Patient::where('archived', 'F')
                    ->where(function ($query) use ($Searches) {
                        $query->where('lastname', 'like', $Searches[0].'%')
                        ->orWhere('firstname', 'like', $Searches[0].'%')
                        ->orWhere('mi', 'like', $Searches[0].'%')
                        ->orWhere('chart', 'like', $Searches[0].'%')
                        ->orWhere('dob', 'like', $Searches[0].'%');
                    });
                // Get total matches count
                $resultDetails->count = $Patients->count();
                // Get distinct lastnames to return
                $Patients = $Patients->distinct('lastname')
                    ->get(['lastname']);

                $resultDetails->distinct_count = $Patients->count();
                $resultDetails->distinct = 'lastname';
                $resultDetails->matches = $Patients;

                if ($Patients->count() == 1) {
                    if (preg_match('/'.$Searches[0].'/i', $Patients[0]->lastname)) {
                        $Searches[1] = '';
                        $Searches[0] = $Patients[0]->lastname;
                    } else {
                        $Searches[1] = $Searches[0];
                        $Searches[0] = $Patients[0]->lastname;
                    }
                } else {
                    break;
                }
                // Intentinall fall through to next case
            case 2:
                $Patients = Patient::where(function ($query) use ($Searches) {
                    //if searches[0] is a value, lastname must match it
                    //otherwise lastname has no requirement
                    if ($Searches[0] != '') {
                        $query->where('lastname', $Searches[0]);
                    }
                })
                    ->where('archived', 'F')
                    ->where(function ($query) use ($Searches) {
                        $query->orWhere('firstname', 'like', $Searches[1].'%')
                        ->orWhere('mi', 'like', $Searches[1].'%')
                        ->orWhere('dob', 'like', $Searches[1].'%')
                        ->orWhere('chart', 'like', $Searches[1].'%');
                    });
                $resultDetails->count = $Patients->count();
                $Patients = $Patients->distinct('firstname')
                    ->get(['lastname','firstname']);

                $resultDetails->distinct_count = $Patients->count();
                $resultDetails->distinct = 'lastname, firstname';
                $resultDetails->matches = $Patients;

                if ($Patients->count() == 1) {
                    if (preg_match('/'.$Searches[1].'/i', $Patients[0]->firstname)) {
                        $Searches[2] = '';
                        $Searches[1] = $Patients[0]->firstname;
                        $Searches[0] = $Patients[0]->lastname;
                    } else {
                        $Searches[2] = $Searches[1];
                        $Searches[1] = $Patients[0]->firstname;
                        $Searches[0] = $Patients[0]->lastname;
                    }
                } else {
                    break;
                }
                // Intentinall fall through to next case
            case 3:
                $Patients = Patient::where(function ($query) use ($Searches) {
                    if ($Searches[0] != '') {
                        $query->where('lastname', $Searches[0]);
                    }
                })
                    ->where(function ($query) use ($Searches) {
                        if ($Searches[1] != '') {
                            $query->where('firstname', $Searches[1]);
                        }
                    })
                    ->where('archived', 'F')
                    ->where(function ($query) use ($Searches) {
                        $query->orWhere('mi', 'like', $Searches[2].'%')
                        ->orWhere('dob', 'like', $Searches[2].'%')
                        ->orWhere('chart', 'like', $Searches[2].'%');
                    });
                $resultDetails->count = $Patients->count();
                $Patients = $Patients->distinct('mi')
                    ->get(['lastname','firstname','mi']);

                $resultDetails->distinct_count = $Patients->count();
                $resultDetails->distinct = 'lastname, firstname, mi';
                $resultDetails->matches = $Patients;

                if ($Patients->count() == 1) {
                    if (preg_match('/'.$Searches[2].'/i', $Patients[0]->mi)) {
                        $Searches[3] = '';
                        $Searches[2] = $Patients[0]->mi;
                        $Searches[1] = $Patients[0]->firstname;
                        $Searches[0] = $Patients[0]->lastname;
                    } else {
                        $Searches[3] = $Searches[2];
                        $Searches[2] = $Patients[0]->mi;
                        $Searches[1] = $Patients[0]->firstname;
                        $Searches[0] = $Patients[0]->lastname;
                    }
                } else {
                    break;
                }
                // Intentinall fall through to next case
            case 4:
                $Patients = Patient::where(function ($query) use ($Searches) {
                    if ($Searches[0] != '') {
                        $query->where('lastname', $Searches[0]);
                    }
                })
                    ->where(function ($query) use ($Searches) {
                        if ($Searches[1] != '') {
                            $query->where('firstname', $Searches[1]);
                        }
                    })
                    ->where(function ($query) use ($Searches) {
                        if ($Searches[2] != '') {
                            $query->where('mi', $Searches[2]);
                        }
                    })
                    ->where('archived', 'F')
                    ->where(function ($query) use ($Searches) {
                        $query->orWhere('dob', 'like', $Searches[3].'%')
                        ->orWhere('chart', 'like', $Searches[3].'%');
                    });
                $resultDetails->count = $Patients->count();
                $Patients = $Patients->distinct('chart')
                    ->get(['lastname','firstname','mi','chart']);

                $resultDetails->distinct_count = $Patients->count();
                $resultDetails->distinct = 'lastname, firstname, mi, mrn';
                $resultDetails->matches = $Patients;

                if ($Patients->count() == 1) {
                    if (preg_match('/'.$Searches[3].'/i', $Patients[0]->chart)) {
                        $Searches[4] = '';
                        $Searches[3] = $Patients[0]->chart;
                        $Searches[2] = $Patients[0]->mi;
                        $Searches[1] = $Patients[0]->firstname;
                        $Searches[0] = $Patients[0]->lastname;
                    } else {
                        $Searches[4] = $Searches[3];
                        $Searches[3] = $Patients[0]->chart;
                        $Searches[2] = $Patients[0]->mi;
                        $Searches[1] = $Patients[0]->firstname;
                        $Searches[0] = $Patients[0]->lastname;
                    }
                } else {
                    break;
                }
                // Intentinall fall through to next case
            case 5:
                $Patients = Patient::where(function ($query) use ($Searches) {
                    if ($Searches[0] != '') {
                        $query->where('lastname', $Searches[0]);
                    }
                })
                    ->where(function ($query) use ($Searches) {
                        if ($Searches[1] != '') {
                            $query->where('firstname', $Searches[1]);
                        }
                    })
                    ->where(function ($query) use ($Searches) {
                        if ($Searches[2] !='') {
                            $query->where('mi', $Searches[2]);
                        }
                    })
                    ->where(function ($query) use ($Searches) {
                        if ($Searches[3] !='') {
                            $query->where('chart', $Searches[3]);
                        }
                    })
                    ->where('archived', 'F')
                    ->where('dob', 'like', $Searches[4].'%');
                $resultDetails->count = $Patients->count();
                $Patients = $Patients->distinct('dob')
                    ->get(['lastname','firstname','mi','chart','dob']);

                $resultDetails->distinct_count = $Patients->count();
                $resultDetails->distinct = 'lastname, firstname, mi, mrn, dob';
                $resultDetails->matches = $Patients;
        }

        if ($resultDetails->count == 1) {
            if (isset($Patients[0]->dob)) {
                // if it got all the way to dob a specific patient was selected
                $Patient = Patient::with('locks')
                    ->where('archived', 'F')
                    ->where('lastname', $Patients[0]->lastname)
                    ->where('firstname', $Patients[0]->firstname)
                    ->where('mi', $Patients[0]->mi)
                    ->where('dob', $Patients[0]->dob)
                    ->where('chart', $Patients[0]->chart)
                    ->get(['firstname','lastname','mi','dob','chart','patient_id']);

                $resultDetails->distinct_count = $Patients->count();
                $resultDetails->count = $Patients->count();
                $resultDetails->distinct = 'lastname, firstname, mi, mrn, dob';
                $resultDetails->matches = $Patient;

                if ($resultDetails->count == 1) {
                    return response()->json($resultDetails);
                }
            } else {
                return respones()->json($resultDetails);
            }
        } else {
            // if greater than 5 distinct matches, return an empty array
            if ($resultDetails->count > 10) {
                $resultDetails->matches = array();
            }
            return response()->json($resultDetails);
        }
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Patient"},
     *     path="/patient",
     *     summary="Create a new patient.",
     *     description="",
     *     operationId="api.patient.createPatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="patient object",
     *        in="body",
     *        description="Patient object to be created in the system. (The patient_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Patient"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Patient object fields to return.",
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
    public function createPatient(Request $request)
    {
        //find out if system supports patient creation
        $CreatePatient = explode(',', xpsprefs::first()->prefset1)[0];

        if ($CreatePatient) {
            return $this->handleRequest($request, new Patient);
        } else {
            return response()->json('Patients are read only on this system', 401);
        }
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Patient"},
     *     path="/patient/{id}",
     *     summary="Mark a patient as deleted.",
     *     description="",
     *     operationId="api.patient.deletePatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the patient to delete.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Patient object fields to return.",
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
    public function deletePatient(request $request)
    {
        $CreatePatient = explode(',', xpsprefs::first()->prefset1)[0];

        if ($CreatePatient) {
            return $this::handleRequest($request, new Patient);
        } else {
            return response()->json('Patients cannot be created or deleted on this system', 401);
        }
    }


    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Patient"},
     *     path="/patient/{id}",
     *     summary="Update a patient object.",
     *     description="",
     *     operationId="api.patient.updatePatient",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the patient to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="patient object",
     *        in="body",
     *        description="Patient object containing only the fields that need to be updated. (The patient_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Patient"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Patient object fields to return.",
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
    public function updatePatient(Request $request)
    {
        //find out if system supports patient creation
        $CreatePatient = explode(',', xpsprefs::first()->prefset1)[0];

        //Need to add check to allow update even if create patient is false if props being updated
        //are allowed in our system (displayname, pin, state, etc) Currently hardcoded true.
        $XtractUpdateable = true;
        if ($CreatePatient === "T" || $XtractUpdateable) {
            $this::getRequestOptions($request);
            try {
                $Patient = Patient::findOrFail($this->RequestOptions->patient_id);
            } catch (ModelNotFoundException $ex) {
                return response()->json('the requested resource could not be located.', 404);
            }
            //now that we have patient details...

            //if firstname wasn't in the request, merge it in using the value from the query
            $firstname = $request->input('firstname');

            if (is_null($firstname)) {
                $request->merge(['firstname' => $Patient->firstname]);
            }

            //if lastname wasn't in the request, merge it in using the value from the query
            $lastname = $request->input('lastname');

            if (is_null($lastname)) {
                $request->merge(['lastname' => $Patient->lastname]);
            }

            //if mi wasn't in the request, merge it in using the value from the query
            $mi = $request->input('mi');

            if (is_null($mi)) {
                $request->merge(['mi' => $Patient->mi]);
            }

            //if dob wasn't in the request, merge it in using the value from the query
            $dob = $request->input('dob');

            if (is_null($dob)) {
                $request->merge(['dob' => $Patient->dob]);
            }

            //if chart wasn't in the request, merge it in using the value from the query
            $chart = $request->input('chart');

            if (is_null($chart)) {
                $request->merge(['chart' => $Patient->chart]);
            }

            return $this->handleRequest($request, new Patient);
        } else {
            return response('Patients are read only on this system.', 401);
        }
    }

    public function updatePatientLink(request $request)
    {
        $this->getRequestOptions($request);
        $this->RequestOptions->id = $this->RequestOptions->patient_id;
        $this->isCreate = true;
        return $this->updateFromRequest($request, new Patient);
    }

    protected function validateAndSave(request $request, $Object = null)
    {
        if ($this->getLock()) {
            $request = $this->fixCreatedUpdatedInfo($request);
            if ($Object->validate($request->all(), $this->RequestOptions->patient_id)) {
                return $Object = $this->saveAndQuery($request, $Object);
            } else {
                return response()->json($Object->errors(), 400);
            }
        } else {
            return response()->json('Another user currently owns one or more locks required to perform this action. Please try again later.', 401);
        }
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        $Object->save();
        if (isset($this->RequestOptions->waitlist_id)) {
            $result = $this->linkManualIn($Object, $this->RequestOptions->waitlist_id);
            if (!is_null($result)) {
                return $result;
            }
        }
        //If we are saving something with a null id it must have been a create
        //so use the objects primary key to find the created id.
        $primaryId = is_null($this->RequestOptions->patient_id) ? $Object[$Object->getKeyName()] : $this->RequestOptions->patient_id;

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($primaryId);
        return $this->finishAndFilter($newObject);
    }

    public function linkManualIn($Patient, $LoginId)
    {
        try {
            $LoginRow = Login::findOrFail($LoginId);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located.', 404);
        }
        try {
            $LoginPatient = Patient::where('displayname', 'manualIn')->where('archived', 'T')->findOrFail($LoginRow->patient_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The waitlist_id must reference a manual login patient.', 404);
        }
        if (!is_null($LoginPatient)) {
            $LoginRow->patient_id = $Patient->patient_id;
            if (!is_null($Patient->displayname) && $Patient->displayname != '') {
                $LoginRow->Name = $Patient->displayname;
            }
            $LoginRow->save();
            return null;
        } else {
            return response()->json('The waitlist_id must reference a manual login patient.', 401);
        }
    }

    public function patientImage(Request $request)
    {
        $this->getRequestOptions($request);
        try {
            $Patient = Patient::with('image')->findOrFail($this->RequestOptions->patient_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located', 404);
        }

        // do validation of filesize.
        $Validator = \Validator::make($request->all(), [
            'patientImage' => 'max:'.strval(1024*5),
        ]);
        if ($Validator->fails()) {
        \Log::info($Validator->errors());
            return response()->json($Validator->errors(), 400);
        }

        if (isset($Patient->image)) {
            $PatientImage = $Patient->image;
        } else {
            $PatientImage = new Image;
        }

        $type = $request->input('type');


        // save the image to a temp file
        $image = $request->file('patientImage');
        $exif = exif_read_data($image);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $rotate = 90;
                    break;
                case 3:
                    $rotate = 180;
                    break;
                case 6:
                    $rotate = -90;
                    break;
            }
        } else {
            $rotate = 0;
        }
        $path = $request->file('patientImage')->store('tmp');
        \Storage::setVisibility($path, 'private');

        try {
            $data = $this->resizeImage(base_path('storage/app/'.$path), 150, 150, true, $type, $rotate);
            \Storage::delete($path);

            $PatientImage->data = $data;
            $PatientImage->type=$type;
            $PatientImage->save();

            $Patient->face_image_id = $PatientImage->image_id;
            $Patient->save();

            return response()->json($PatientImage, 200);
        } catch (Exception $e) {
            return response()->json(['image' => 'Error storing image'], 500);
        }
    }

    //from https://stackoverflow.com/questions/14649645/resize-image-in-php
    //and tweaked from there
    private function resizeImage($file, $w, $h, $crop = false, $type = 'image/jpeg', $rotate = 0)
    {
        $top = 0;
        $left = 0;
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $adjustedWidth = ceil($width-($width*abs($r-$w/$h)));
                $left = ($width - $adjustedWidth) / 2;
                $width = $adjustedWidth;
            } else {
                $adjustedHeight = ceil($height-($height*abs($r-$w/$h)));
                $top = ($height - $adjustedHeight) / 2;
                $height = $adjustedHeight;
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }

        switch ($type) {
            case 'image/jpeg':
                $src = imagecreatefromjpeg($file);
                break;
            case 'image/png':
                $src = imagecreatefrompng($file);
                break;
            case 'image/gif':
                $src = imagecreatefromgif($file);
                break;
        }
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, $left, $top, $newwidth, $newheight, $width, $height);
        $dst = imagerotate($dst,$rotate,0);
        ob_start();
        switch ($type) {
            case 'image/jpeg':
                imagejpeg($dst, null, 90);
                break;
            case 'image/png':
                imageflip($dst, IMG_FLIP_HORIZONTAL);
                imagepng($dst, null, 8);
                break;
            case 'image/gif':
                imagegif($dst, null);
                break;
        }
        $imageblob = ob_get_contents();
        ob_clean();
        imagedestroy($dst);
        return base64_encode($imageblob);
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  Patient $Patient object returned from the database
     * @param  $Filter of properties to include in returned object
     * @param  $AllowedFields
     * @return Patient object
     */
    protected function finalize($Patient)
    {
        $Patient = $this->getLastVisit($Patient);
        $Patient = $this->getInjectionAnalyitics($Patient);

        unset($Patient->loginState);
        unset($Patient->prescriptions);

        if (!is_null($Patient->provider)) {
            $Provider = $Patient->provider->displayname;
            unset($Patient->provider);
            $Patient->provider = $Provider;
        }

        return $Patient;
    }

    protected function queryWith($Query)
    {
        return $Query->with([
            'configs' => function ($query) {
                $query->where('name', '!=', 'lock');
            },
            'loginState',
            'provider:provider_id,displayname',
            'account',
            'logins' => function ($query) {
                $query->where('loginTime', '<', 'date(now())')
                ->orderby('loginTime', 'desc');
            },
            'codes' => function ($query) {
                $query->where('deleted', 'F')
                ->select('text', 'code', 'flags.flag_id');
            },
            'image'
        ]);
    }


    /**
     * Find date of last visit and apply to patient object
     * @param  patient $Patient Patient to find last visit for
     * @return patient           Patient object with last visit info applied
     */
    private function getLastVisit(patient $Patient)
    {
        $LastLogin = Count($Patient->logins) > 0 ? $Patient->logins[0] : null;
        $LoginTime = isset($LastLogin->loginTime) ? $LastLogin->logintime : null;
        if (!is_null($LoginTime)) {
            $Patient->last_visit = Carbon::createFromFormat('Y-m-d H:i:s', $LoginTime)->format('Y-m-d');
        }
        unset($Patient->logins);
        return $Patient;
    }

    /**
     * Find the date of the patients first injection
     * @param  Patient $Patient
     * @return Patient
     */
    private function getInjectionAnalyitics(Patient $Patient)
    {
        //get rx_ids associated with the patient
        $RXIDS = $Patient->prescriptions->pluck('prescription_id');
        //get compound ids associated with the rxs
        $CPIDS = Compound::wherein('rx_id', $RXIDS)->get()->pluck('compound_id');
        //get the max injection date associated with the compounds
        $Injections = Injection::wherein('compound_id', $CPIDS)->get();

        if (!isset($Patient->shot_start) || $Patient->shot_start == '') {
            // only calculate it if it hasn't been manually set in the
            // patient row
            $ShotStartDate = $Injections->min('date');
            if (!is_null($ShotStartDate)) {
                $Patient->shot_start = Carbon::createFromFormat('Y-m-d H:i:s', $ShotStartDate)->format('Y-m-d');
            } else {
                $Patient->shot_start = null;
            }
        }

        $Reactions = $this::getReactionNames();
        //remove left most "no reaction" item from each array
        array_shift($Reactions->local);
        array_shift($Reactions->systemic);
        $UpperLocal = array_map("strToUpper", $Reactions->local);
        $UpperSystemic = array_map("strToUpper", $Reactions->systemic);

        $Patient->number_local_reactions = $Injections->wherein('reaction', array_merge($UpperLocal, $Reactions->local))->Count();
        $Patient->number_systemic_reactions = $Injections->wherein('sysreaction', array_merge($UpperSystemic, $Reactions->systemic))->count();
        $Total = $Injections->where('date', '>=', $Patient->lateInjectionsStartDate)->count();
        $NumLate = $Patient->numLateInjections;

        if ($NumLate === 0 || $Total === 0) {
            $Patient->compliance_score = 100;
        } elseif ($NumLate === $Total) {
            $Patient->compliance_score = 0;
        } else {
            $Patient->compliance_score  = round(100 - (($NumLate / $Total) * 100));
        }

        return $Patient;
    }

    protected static function getReactionNames()
    {
        $ReactionStrings = Xisprefs::firstOrFail();
        $LocalNames = explode(',', $ReactionStrings->reactNamesL);
        $SystemicNames = explode(',', $ReactionStrings->reactNamesS);
        $Reactions = app()->make('stdClass');
        $Reactions->systemic = $SystemicNames;
        $Reactions->local = $LocalNames;

        return $Reactions;
    }
}
