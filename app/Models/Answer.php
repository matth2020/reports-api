<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Question;

/**
 * Class Answer.
 *
 *
 * @SWG\Definition(
 *   definition="Answer",
 *   required={"name","app","value"}
 * )
 */

class Answer extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'answer';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'answer_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that are hidden from public view.
     *
     * @var array
     */
    protected $hidden = [];

    /**
    * @SWG\Property(
    *  example="7",
    *  title="answer_id",
    *  description="Id of the answer object from the database",
    *  type="integer",
    *  default="",
    * )
    * @var int
    */
    private $answer_id;

    /**
    * @SWG\Property(
    *  example="4",
    *  title="question_id",
    *  description="Id of the question that the answer applies to",
    *  type="integer",
    *  default="",
    * )
    * @var int
    */
    private $question_id;

    /**
   * @SWG\Property(
   *  example="7",
   *  title="questionnaire_id",
   *  description="Id of the questionnaire that the answer applies to",
   *  type="integer",
   *  default="",
   * )
   * @var int
   */
    private $questionnaire_id;

    /**
    * @SWG\Property(
    *  example="test",
    *  title="response",
    *  description="If question.type = text then answer may be free text. Otherwise answer.type must be one or more values from the answer.type csv. If multiple values sent, they should be in an array.",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $response;

    /**
    * @SWG\Property(
    *  example="Nurse comment",
    *  title="nurse_comment",
    *  description="Nurse comments after reviewing the answer",
    *  @SWG\Schema(
    *    type={"string"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $nurse_comment;

    /**
    * @SWG\Property(
    *  example="T",
    *  title="ask",
    *  description="Allows the patient to indicate that they would like to discuss the question further. (default=F)",
    *  type={"string"},
    *  enum={"F","T"},
    *  default="F",
    * )
    * @var string
    */
    private $ask;

    /**
    * @SWG\Property(
    *  example="T",
    *  title="locked",
    *  description="Indicates if the response provided during answer creation was listed in the questions lockout list.",
    *  type={"string"},
    *  enum={"F","T"},
    *  default="F",
    * )
    * @var string
    */
    private $locked;




    /**
      * Relationships
      */
    public function question()
    {
        return $this->belongsTo('App\Models\Question', 'question_id');
    }
    public function questionnaire()
    {
        return $this->belongsTo('App\Models\questionnaire', 'questionnaire_id');
    }

    /**
     * Accessors
     */
    public function getAnswerIdAttribute($value)
    {
        return (int)$value;
    }

    /**
     * An array of fields that need to be converted from one name
     * in the database (array index) to another in the json object (value).
     */
    public static $DBtoRestConversion = array(
        'reviewedBy' => 'reviewed_by',
        'comment' => 'nurse_comment'
    );

    public $Messages = [
        'response.valid_multi_answer' => 'The :attribute must be one of the following types: :values',
        'response.in' => 'The :attribute must be one of the following types: :values'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'question_id' => array('exists:question,question_id,deleted,F'),
            'questionnaire_id' => array('exists:questionnaire,questionnaire_id,deleted,F'),
            'response' => array('standard','between:0,64'),
            'ask' => array('in:T,t,F,f'),
            'nurse_comment' => array('standard')
        ];

        // add an error message replacer to handle multi-answer validation
        
        \Validator::replacer('validMultiAnswer', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', implode(', ', $parameters), $message);
        });

        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['question_id', 'questionnaire_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        $Validator->sometimes(['response'], 'required_without:ask', function () use ($id) {
            return is_null($id);
        });

        $Validator->sometimes(['answer_id'], 'required', function () use ($id) {
            return $id === -1; //this is the case during multiupdate
        });

        if (is_null($id)) {
            try {
                if (isset($Validator->getData()['question_id'])) {
                    $Question = Question::findOrFail($Validator->getData()['question_id']);
                } else {
                    return $Validator;
                }
            } catch (ModelNotFoundException $e) {
                //If the question_id didn't lead to a question we will already have a validation error
                //based on the question_id rule above so just return the $validator so it can continue
                return $Validator;
            }
        } else {
            try {
                // $Question = Answer::with('question')->findOrFail($id);
                // $Question = $Question->question;
                $Question = Question::whereHas('answers', function ($Query) use ($id) {
                    return $Query->where('answer_id', $id);
                })->first();
            } catch (ModelNotFoundException $e) {
                return $Validator;
            }
        }

        if ($Question['type'] != 'text') {
            $Validator->sometimes(['response'], 'in:'.$Question['type'], function () use ($Question) {
                return ($Question['allow_multiple'] != 'T');
            });

            $Validator->sometimes(['response'], 'validMultiAnswer:'.$Question['type'], function () use ($Question) {
                return ($Question['allow_multiple'] == 'T');
            });
        }

        return $Validator;
    }
}
