<?php

namespace App\Models;

/**
 * Class QuestionnaireQuestion.
 */

class QuestionnaireQuestion extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'questionnaire_question';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = ['questionnaire_id','question_id'];
    public $incrementing = false;

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Relationships
     */
    public function questionnaire()
    {
        return $this->hasOne('App\Models\Questionnaire', 'questionnaire_id', 'questionnaire_id');
    }
    public function question()
    {
        return $this->hasOne('App\Models\Question', 'question_id', 'question_id');
    }

    /**
     * Accessors
     */
    public function getQuestionnaireQuestionIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes([], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
