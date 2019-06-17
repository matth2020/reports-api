<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\QuestionnaireAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use App\Models\Answer;
use DB;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire/Answer"},
     *     path="/answer/_search",
     *     summary="Returns a list answers in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.answer.searchAnswer",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="answer object",
     *        in="body",
     *        description="Answer object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Answer"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Answer object fields to return.",
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
    public function searchAnswer(request $request)
    {
        return $this->handleRequest($request, new Answer);
    }
    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Questionnaire/Answer"},
     *     path="/patient/{patient_id}/answer/lastten",
     *     summary="returns last 10 answers for the patient. Temp endpoint, do not use",
     *     description="",
     *     operationId="api.answer.getlast10.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="patient_id",
     *        in="path",
     *        description="Id of the patient to return answers for.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Answer object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Answer object.",
     *         @SWG\Schema(ref="#/definitions/Answer"),
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
    public function getlast10(request $request)
    {
        $this::getRequestOptions($request, new Answer);
        $Objects = Answer::where('patient_id', $this->RequestOptions->patient_id)->orderBy('date', 'desc')->take(10)->get();
        return response()->json($Objects);
    }

    protected function queryWith($Query)
    {
        return $Query->with('question');
    }
}
