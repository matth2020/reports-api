<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Config\ConfigController;
use App\Http\Controllers\LockableController;
use Illuminate\Validation\Validator;
use App\Models\PatientQuestionnaire;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Config;
use App\Models\Answer;
use Carbon\Carbon;

class PatientQuestionnaireController extends LockableController
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Questionnaire/Assignment"},
    *     path="/patient/{patient_id}/questionnaire/assigned",
    *     summary="Returns a list of all questionnaires in the system.",
    *     description="",
    *     operationId="api.questionnaire.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient who questionnaire assignments are being read.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Questionnaire object fields to return.",
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
        return $this->handleRequest($request, new PatientQuestionnaire);
    }

    protected function queryWith($Query)
    {
        return $Query->with('questionnaire');
    }

    protected function queryModifier($Query)
    {
        return $Query->whereHas('questionnaire', function ($innerQuery) {
            $innerQuery->where('deleted', 'F');
        })->where('patient_id', $this->RequestOptions->patient_id);
    }

    protected function finalize($Questionnaire)
    {
        return $Questionnaire->questionnaire;
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire/Assignment"},
     *     path="/patient/{patient_id}/questionnaire/{questionnaire_id}/assignment",
     *     summary="Assign a questionnaire to a patient.",
     *     description="",
     *     operationId="api.PatientQuestionnaire.createPatientQuestionnaire",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient to assign the questionnaire to.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *         name="questionnaire_id",
     *         in="path",
     *         description="The id of the questionnaire to be assigned.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PatientQuestionnaire object fields to return.",
     *        required=false,
     *        type="array",
     *        @SWG\Items(type="string"),
     *        collectionFormat="csv",
     *     ),
     *     @SWG\Response(
     *        response=200,
     *        description="PatientQuestionnaire object that was created.",
     *        @SWG\Schema(ref="#/definitions/PatientQuestionnaire"),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Malformed request.",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found.",
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     */
    public function createAssignment(Request $request)
    {
        $this->getRequestOptions($request);
        $PatientQuestionnaire = new PatientQuestionnaire();
        $PatientQuestionnaire->questionnaire_id = $this->RequestOptions->questionnaire_id;
        $PatientQuestionnaire->patient_id = $this->RequestOptions->patient_id;
        //By moving the ids into the (empty) post data... we can use or normal validation
        //code
        $request->merge(
            array(
                'patient_id' => $this->RequestOptions->patient_id,
                'questionnaire_id' => $this->RequestOptions->questionnaire_id
            )
        );

        if ($PatientQuestionnaire->Validate($request->all(), $this->RequestOptions->patient_id)) {
            $PatientQuestionnaire->load('questionnaire');
            // calculate frequency the questionnaire should be administered at. Should be the
            // requested value or the minimum associated with the questionnaire, whichever is greater.
            $RequestedFrequency = isset($PatientQuestionnaire->frequency) ? $PatientQuestionnaire->frequency : $PatientQuestionnaire->questionnaire->minimum_frequency;
            $MinimumFrequency = $PatientQuestionnaire->questionnaire->minimum_frequency;
            $ActualFequency = $RequestedFrequency < $MinimumFrequency ? $MinimumFrequency : $RequestedFrequency;
            $PatientQuestionnaire->frequency = $ActualFequency;
            $PatientQuestionnaire->recurring = 'T';
            $PatientQuestionnaire->save();
            return response()->json($PatientQuestionnaire);
        } else {
            return response()->json($PatientQuestionnaire->errors(), 400);
        }
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire/Assignment"},
     *     path="/group/{group}/questionnaire/{questionnaire_id}/assignment",
     *     summary="Assign a questionnaire to a group of patients.",
     *     description="",
     *     operationId="api.PatientQuestionnaire.createGroupAssignment",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="questionnaire_id",
     *         in="path",
     *         description="The id of the questionnaire to be assigned.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *         name="group",
     *         in="path",
     *         description="The group of patients to assign the questionnaire to. Valid groups are in config where section='boxNames'.",
     *         required=true,
     *         type="string"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PatientQuestionnaire object fields to return.",
     *        required=false,
     *        type="array",
     *        @SWG\Items(type="string"),
     *        collectionFormat="csv",
     *     ),
     *     @SWG\Response(
     *        response=200,
     *        description="PatientQuestionnaire object that was created.",
     *        @SWG\Schema(ref="#/definitions/PatientQuestionnaire"),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Malformed request.",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found.",
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     */
    public function createGroupAssignment(Request $request, $group)
    {
        $this->getRequestOptions($request);
        $RequestedGroups = array($group); //Originally group was passed in as a json element that was allowed to be an array of groups. This is no longer used but I am tying into the existing code using an array of 1 element for now. This could be simplified later.
        $AvailableGroups = $this->getAvailableGroups();

        $Errors = $this->validateGroupRequest($this->RequestOptions->questionnaire_id, $RequestedGroups);
        if (!is_null($Errors)) {
            return response()->json($Errors, 400);
        }

        $RequestedPatients = array();
        foreach ($RequestedGroups as $key => $RequestedGroup) {
            $Patients = Patient::whereDoesntHave('patientQuestionnaires', function ($query) use ($RequestedGroup) {
                //exclude patients who are already assigned to the questionnaire
                $query->where('patient_questionnaire.questionnaire_id', $this-> RequestOptions->questionnaire_id);
            })
                ->where('archived', 'F')
                ->where(function ($query2) use ($RequestedGroup, $AvailableGroups) {
                    if (strToUpper($RequestedGroup) !== 'ALL') {
                        $query2->where($AvailableGroups[strToUpper($RequestedGroup)], 'T');
                    }
                })
                ->pluck('patient_id')
                ->toArray();

            // Merge unique patient ids
            $RequestedPatients = array_unique(array_merge($Patients, $RequestedPatients));
        }

        $MinimumFrequency = Questionnaire::find($this->RequestOptions->questionnaire_id)->minimum_frequency;
        $RequestedFrequency = !is_null($request->json('frequency')) ? $request->json('frequency') : $MinimumFrequency;
        $ActualFequency = $RequestedFrequency < $MinimumFrequency ? $MinimumFrequency : $RequestedFrequency;

        $InsertData = array();
        foreach ($RequestedPatients as $key => $PatientId) {
            array_push($InsertData, [
                'patient_id' => $PatientId,
                'questionnaire_id' => $this->RequestOptions->questionnaire_id,
                'recurring' => 'T',
                'frequency' => $ActualFequency
            ]);
        }

        PatientQuestionnaire::insert($InsertData);

        return response()->json(sizeOf($InsertData));
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Questionnaire/Assignment"},
     *     path="/patient/{patient_id}/questionnaire/{questionnaire_id}/assignment",
     *     summary="Mark a PatientQuestionnaire as deleted.",
     *     description="",
     *     operationId="api.PatientQuestionnaire.deletePatientQuestionnaire",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient to unassign the questionnaire from.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="questionnaire_id",
     *        in="path",
     *        description="Id of the questionnaire to be unassigned.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PatientQuestionnaire object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *        response=200,
     *        description="PatientQuestionnaire object that was deleted.",
     *        @SWG\Schema(ref="#/definitions/PatientQuestionnaire"),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Malformed request.",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found.",
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     */
    public function deleteAssignment(request $request)
    {
        $this->getRequestOptions($request);
        try {
            $PatientQuestionnaire = PatientQuestionnaire::where('patient_id', $this->RequestOptions->patient_id)->where('questionnaire_id', $this->RequestOptions->questionnaire_id)->firstOrFail();
            $PatientQuestionnaire->delete();
            $PatientQuestionnaire->load('questionnaire');
            return response()->json($PatientQuestionnaire, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested assignment was not found.', 404);
        }
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Questionnaire/Assignment"},
     *     path="/group/{group}/questionnaire/{questionnaire_id}/assignment",
     *     summary="Remove a questionnaire assignment by group.",
     *     description="",
     *     operationId="api.PatientQuestionnaire.deleteGroupAssignment",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="questionnaire_id",
     *        in="path",
     *        description="Id of the questionnaire to be unassigned.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *         name="group",
     *         in="path",
     *         description="The group of patients to assign the questionnaire to. Valid groups are in config where section='boxNames'.",
     *         required=true,
     *         type="string"
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="PatientQuestionnaire object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *        response=200,
     *        description="PatientQuestionnaire object that was deleted.",
     *        @SWG\Schema(ref="#/definitions/PatientQuestionnaire"),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Malformed request.",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found.",
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     */
    public function deleteGroupAssignment(request $request, $group)
    {
        $this->getRequestOptions($request);
        $RequestedGroups = array($group); //Originally group was passed in as a json element that was allowed to be an array of groups. This is no longer used but I am tying into the existing code using an array of 1 element for now. This could be simplified later.
        $AvailableGroups = $this->getAvailableGroups();

        $Errors = $this->validateGroupRequest($this->RequestOptions->questionnaire_id, $RequestedGroups);
        if (!is_null($Errors)) {
            return response()->json($Errors, 400);
        }

        $RequestedPatients = array();
        foreach ($RequestedGroups as $RequestedGroup) {
            $Patients = PatientQuestionnaire::whereHas('patient', function ($query) use ($RequestedGroup, $AvailableGroups) {
                if (strToUpper($RequestedGroup) !== 'ALL') {
                    $query->where($AvailableGroups[strToUpper($RequestedGroup)], 'T');
                }
            })
                ->pluck('patient_id')
                ->toArray();

            // Merge unique patient ids
            $RequestedPatients = array_unique(array_merge($Patients, $RequestedPatients));
        }

        $DeletedRows = PatientQuestionnaire::where('questionnaire_id', $this->RequestOptions->questionnaire_id)->whereIn('patient_id', $RequestedPatients)->delete();

        return response()->json($DeletedRows);
    }

    /**
     * Query the config controller for groups setup in the system (config.section=boxNames)
     * @return [Array] A key value array where the key is the group name and the value is the
     * column name in the patient table (eg array['asthma'] = 'box1')
     */
    private function getAvailableGroups()
    {
        $ConfigController = new ConfigController();

        $SearchConfig = new Config();
        $SearchConfig->section = 'boxNames';
        $SearchConfig->app = 'XIS';

        $fakeRequest = Request::create('/v1/config/_search', 'POST', $SearchConfig->toArray());
        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add($SearchConfig->toArray());
        $fakeRequest->setJson($data);

        $result = $ConfigController->searchConfig($fakeRequest);
        
        $Groups = array();
        foreach ($result->getData() as $Group) {
            //value is the user readable group name
            //name is the patient table column name (eg box1)
            $Groups[strToUpper($Group->value)] = $Group->name;
        }
        //Add all patients option
        $Groups['ALL'] = 'ALL';
        return $Groups;
    }

    private function validateGroupRequest($Questionnaire_id, $Groups)
    {
        $AvailableGroups = $this->getAvailableGroups();
        $ValidationErrors = array();
        if (is_array($Groups)) {
            foreach ($Groups as $key => $requestedGroup) {
                if (!array_key_exists(strToUpper($requestedGroup), $AvailableGroups)) {
                    $ValidationErrors['ValidGroup.'.$key] = $requestedGroup . ' is not in the list of available groups. Valid groups are: ' . implode(',', array_keys($AvailableGroups));
                }
            }
        } else {
            $ValidationErrors['Groups'] = 'Groups should be an array of strings.';
        }

        try {
            Questionnaire::where('deleted', 'F')->findOrFail($Questionnaire_id);
        } catch (ModelNotFoundException $e) {
            $ValidationErrors['Exists'] = 'The quesetionnaire_id must exist in the questionnaire table and be marked deleted=F.';
            return $ValidationErrors;
        }

        if (sizeOf($ValidationErrors) > 0) {
            return $ValidationErrors;
        } else {
            return null;
        }
    }

    public static function syncPatientQuestionnaireGroups($patient_id)
    {
        // Now get all of the assignments that are currently assigned to the patient
        // but shouldn't be (questionnaire deleted=T or no longer matches patients boxs)
        PatientQuestionnaire::where('patient_id', $patient_id)
            // assigned questionnaire is deleted
            ->whereHas('questionnaire', function ($Query) {
                $Query->where('deleted', 'T');
            })
            // assigned questionnaire doesn't have at least one box where both patient
            // and questionnaire = 'T' for that box.
            ->orWhere(function ($Query) {
                // match only if its a mass-assignment questionnaire (aka, any boxes checked)
                $Query->whereHas('questionnaire', function ($innerQuery) {
                    $innerQuery->where('box1', 'T')
                        ->orWhere('box2', 'T')
                        ->orWhere('box3', 'T');
                })
                // and if for every box, either the patient, questionnaire, or both are F
                // then then match
                ->where(function ($innerQuery) {
                    $innerQuery->whereHas('questionnaire', function ($innerQuery2) {
                        $innerQuery2->where('box1', 'F');
                    })
                    ->orWhereHas('patient', function ($innerQuery2) {
                        $innerQuery2->where('box1', 'F');
                    });
                })->where(function ($innerQuery) {
                    $innerQuery->whereHas('questionnaire', function ($innerQuery2) {
                        $innerQuery2->where('box2', 'F');
                    })
                    ->orWhereHas('patient', function ($innerQuery2) {
                        $innerQuery2->where('box2', 'F');
                    });
                })->Where(function ($innerQuery) {
                    $innerQuery->whereHas('questionnaire', function ($innerQuery2) {
                        $innerQuery2->where('box3', 'F');
                    })
                    ->orWhereHas('patient', function ($innerQuery2) {
                        $innerQuery2->where('box3', 'F');
                    });
                });
                // Now delete these patient_questionnaire rows.
            })->delete();

        // get the patient in question
        $Patient = Patient::find($patient_id);
        // Get the questionnaires that should be assigned to the patient but currently arent
        $QuestionnairesToAssign = Questionnaire::where('deleted', 'F')
            // each of these double where clauses is an easy way to ensure that we return
            // questionnaires where the patient.boxX and questionnaire.boxX are both T
            ->where(function ($query) use ($Patient) {
                $query->where(function ($innerQuery) use ($Patient) {
                    // where (and and)
                    $innerQuery->where('box1', 'T')->where('box1', $Patient->box1);
                })
                ->orWhere(function ($innerQuery) use ($Patient) {
                    // or where (and and)
                    $innerQuery->where('box2', 'T')->where('box2', $Patient->box2);
                })
                ->orWhere(function ($innerQuery) use ($Patient) {
                    // or where (and and)
                    $innerQuery->where('box3', 'T')->where('box3', $Patient->box3);
                })
                ->orWhere('allPatients', 'T'); // or where
            })
            // at this point we have queried all questionnaires the patient should receive
            // based on group, now we need to remove any from the set that are already
            // assigned per patientQuestionnaire
            ->whereDoesntHave('patientQuestionnaires', function ($query) use ($patient_id) {
                $query->where('patient_id', $patient_id);
            })
            ->get();

        // create the missing patientQuestionnaire assignment rows.
        foreach ($QuestionnairesToAssign as $Questionnaire) {
            $Assignment = new PatientQuestionnaire;
            $Assignment->patient_id = $patient_id;
            $Assignment->questionnaire_id = $Questionnaire->questionnaire_id;
            $Assignment->frequency = $Questionnaire->minimum_frequency;
            $Assignment->recurring = 'T';
            $Assignment->save();
        }

        return;
    }
}
