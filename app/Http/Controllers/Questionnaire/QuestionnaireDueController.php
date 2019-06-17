<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Questionnaire\PatientQuestionnaireController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\PatientQuestionnaire;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Config;
use Carbon\Carbon;
use Log;

class QuestionnaireDueController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Questionnaire/Due"},
    *     path="/patient/{patient_id}/questionnaire_due",
    *     summary="Returns a list of all questionnaires due for the selected patient.",
    *     description="This endpoint returns questions (typically batched as a full questionnaire but individual questions must meet criterion) that the selected patient is due to answer. Questions that are due are questions that have either never been answered or have been answered greater than patient_questionnaire.frequency days ago, or on todays date. If a question has been answered on todays date, that answer is returned with the question as well to allow patients to retake a questionnaire (on the same date) and potentially change answers.",
    *     operationId="api.questionnairedue.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whos due questionnaires to view.",
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
        $this->getRequestOptions($request, new PatientQuestionnaire);

        // First we need to compare the patients current box123 settings against all of the
        // current box123 and all settings in the system and update the patients
        // questionQuestionnaire rows as necessary.
        try {
            Patient::findOrFail($this->RequestOptions->patient_id);
        } catch (ModelNotFoundException $e) {
            return response()->json('The requested resource could not be located', 404);
        }

        // Ensure patients questionnaire assignments are accurate
        PatientQuestionnaireController::syncPatientQuestionnaireGroups($this->RequestOptions->patient_id);

        // Now that we have confirmed the patientQuestionnaire assignments are correct and
        // current, we can select the questionnaires due based on that.
        $Questionnaires = PatientQuestionnaire::
            // along with our selection, return only those answers that match todays date
            with(['questionnaire.questionnaireQuestions.question.answers' => function ($query) {
                $query->whereRaw('date(date) = date(now())')->where('patient_id', $this->RequestOptions->patient_id);
            }])
            // we want the patientQuestionnaire rows that match the patient_id in question
            // and that have answers where the date was today (edit a question answered today)
            // or the date of the answer is greater than `frequency` days ago. (questions with
            // no answers are selected separately in the next section)
            ->where(function ($query) {
                $query->where('patient_id', $this->RequestOptions->patient_id)
                    ->whereHas('questionnaire', function ($innerQuery) {
                        $innerQuery->where('questionnaire.deleted', 'F');
                    })
                    ->whereHas('questionnaire.questionnaireQuestions.question.answers', function ($query1) {
                        $query1
                        ->where(function ($query2) {
                            $query2->whereRaw('date(date) = date(now())')
                                ->orWhereRaw('date(now()) > date(date_add(date, interval frequency day))');
                        });
                    });
            })
            // This where clause selects any questions applying to the patient that have never
            // been answered
            ->orWhere(function ($query) {
                $query->where('patient_id', $this->RequestOptions->patient_id)
                ->doesntHave('questionnaire.questionnaireQuestions.question.answers');
            })
            ->get();
        return $this->finishAndFilter($Questionnaires);
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  Questionnaire $Questionnaire object returned from the database
     * @param  request $request API request used to build filter.
     * @return Questionnaire object
     */
    protected function finalize($PatientQuestionnaire)
    {
        //Move questions from within patient_questionnaire to the outer questionnaire (structure)
        //for each questionnaire, if there are one or more answers within the required
        //patient_questionnaire frequency, then remove the questionnaire from the list (not due)
        $Questionnaire = $PatientQuestionnaire->questionnaire;
        $Questions = array();
        foreach ($Questionnaire->questionnaireQuestions as $Question) {
            $Question->text = $Question->question->text;
            $Question->type = $Question->question->type;
            $Question->allow_multiple = $Question->question->allow_multiple;
            $Question->answer = sizeOf($Question->question->answers) === 0 ? null : $Question->question->answers[0];
            unset($Question->question);
            array_push($Questions, $Question);
        }
        $Questionnaire->questions = $Questions;
        unset($Questionnaire->questionnaireQuestions);

        return $Questionnaire;
    }
}
