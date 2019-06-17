<?php

namespace App\Models;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

/**
 * Class Config.
 *
 *
 * @SWG\Definition(
 *   definition="UserConfig",
 *   required={"name","app","value"}
 * )
 */

class UserConfig extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'user_config';
    public $exists = false;

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_config_id';

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
     *  title="user_config_id",
     *  description="Id of the config object from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $user_config_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="user_id",
     *  description="Id of the user the config object belongs to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $user_id;

    /**
     * @SWG\Property(
     *  example="sizes",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Name of the config item",
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
     *  example="5ml,10ml,15ml",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="value",
     *  description="Value of the config item",
     *  minLength=0,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $value;

    /**
     * Accessors
     */
    public function getConfigIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    public $Messages = [
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $value = isset($data['value']) ? $data['value'] : null;
        $app = isset($data['app']) ? $data['app'] : null;
        
        $Rules = [
            'user_id' => array('exists:user,user_id,deleted,F'),
            'name' => array('standard', 'between:0,32'),
            'value' => array('standard'),
        ];

        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['user_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }

    public function markDeleted($RequestOptions)
    {
        $this->delete();
        return $this;
    }
}
