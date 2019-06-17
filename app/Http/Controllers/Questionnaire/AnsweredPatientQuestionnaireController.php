<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\QuestionnaireQuestion;
use App\Http\Controllers\Controller;
use App\Models\PatientQuestionnaire;
use Illuminate\Validation\Validator;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;

class AnsweredPatientQuestionnaireController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Questionnaire"},
    *     path="/patient/{patient_id}/questionnaire/answered",
    *     summary="Returns a list of all questionnaires in the system answered by a given patient.",
    *     description="",
    *     operationId="api.answeredPatientQuestionnaire.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
     *        name="patient_id",
     *        in="path",
     *        description="Id of the patient who's questionnaires should be returned.",
     *        required=true,
     *        type="integer",
     *     ),
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
        return $this->handleRequest($request, new Questionnaire);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire"},
     *     path="/patient/{patient_id}/questionnaire/answered/_search",
     *     summary="Returns a list questionnaires in the system matching the requested fields that have been answered by a given patient.",
     *     description="% may be used as a wild card.",
     *     operationId="api.answeredPatientQuestionnaire.searchQuestionnaire",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="patient_id",
     *        in="path",
     *        description="Id of the patient who's questionnaires should be returned.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="questionnaire object",
     *        in="body",
     *        description="Questionnaire object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Questionnaire"),
     *     ),
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
    public function searchQuestionnaire(request $request)
    {
        return $this->handleRequest($request, new Questionnaire);
    }

    protected function queryWith($Query)
    {
        return $Query->with(['questionnaireQuestions.question' => function ($innerQuery) {
            return $innerQuery->with(['answers' => function ($innerQuery2) {
                return $innerQuery2->where('patient_id', $this->RequestOptions->patient_id);
            }]);
        }
    ]);
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  Questionnaire $Questionnaire object returned from the database
     * @param  request $request API request used to build filter.
     * @return Questionnaire object
     */
    protected function finalize($Questionnaire)
    {
        return $Questionnaire;
    }
}
