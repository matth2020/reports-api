<?php

namespace App\Models;

/**
 * Class Antigen.
 *
 *
 * @SWG\Definition(
 *   definition="Antigen",
 *   required={"name"}
 * )
 */
class Antigen extends BaseModel
{

    protected $fillable = ['antigen_id', 'name', 'compatability_class_id', 'clinic_part_number', 'test_order', 'for_test_only', 'extract_id', 'deleted'];

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'antigen';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'antigen_id';

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
     *  title="antigen_id",
     *  description="Id of the extract from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $antigen_id;

    /**
     * @SWG\Property(
     *  example="Aspergillus Fumigatus",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Extract name",
     *  minLength=0,
     *  maxLength=100,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="compatability_class_id",
     *  description="Id of compatibility class this antigen belongs to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $compatability_class_id;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="clinic_part_number",
     *  description="Part number of the antigen",
     *  minLength=0,
     *  maxLength=32,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $clinic_part_number;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="for test only",
     *  description="Used only for skin testing (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $for_test_only;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="test_order",
     *  description="Antigen order when displayed as part of a test",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $test_order;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="extract_id",
     *  description="Extract this antigen is based on",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $extract_id;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Extract deleted (t/f)",
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
     * Relationships
     */
    public function extract()
    {
        return $this->belongsTo('App\Models\Extract', 'extract_id', 'extract_id');
    }
    public function compatibilityClass()
    {
        return $this->belongsTo('App\Models\CompatibilityClass', 'compatibility_class_id');
    }

    /**
     * Accessors
     */
    public function getForTestOnlyAttribute($value)
    {
        if ($value === '' || is_null($value)) {
            return 'F';
        } else {
            return $value;
        }
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
            'test_order' => array('integer', 'min:1'),
            'extract_id' => 'exists:extract,extract_id',
            'compatibility_class_id' => 'exists:compatibility_class,compatibility_class_id',
            'name' => array('standard', 'between:0,100'),
            'clinic_part_number' => array('standard', 'between:0,32'),
            'for_test_only' => array('standard', 'between:0,45')
        ];

        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['test_order'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
