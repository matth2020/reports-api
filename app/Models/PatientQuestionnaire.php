<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Log;

/**
 * Class PatientQuestionnaire.
 *
 *
 * @SWG\Definition(
 *   definition="PatientQuestionnaire",
 *   required={"questionnaire_id"}
 * )
 */

class PatientQuestionnaire extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'patient_questionnaire';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = ['patient_id','questionnaire_id'];
    public $incrementing = false;

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="questionnaire_id",
     *  description="Id of the questionnaire to be assigned to the patient.",
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
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="frequency",
     *  description="Number of days after which the patient should take the questionnaire again",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $frequency;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="recurring",
     *  description="Should this questionnaire be administered multiple times.",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $recurring;

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function questionnaire()
    {
        return $this->belongsTo('App\Models\Questionnaire', 'questionnaire_id');
    }

    /**
     * Accessors
     */
    public function getPatientQuestionnaireIdAttribute($value)
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
            'questionnaire_id' => Rule::unique('patient_questionnaire')->where(function ($query) use ($data) {
                return $query->where('questionnaire_id', $data['questionnaire_id'])
                    ->where('patient_id', $data['patient_id']);
            }),
            'recurring' => array('in:t,T,f,F')
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['questionnaire_id','patient_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
