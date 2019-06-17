<?php

namespace App\Models;

/**
 * Class Code.
 *
 *
 * @SWG\Definition(
 * )
 */
class Code extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'flags';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'flag_id';

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
     *  title="code_id",
     *  description="Id of the code from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $code_id;

    /**
     * @SWG\Property(
     *  example="Asthmatic patient",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="text",
     *  description="Full text description of the code",
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
     *  example="Asthma",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="code",
     *  description="The abbreviated code value",
     *  minLength=0,
     *  maxLength=16,
     *  type={"string","null"},
     *  default="active",
     * )
     *
     * @var string
     */
    private $code;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Status of the code entry in the system",
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
     *  title="adminReq",
     *  description="No idea what this is",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $adminReq;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="showMsg",
     *  description="No idea what this is",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $showMsg;

    public function Codes()
    {
        return $this->hasManyThrough('App\Models\PatientCode', 'App\Models\Code', 'code_id', 'patient_id', 'code_id', 'patient_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Models\CodeUse', 'flag_id');
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'flag_id' => 'code_id',
        'adminReq' => 'admin_required',
        'showMsg' => 'show_message',
        'text' => 'description'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'description' => array('standard', 'between:0,255'),
            'code' => array('standard', 'between:0,45'),
            'deleted' => array('in:t,T,f,F'),
            'show_message' => array('in:t,T,f,F'),
            'admin_required' => array('in:t,T,f,F'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['description', 'code'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
