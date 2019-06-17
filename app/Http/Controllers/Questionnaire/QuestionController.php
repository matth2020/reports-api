<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\QuestionnaireQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use DB;

class QuestionController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Questionnaire/Question"},
    *     path="/question",
    *     summary="Returns a list of all questions in the system.",
    *     description="",
    *     operationId="api.question.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Question object fields to return.",
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
        return $this->handleRequest($request, new Question);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Questionnaire/Question"},
     *     path="/question/{id}",
     *     summary="Returns a single question in the system identified by {id}.",
     *     description="",
     *     operationId="api.question.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the question to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Question object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Question object.",
     *         @SWG\Schema(ref="#/definitions/Question"),
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
    public function getQuestion(request $request)
    {
        return $this->handleRequest($request, new Question);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire/Question"},
     *     path="/question/_search",
     *     summary="Returns a list questions in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.question.searchQuestion",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="question object",
     *        in="body",
     *        description="Question object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Question"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Question object fields to return.",
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
    public function searchQuestion(request $request)
    {
        return $this->handleRequest($request, new Question);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Questionnaire/Question"},
     *     path="/question",
     *     summary="Create a new question.",
     *     description="",
     *     operationId="api.question.createQuestion",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="Question object",
     *        in="body",
     *        description="Question object to be created in the system. (The question_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Question"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Question object fields to return.",
     *        required=false,
     *        type="array",
     *        @SWG\Items(type="string"),
     *        collectionFormat="csv",
     *     ),
     *     @SWG\Response(
     *        response=200,
     *        description="Question object that was created.",
     *        @SWG\Schema(ref="#/definitions/Question"),
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
    public function createQuestion(Request $request)
    {
        $this->getRequestOptions($request);
        $this->RequestOptions->questionnaires = !is_null($request->input('questionnaires')) ? $request->input('questionnaires') : array();
        return $this->validateAndSave($request, new Question);
    }

    /**
     * Delete an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Delete(
     *     tags={"Questionnaire/Question"},
     *     path="/question/{id}",
     *     summary="Mark a question as deleted.",
     *     description="",
     *     operationId="api.question.deleteQuestion",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the question to mark deleted.",
     *        required=true,
     *        type="integer",
     *      ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Question object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *        response=200,
     *        description="Question object that was deleted.",
     *        @SWG\Schema(ref="#/definitions/Question"),
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
    public function deleteQuestion(request $request)
    {
        $result = $this->handleRequest($request, new Question);
        // we also need to remove the questions questionnaire assignments
        QuestionnaireQuestion::where('question_id', $this->RequestOptions->id)->delete();

        return $result;
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Questionnaire/Question"},
     *     path="/question/{id}",
     *     summary="Update a question object.",
     *     description="",
     *     operationId="api.question.updateQuestion",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the question to update.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="question object",
     *        in="body",
     *        description="Question object containing only the fields that need to be updated. (The question_id property cannot be updated and will be ignored)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Question"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Question object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *        description="Question object that was deleted.",
     *         @SWG\Schema(ref="#/definitions/Question"),
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
    public function updateQuestion(Request $request)
    {
        $this::getRequestOptions($request);
        $this->RequestOptions->questionnaires = isset($request['questionnaires']) ? $request['questionnaires'] : array();

        try {
            $Question = question::with('answers', 'questionQuestionnaires')
                ->findOrFail($this->RequestOptions->question_id);
        } catch (ModelNotFoundException $e) {
            return response('Resource could not be located.', 404);
        }

        //see if question has any answers linked to it. If it does, we cant
        //actually update it or those answers will appear to be answers to
        //a different question than they actually were so if that is the case
        //we need to mark the original question as deleted and then create a
        //new question with the same data as the first including the new updates
        $AnswersCount = $Question->answers->count();
        if ($AnswersCount > 0) {
            //delete the question and get it back as an object
            $fakeRequest = Request::create('/v1/Question/'.$this->RequestOptions->id, 'DELETE');
            $Question = $this->deleteQuestion($fakeRequest)->getData();
            //now apply the properties of the question object to the request
            //and provide that to createQuestion. Note that currently question
            //is an exact duplicate of the deleted object so to create a new
            //question we need to unset the question_id and deleted
            $Question = $this::APItoDB($request, Question::find($Question->question_id));
            unset($Question->question_id);
            unset($Question->deleted);
            $fakeRequest = Request::create('/v1/Question', 'POST', $Question->toArray());
            $data = new \Symfony\Component\HttpFoundation\ParameterBag;
            $data->add((array)$Question);
            $fakeRequest->setJson($data);
            $CreateResponse = $this->createQuestion($fakeRequest);


            //Next, we need to link the new question_id to any questionnaires
            //that were using the old ID. We will leave the old id linked as
            //well because if the user views an old version of the
            //questionnaire, they should still see the old question. But new
            //versions should show the new question
            $QuestionID = $this->castObject($CreateResponse->getData(), 'App\Models\Question')->question_id;

            $Questionnaires = Questionnaire::whereHas('questionnaireQuestions', function ($Query) use ($QuestionID) {
                $Query->where('question_id', $QuestionID);
            });
            if ($Questionnaires->count() > 0) {
                //questionnaires were found so create new links
                foreach ($Questionnaires as $Questionnaire) {
                    $QuestionnaireQuestion = new QuestionnaireQuestion();
                    $QuestionnaireQuestion->questionnaire_id = $Questionnaire;
                    $QuestionnaireQuestion->question_id = $QuestionID;
                    $QuestionnaireQuestion->save();
                }
            }

            return $CreateResponse;
        } else {
            // if there the request specifies a list of questionnaires,
            // get rid of the old ones
            if (isset($request['questionnaires'])) {
                // need to delete all of the link questions from the questionnaire
                // so that when we save the new one we can link the new and
                // possibly different set.
                QuestionnaireQuestion::where('question_id', $Question->question_id)
                    ->delete();
            }
            return $this->validateAndSave($request, $Question);
        }
    }

    protected function queryWith($Query)
    {
        return $Query->with('questionQuestionnaires');
    }

    protected function queryModifier($Query)
    {
        return $Query->where('deleted', 'F');
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        //Do the following steps so the questionnaire and questions get saved in a transaction
        DB::transaction(function () use ($Object) {
            $Object->save();
            //link to the of provided questionnaires
            foreach ($this->RequestOptions->questionnaires as $QuestionnaireId) {
                $QuestionnaireQuestion = new QuestionnaireQuestion();
                $QuestionnaireQuestion->question_id = $Object->question_id;
                $QuestionnaireQuestion->questionnaire_id = $QuestionnaireId;
                $QuestionnaireQuestion->save();
            }
        });

        //If we are saving something with a null id it must have been a create
        //so use the objects primary key to find the created id.
        $primaryId = is_null($this->RequestOptions->id) ? $Object[$Object->getKeyName()] : $this->RequestOptions->id;

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($primaryId);
        return $this->finishAndFilter($newObject);
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  Question $Question object returned from the database
     * @param  request $request API request used to build filter.
     * @return Question object
     */
    protected function finalize($Question)
    {
        $Question = $this->cleanQuestionnaires($Question);
        return $Question;
    }

    /**
     * Convert questionQuestionnaire data into an array of questionnaire ids
     * @param  Question $Question Question object to manipulate
     * @return Question           Question object with questionQuestionnaires
     *                                     removed and questionnaires added
     */
    private function cleanQuestionnaires(Question $Question)
    {
        $Questionnaires = $Question->questionQuestionnaires;
        unset($Question->questionQuestionnaires);
        $QuestionnaireArray = array();
        foreach ($Questionnaires as $Questionnaire) {
            array_push($QuestionnaireArray, $Questionnaire->questionnaire_id);
        }
        if (sizeof($QuestionnaireArray) > 0) {
            $Question->questionnaires = $QuestionnaireArray;
        }

        return $Question;
    }
}
