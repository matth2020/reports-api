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

class QuestionnaireController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Questionnaire"},
    *     path="/questionnaire",
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
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Questionnaire"},
     *     path="/questionnaire/{id}",
     *     summary="Returns a single questionnaire in the system identified by {id}.",
     *     description="",
     *     operationId="api.questionnaire.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the questionnaire to return.",
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
    public function getQuestionnaire(request $request)
    {
        return $this->handleRequest($request, new Questionnaire);
    }

    /**
     * Display a listing of the resource matching search criteron.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire"},
     *     path="/questionnaire/_search",
     *     summary="Returns a list questionnaires in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.questionnaire.searchQuestionnaire",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
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

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire"},
     *     path="/questionnaire",
     *     summary="Create a new questionnaire.",
     *     description="",
     *     operationId="api.questionnaire.createQuestionnaire",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Questionnaire object to be created in the system. (The questionnaire_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Questionnaire"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Questionnaire object fields to return.",
     *        required=false,
     *        type="array",
     *        @SWG\Items(type="string"),
     *        collectionFormat="csv",
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
    public function createQuestionnaire(Request $request)
    {
        $this::getRequestOptions($request);

        $Errors = $this->validateQuestionnaire($request);
        if ($Errors) {
            return response()->json($Errors, 400);
        }

        $SavedQuestionnaire = $this->saveQuestionnaire($request);

        $Questionnaire = Questionnaire::with('questionnaireQuestions')
            ->find($SavedQuestionnaire->questionnaire_id);

        return $this->finishAndFilter($Questionnaire);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Questionnaire"},
     *     path="/questionnaire/{id}",
     *     summary="Mark a questionnaire as deleted.",
     *     description="",
     *     operationId="api.questionnaire.deleteQuestionnaire",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the questionnaire to mark deleted.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Questionnaire object fields to return.",
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
    public function deleteQuestionnaire(request $request)
    {
        $this::getRequestOptions($request);

        try {
            $Questionnaire = Questionnaire::with('questionnaireQuestions')
                ->findOrFail($this->RequestOptions->id);
        } catch (ModelNotFoundException $e) {
            return response('Requested questionnaire does not exist.', 404);
        }
        //mark questionnaire deleted
        $Questionnaire->deleted = 'T';
        $Questionnaire->save();
        return $this->finishAndFilter($Questionnaire);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Questionnaire"},
     *     path="/questionnaire/{id}",
     *     summary="Update a questionnaire object.",
     *     description="",
     *     operationId="api.questionnaire.updateQuestionnaire",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the questionnaire to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="questionnaire object",
     *        in="body",
     *        description="Questionnaire object containing only the fields that need to be updated. (The questionnaire_id property cannot be updated and will be ignored)",
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
    public function updateQuestionnaire(Request $request)
    {
        $this::getRequestOptions($request);

        $Errors = $this->validateQuestionnaire($request);
        if ($Errors) {
            return response()->json($Errors, 400);
        }

        try {
            $Questionnaire = questionnaire::with(['answers', 'patientQuestionnaires'])
                ->findOrFail($this->RequestOptions->id);
        } catch (ModelNotFoundException $e) {
            return response('Requested question does not exist.', 404);
        }

        // see if questionnaire has any answers linked to it via questions.
        // If it does, we cant actually update it or it wont be possible to
        // display the correct questionnaire when those answers are viewed.
        // If that is the case we need to mark the original questionnaire as
        // deleted and then create a new questionnaire with the same data as
        // the first including the new updates

        $AnswersCount = $Questionnaire
            ->answers()
            ->count();

        if ($AnswersCount > 0) {
            //delete the questionnaire and get it back as an object
            $DeletedQuestionnaire = $this->deleteQuestionnaire($request, $this->RequestOptions->id)->getData();
            //now apply the properties of the questionnaire object to the request
            //and provide that to createQuestionnaire. Note that currently
            //questionnaire is an exact duplicate of the deleted object so to
            // create a new questionnaire we need to unset the questionnaire_id
            // and deleted
            unset($DeletedQuestionnaire->questionnaire_id);
            unset($DeletedQuestionnaire->deleted);
            $this->updateRequestFromObject($request, $DeletedQuestionnaire);
            //now create the updated questionnaire and save the response
            $SavedQuestionnaire = $this->createQuestionnaire($request)->getData();

            //Next, we need to link the new questionnaire_id to any patients
            //that were using the old ID. We will leave the old id linked as
            //well because if the user views an old version of the
            //questionnaire, the appropriate data but new versions should show
            // the new questionnaire

            $QuestionnaireID = $SavedQuestionnaire->questionnaire_id;

            $Patients = Questionnaire::with(['patientQuestionnaires'])->find($this->RequestOptions->id)->patientQuestionnaires;

            if ($Patients->count() > 0) {
                //patients were found so create new links
                foreach ($Patients as $Patient) {
                    $PatientQuestionnaire = new PatientQuestionnaire();
                    $PatientQuestionnaire->questionnaire_id = $QuestionnaireID;
                    $PatientQuestionnaire->patient_id = $Patient->patient_id;
                    $PatientQuestionnaire->recurring = 'T';
                    $PatientQuestionnaire->save();
                }
            }
        } else {
            // need to deleted all of the link questions from the questionnaire
            // so that when we save the new one we can link the new and
            // possibly different set.
            $Questionnaire->questionnaireQuestions()->delete();

            $SavedQuestionnaire = $this->saveQuestionnaire($request, $Questionnaire);
        }
        $Questionnaire = Questionnaire::with('questionnaireQuestions')
            ->find($SavedQuestionnaire->questionnaire_id);

        return $this->finishAndFilter($Questionnaire);
    }

    protected function queryWith($Query)
    {
        return $Query->with('questionnaireQuestions');
    }

    protected function queryModifier($Query)
    {
        return $Query->where('deleted', 'F');
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
        $Questionnaire = $this->cleanQuestions($Questionnaire);
        return $Questionnaire;
    }

    /**
     * Convert questionQuestion data into an array of question ids
     * @param  Questionnaire $Questionnaire Questionnaire object to manipulate
     * @return Questionnaire           Questionnaire object with questionnaireQuestions
     *                                 removed and questions array added
     */
    private function cleanQuestions(Questionnaire $Questionnaire)
    {
        $Questions = $Questionnaire->questionnaireQuestions;
        unset($Questionnaire->questionnaireQuestions);
        $QuestionArray = array();
        foreach ($Questions as $Question) {
            array_push($QuestionArray, $Question->question_id);
        }
        $Questionnaire->questions = $QuestionArray;
        return $Questionnaire;
    }

    /**
     * Save questionnaire to the database
     * @param  request        $request       API request
     * @param  Questionnaire  $Questionnaire Questionnaire object to be saved
     * @return Questionnaire object that was saved.
     */
    private function saveQuestionnaire(request $request, Questionnaire $Questionnaire = null)
    {
        $Questionnaire = is_null($Questionnaire) ? new Questionnaire() : $Questionnaire;
        //Update the provided questionnaire object with all of the appropriate
        //values from the request
        $Questionnaire = $this->APItoDB($request, $Questionnaire);
        //have to save the questionnaire before the questions or there is no
        //questionnaire_id to link to questions
        $Questionnaire->save();

        //get questions from the request
        $Questions = !isset($request['questions']) ? [] : $request->json('questions');

        //Link the new questionnaire to its questions
        foreach ($Questions as $Question) {
            $QuestionnaireQuestion = new QuestionnaireQuestion();
            $QuestionnaireQuestion->question_id = $Question;
            $QuestionnaireQuestion->questionnaire_id = $Questionnaire->questionnaire_id;
            $QuestionnaireQuestion->save();
        }

        return $Questionnaire;
    }

    /**
     * Validate questionnaire before saving
     * @param  request $request API request
     * @param  int     $id      Id of the questionnaire to save (null for create)
     * @return null
     */
    public function validateQuestionnaire(request $request)
    {
        $create = $this->RequestOptions->isCreate;
        $Rules = [
            'name' => array('standard', 'between:0,32', 'unique:questionnaire,name,'.$this->RequestOptions->id.',questionnaire_id,deleted,F'),
            'minimum_frequency' => array('integer'),
            'deleted' => array('in:t,T,f,F'),
            'questions' => array('array'),
        ];

        //custom messages for validation errors
        $Messages = [
            'name.unique' => 'The :attribute field must be unique in the questionnaire table where deleted=F.',
            'between' => 'The :attribute field must be between 0-32 characters in length.',
            'in' => 'The :attribute field must be T or F.',
        ];

        //do validation
        $Validation = \Validator::make($request->all(), $Rules, $Messages);

        $Validation->sometimes(['name'], 'required', function () use ($create) {
            return $create;
        });

        $Errors = $Validation->errors();

        $Questions = $request->input('questions');

        if ($Questions && count($Questions) > 0) {
            $ReturnedErrors = array();
            foreach ($Questions as $QuestionID) {
                try {
                    Question::where('deleted', 'F')->findOrFail($QuestionID);
                } catch (ModelNotFoundException $e) {
                    array_push($ReturnedErrors, 'Question_id ' . $QuestionID . ' either does not exist or was previously marked deleted="T"');
                }
            }
            $QuestionErrors['question'] = $ReturnedErrors;

            $Errors->merge($QuestionErrors);
        }

        //if validation errors were detected, return them
        if (count($Errors) > 0) {
            return $Errors;
        }

        return false;
    }
}
