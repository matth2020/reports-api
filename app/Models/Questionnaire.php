<?php

namespace App\Models;

/**
 * Class Questionnaire.
 *
 * @package App
 *
 * @SWG\Definition(
 *   definition="Questionnaire",
 *   required={"name"},
 * )
 */

class Questionnaire extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'questionnaire';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'questionnaire_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    protected $guarded = ['questionnaire_id'];

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="questionnaire_id",
     *  description="Id of the questionnaire from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $questionnaire_id;

    /**
     * @SWG\Property(
     *  example="sizes",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Name of the questionnaire",
     *  minLength=0,
     *  maxLength=32,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="30",
     *  pattern="^[0-9]+$",
     *  title="minimum_frequency",
     *  description="Number of days after which patients assigned this questionnaire must retake it.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $minimum_frequency;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Is this question still available for use?",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $deleted;

    /**
     * @SWG\Property(
     *  example={},
     *  pattern="",
     *  title="questions",
     *  description="An array of objects describing question that belong to the questionnaire",
     *  type="array",
     *  @SWG\Items(type={"integer","null"})
     * )
     * @var questions   */
    private $questions;

    /**
     * Relationships
     */
    public function patientQuestionnaires()
    {
        return $this->hasMany('App\Models\PatientQuestionnaire', 'questionnaire_id');
    }
    public function questionnaireQuestions()
    {
        return $this->hasMany('App\Models\QuestionnaireQuestion', 'questionnaire_id');
    }
    public function questions()
    {
        return $this->hasManyThrough('App\Models\Question', 'App\Models\QuestionnaireQuestion', 'question_id', 'questionnaire_id', 'question_id', 'questionnaire_id');
    }

    public function answers()
    {
        return $this->hasMany('App\Models\Answer', 'questionnaire_id');
    }

    /**
     * Accessors
     */
    public function getQuestionnaireIdAttribute($value)
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
            'name' => array('standard', 'between:0,32', 'unique:questionnaire,name,'.$id.',questionnaire_id,deleted,F'),
            'minimum_frequency' => array('integer'),
            'deleted' => array('in:t,T,f,F'),
            'questions' => array('array'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
