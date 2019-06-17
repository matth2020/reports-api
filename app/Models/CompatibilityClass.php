<?php

namespace App\Models;

/**
 * Class CompatibilityClass.
 *
 *
 * @SWG\Definition(
 *   definition="CompatibilityClass",
 *   required={"name"}
 * )
 */
class CompatibilityClass extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'compatibility_class';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'compatibility_class_id';

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
    private $compatibility_class_id;

    /**
     * @SWG\Property(
     *  example="Grasses",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="A text name of the class",
     *  minLength=0,
     *  maxLength=64,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * Relationships
     */
    public function incompatibleClasses2()
    {
        return $this->hasManyThrough(
            'App\Models\CompatibilityClass',
            'App\Models\ClassIncompatibility',
            'class_id_2',
            'compatibility_class_id',
            'compatibility_class_id',
            'class_id_1'
        );
    }
    public function incompatibleClasses1()
    {
        return $this->hasManyThrough('App\Models\CompatibilityClass', 'App\Models\ClassIncompatibility', 'class_id_1', 'compatibility_class_id', 'compatibility_class_id', 'class_id_2');
    }
    public function incompatibleClasses()
    {
        $this->incompatibleClasses = $this->incompatibleClasses1->merge($this->incompatibleClasses2);
        unset($this->incompatibleClasses1);
        unset($this->incompatibleClasses2);
        return $this;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'incompatibleClasses' => 'incompatible_classes'
    );

    public $Messages = [
        'name.unique' => 'A compatibility class with that name already exists.'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => ['unique:compatibility_class,name,'.$id.',compatibility_class_id']
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
