<?php

namespace App\Models;

/**
 * Class Question.
 *
 *
 * @SWG\Definition(
 *   definition="Question",
 *   required={"text","type"}
 * )
 */

class Question extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'question';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'question_id';

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
    protected $hidden = ['BOX1', 'BOX2', 'BOX3', 'code', 'qorder', '`all`', 'goodAns'];

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="question_id",
     *  description="Id of the question from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $question_id;

    /**
     * @SWG\Property(
     *  example="text",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="text",
     *  description="Question text",
     *  minLength=0,
     *  maxLength=255,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $text;

    /**
     * @SWG\Property(
     *  example="note",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="note",
     *  description="Optional nurse note",
     *  minLength=0,
     *  maxLength=255,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $note;

    /**
     * @SWG\Property(
     *  example="1,2,3,4",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="type",
     *  description="Type of response the user can provide. Valid options are 'text' for a text box or a csv list of options (ex 'a,b,c' or 'yes,no') for multiple choice",
     *  minLength=0,
     *  maxLength=64,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $type;

    /**
     * @SWG\Property(
     *  example="1,2",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="bad_answer",
     *  description="A subset (csv if multiple) of answers provided in the 'type' field. Any answer from type that is included in this field will result in questionnaire lockout. For example, if type is '1,2,3,4' and bad_answer is '1,2' then a questionnaire lockout will occur if a patient answers either 1 or 2. (ignored if type=text)",
     *  minLength=0,
     *  maxLength=64,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $bad_answer;

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
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="allow_multiple",
     *  description="Should the user be able to select multiple answer options. (ignored if type='text')",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $allow_multiple;

    /**
     * @SWG\Property(
     *  example={},
     *  pattern="",
     *  title="questionnaires",
     *  description="An array of objects describing questionnaires that the question belongs to.",
     *  type="array",
     *  @SWG\Items(type={"integer","null"})
     * )
     * @var questionnaires   */
    private $questionnaires;

    /**
     * Relationships
     */
    public function answers()
    {
        return $this->hasMany('App\Models\Answer', 'question_id');
    }

    public function questionQuestionnaires()
    {
        return $this->hasMany('App\Models\QuestionnaireQuestion', 'question_id');
    }

    /**
     * Accessors
     */
    public function getQuestionIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'question_questionnaiers' => 'questionnaires'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $data['type'] = isset($data['type']) ? $data['type'] : null;
        $Rules = [
            'text' => array('standard', 'between:0,255', 'unique:question,text,'.$id.',question_id,deleted,F'),
            'type' => array('between:0,64', 'validQuestionType'),
            'bad_answer' => array('between:0,64', 'badAnswers:'.$data['type']),
            'allow_multiple' => array('in:t,T,f,F'),
            'questionnaire' => array('array','inQuestionnaires'),
            'deleted' => array('in:t,T,f,F'),
            'note' => array('standard'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['text','type'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
