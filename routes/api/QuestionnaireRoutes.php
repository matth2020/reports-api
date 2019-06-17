<?php

/*
|--------------------------------------------------------------------------
| Questionnaire Routes
|--------------------------------------------------------------------------
|
*/

$middleware = array();
$Config = app()->make('config');
if ($Config->get('app.api_log_enabled') != false) {
    array_push($middleware, 'api-log');
}

$middlewareWithAuth = $middleware;
if ($Config->get('app.oauth_enabled') != false) {
    array_push($middlewareWithAuth, 'auth:api');
}

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middleware, //No auth required
    'namespace' => 'App\Http\Controllers\Questionnaire'
    ],
    function () use ($router) {
        // Answer
        $router->post('patient/{patient_id}/answer', 'PatientAnswerController@createAnswer');
        $router->post('patient/{patient_id}/multianswer', 'PatientAnswerController@createMultianswer');
        // QuestionnaireDue
        $router->get('patient/{patient_id}/questionnaire_due', 'QuestionnaireDueController@index');
    }
);

$router->group(
    [
    'prefix' => 'v1',
    'middleware' => $middlewareWithAuth, //Auth required
    'namespace' => 'App\Http\Controllers\Questionnaire'
    ],
    function () use ($router) {
        // answer
        $router->post('answer/_search', 'AnswerController@searchAnswer')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/answer/lastten', 'AnswerController@getLast10')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/answer/{id}', 'PatientAnswerController@getAnswer')->middleware('privilege:patient_read');
        $router->put('patient/{patient_id}/answer/{answer_id}', 'PatientAnswerController@updateAnswer')->middleware('privilege:patient_update');
        $router->put('patient/{patient_id}/multianswer', 'PatientAnswerController@updateMultianswer')->middleware('privilege:patient_update');

        // questionnaire
        $router->get('questionnaire', 'QuestionnaireController@index');
        $router->get('questionnaire/{id}', 'QuestionnaireController@getQuestionnaire');
        $router->put('questionnaire/{id}', 'QuestionnaireController@updateQuestionnaire')->middleware('privilege:config_update');
        $router->delete('questionnaire/{id}', 'QuestionnaireController@deleteQuestionnaire')->middleware('privilege:config_delete');
        $router->post('questionnaire', 'QuestionnaireController@createQuestionnaire')->middleware('privilege:config_create');
        $router->post('questionnaire/_search', 'QuestionnaireController@searchQuestionnaire');

        // PatientQuestionnaire
        $router->post('group/{group}/questionnaire/{questionnaire_id}/assignment', 'PatientQuestionnaireController@createGroupAssignment')->middleware('privilege:config_update');
        $router->delete('group/{group}/questionnaire/{questionnaire_id}/assignment', 'PatientQuestionnaireController@deleteGroupAssignment')->middleware('privilege:config_update');
        $router->post('patient/{patient_id}/questionnaire/{questionnaire_id}/assignment', 'PatientQuestionnaireController@createAssignment')->middleware('privilege:patient_update');
        $router->delete('patient/{patient_id}/questionnaire/{questionnaire_id}/assignment', 'PatientQuestionnaireController@deleteAssignment')->middleware('privilege:patient_update');
        $router->get('patient/{patient_id}/questionnaire/assigned', 'PatientQuestionnaireController@index')->middleware('privilege:patient_read');
        $router->get('patient/{patient_id}/questionnaire/answered', 'AnsweredPatientQuestionnaireController@index')->middleware('privilege:patient_read');
        $router->post('patient/{patient_id}/questionnaire/answered', 'AnsweredPatientQuestionnaireController@searchQuestionnaire')->middleware('privilege:patient_read');
        
        // question
        $router->get('question', 'QuestionController@index');
        $router->get('question/{id}', 'QuestionController@getQuestion');
        $router->put('question/{id}', 'QuestionController@updateQuestion')->middleware('privilege:config_update');
        $router->delete('question/{id}', 'QuestionController@deleteQuestion')->middleware('privilege:config_delete');
        $router->post('question', 'QuestionController@createQuestion')->middleware('privilege:config_create');
        $router->post('question/_search', 'QuestionController@searchQuestion');
    }
);
