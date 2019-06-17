<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Carbon\Carbon;
use DB;

class PatientAnswerController extends Controller
{
    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Questionnaire/Answer"},
     *     path="/patient/{patient_id}/answer/{id}",
     *     summary="Returns a single answer in the system identified by {id}.",
     *     description="",
     *     operationId="api.answer.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the answer to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whos answers are being read.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
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
    public function getAnswer(request $request)
    {
        return $this->handleRequest($request, new Answer);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"Questionnaire/Answer"},
    *     path="/patient/{patient_id}/answer",
    *     summary="Create a new answer.",
    *     description="",
    *     operationId="api.answer.createAnswer",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="A single answer object to be created in the system. (The answer_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(
    *            type="array",
    *            @SWG\Items(ref="#/definitions/Answer")
    *        ),
    *     ),
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whos answers are being recorded.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Answer object fields to return",
    *        required=false,
    *        type="string",
    *        collectionFormat="csv"
    *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Answer object that was created.",
    *        @SWG\Schema(ref="#/definitions/Answer")
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
    *     )
    * )
    */
    public function createAnswer(request $request)
    {
        $this->getRequestOptions($request);
        $Now = \DB::select('select now()')[0]->{'now()'};
        $request->merge([
            'patient_id' => $this->RequestOptions->patient_id,
            'date' => $Now
        ]);
        $QuestionId = $request->input('question_id');
        if (!is_null($QuestionId)) {
            $Question = Question::find($QuestionId);
            $request->merge([
                'question' => $Question->text
            ]);
        }
        $request = $this->TEMP_answerFix($request, null);
        return $this->handleRequest($request, new Answer);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"Questionnaire/Answer"},
    *     path="/patient/{patient_id}/multianswer",
    *     summary="Create multiple new answers.",
    *     description="",
    *     operationId="api.answer.createMultianswer",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="Array of Answer objects to be created in the system. (The answer_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(
    *            type="array",
    *            @SWG\Items(ref="#/definitions/Answer")
    *        ),
    *     ),
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whos answers are being recorded.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Answer object fields to return",
    *        required=false,
    *        type="string",
    *        collectionFormat="csv"
    *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Answer object that was created.",
    *        @SWG\Schema(
    *            type="array",
    *            @SWG\Items(ref="#/definitions/Answer")
    *        ),
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
    *     )
    * )
    */
    public function createMultianswer(Request $request)
    {
        $this::getRequestOptions($request);

        $Answer = new Answer; //just an instance to request the validator from
        $Errors = [];
        foreach ($request->all() as $key => $answerObj) {
            if ($key === 'transaction_id') {
                continue;
            }
            $answerObj = $this->TEMP_answerFix(null, $answerObj);
            $answer_id = isset($answerObj['answer_id']) ? $answerObj['answer_id'] : null;
            if (!$Answer->Validate($answerObj, $answer_id)) {
                $Errors [$answerObj['question_id']] = $Answer->errors()->all();
            }
        }

        if (sizeof($Errors) > 0) {
            return response()->json($Errors, 400);
        }

        $Answers = array();

        DB::transaction(function () use ($request, &$Answers) {
            $Now = \DB::select('select now()')[0]->{'now()'};
            foreach ($request->all() as $key => $answerObj) {
                if ($key === 'transaction_id') {
                    continue;
                }
                $answerObj = $this->TEMP_answerFix(null, $answerObj);
                // Find the question that was asked
                $Question = Question::find($answerObj['question_id']);
                // If the answer provided was in the list of the questions bad_answers lock
                $locked = in_array($answerObj['response'], explode(',', $Question->bad_answer));
                if (isset($answerObj['force_lock']) && $answerObj['force_lock'] === 'T') {
                    $locked = 'T';
                }

                if (isset($answerObj['answer_id'])) {
                    //There was an existing answer that we are updating to so find it
                    $Answer = Answer::find($answerObj['answer_id']);
                } else {
                    //This is a new answer
                    $Answer = new Answer;
                }
                $Answer->comment = isset($answerObj['nurse_comment']) ? $answerObj['nurse_comment'] : null;
                $Answer->ask = isset($answerObj['ask']) ? strtoupper($answerObj['ask']) : 'F';
                $Answer->question_id = $answerObj['question_id'];
                $Answer->questionnaire_id = $answerObj['questionnaire_id'];
                $Answer->date = $Now;
                $Answer->patient_id = $this->RequestOptions->patient_id;
                $Answer->locked = $locked ? 'T' : 'F';
                $Answer->question = $Question->text;
                $Answer->response = $answerObj['response'];
                $Answer->save();

                array_push($Answers, $Answer);
            }
        });

        return $this->finishAndFilter($Answers);
    }

    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Questionnaire/Answer"},
     *     path="/patient/{patient_id}/answer/{answer_id}",
     *     summary="Update a single answer object.",
     *     description="",
     *     operationId="api.answer.updateAnswer",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whos answers are being altered.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *         name="answer_id",
    *         in="path",
    *         description="The id of the answer object to update.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
     *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="A single answer object to be updated in the system. ",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Answer")
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
     *        description="Answer object that was deleted.",
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
    public function updateAnswer(request $request)
    {
        $this->getRequestOptions($request);
        // move answer id from the path into the request options since this endpoint
        // always requires it to support multi updates
        $request->merge([
            'answer_id' => $this->RequestOptions->id
        ]);
        return $this->handleRequest($request, new Answer);
    }
    
    /**
     * Update an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Put(
     *     tags={"Questionnaire/Answer"},
     *     path="/patient/{patient_id}/multianswer",
     *     summary="Update an array of answer objects.",
     *     description="",
     *     operationId="api.answer.updateMultianswer",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whos answers are being altered.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="Array of Answer objects to be updated in the system. (The answer_id property must be included in each answer object)",
    *        required=true,
    *        @SWG\Schema(
    *            type="array",
    *            @SWG\Items(ref="#/definitions/Answer")
    *        ),
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
     *        description="Answer object that was deleted.",
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
    public function updateMultianswer(Request $request)
    {
        $this::getRequestOptions($request);

        $Answer = new Answer; //just an instance to request the validator from
        $Errors = [];
        foreach ($request->all() as $key => $answerObj) {
            if ($key === 'transaction_id') {
                continue;
            }
            // an answer_id is absolutly REQUIRED as part of the answerObj in order
            // to bulk update answers however once submited for validation in the lines
            // below, a missing answer_id would be validated as if it was a create
            // rather than update and may appear valid. As a hack to ensure proper
            // validation is preformed, if no answer_id was provided in the object,
            // an answer_id of -1 is submitted so that validation can take place,
            // but we can be sure no actual answer will be found in the database so
            // at minimum, the id will fail validation.
            $answerId = isset($answerObj['answer_id']) ? $answerObj['answer_id'] : -1;
            if (!$Answer->Validate($answerObj, $answerId)) {
                $Errors [$answerId] = $Answer->errors()->all();
            }
        }

        if (sizeof($Errors) > 0) {
            return response()->json($Errors, 400);
        }

        $Answers = array();

        DB::transaction(function () use ($request, &$Answers) {
            foreach ($request->all() as $key => $answerObj) {
                if ($key === 'transaction_id') {
                    continue;
                }
                // Find the original answer
                try {
                    $Answer = Answer::with('question')->where('patient_id', $this->RequestOptions->patient_id)->findOrFail($answerObj['answer_id']);
                } catch (ModelNotFoundException $e) {
                    return response()->json('The requested resource could not be located', 404);
                }
                $Question = $Answer->question;
                unset($Answer->question);

                //Set the ID of the user providing updates to the answer
                $Answer->reviewedBy = $request->user()['user_id'];

                if (isset($answerObj['nurse_comment'])) {
                    $Answer->comment = $answerObj['nurse_comment'];
                }
                if (isset($answerObj['ask'])) {
                    $Answer->ask = strtoupper($answerObj['ask']);
                }

                if (isset($answerObj['response'])) {
                    $Answer->response = $answerObj['response'];
                    // update the locked state based on the new answer
                    $Answer->locked = in_array($answerObj['response'], explode(',', $Question->bad_answer)) ? 'T' : 'F';
                }
                // update lock state last so that if it was intentionally set, that value
                // overrides what was calculated a couple lines ago
                if (isset($answerObj['locked'])) {
                    $Answer->locked = $answerObj['locked'];
                }
                $Answer->save();

                array_push($Answers, $Answer);
            }
        });

        return $this->finishAndFilter($Answers);
    }

    private function TEMP_answerFix($request, $object)
    {
        // this entire function is a workaround until the schema is modified
        // to support null responses
        if (!is_null($request)) {
            // this is for the answer endpoint
            $response = $request->input('response');
            $ask = $request->input('ask');
            if (is_null($response) && !is_null($ask)) {
                $request->merge([
                    'response' => '', //so it wont complain about no default value,
                    'locked' =>'T'
                ]);
            }
            return $request;
        } else {
            //this is for the multi answer endpoint but does the same thing
            //just with an object instead of a request
            if (!isset($object['response']) && isset($object['ask'])) {
                $object['response'] = '';
                $object['force_lock'] = 'T';
            }
            return $object;
        }
    }
}
