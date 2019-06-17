<?php

namespace App\Models;

/**
 * Class ClassIncompatibility.
 *
 *
 * @SWG\Definition(
 *   definition="ClassIncompatibility",
 *   required={"name"}
 * )
 */
class ClassIncompatibility extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'class_incompatibility';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'class_id_1';

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
     *  description="Id of a class in the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $class_id_1;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="antigen_id",
     *  description="Id an incompatible class in the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $class_id_2;

    /**
     * Relationships
     */
    public function compatibilityClass2()
    {
        return $this->belongsTo('App\Models\CompatibilityClass', 'class_id_2', 'compatibility_class_id');
    }
    public function compatibilityClass1()
    {
        return $this->belongsTo('App\Models\CompatibilityClass', 'class_id_1', 'compatibility_class_id');
    }
    public function compatibilityClass()
    {
        $this->compatibilityClass = $this->compatibilityClass1->merge($this->compatibilityClass2);
        unset($this->compatibilityClass1);
        unset($this->compatibilityClass2);
        return $this;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
       // 'class_id_2' => 'compatibility_class_id'
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
