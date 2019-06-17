<?php

namespace App\Models;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

/**
 * Class Config.
 *
 *
 * @SWG\Definition(
 *   definition="Config",
 *   required={"name","app","value"}
 * )
 */

class Config extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'config';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'config_id';

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
    protected $hidden = ['compname'];

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="config_id",
     *  description="Id of the config object from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $config_id;

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
     *  example="vial",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="section",
     *  description="Section of the config item",
     *  minLength=0,
     *  maxLength=32,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $section;

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
     * @SWG\Property(
     *  example="XPS",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="app",
     *  description="App that the config item belongs to",
     *  minLength=0,
     *  maxLength=16,
     *  type={"string","null"},
     *  enum={"XPS","XIS","XST","LOGIN","LOBBY","ALL"},
     *  default="",
     * )
     *
     * @var string
     */
    private $app;

    /**
     * Accessors
     */
    public function getConfigIdAttribute($value)
    {
        return (int)$value;
    }

    public static function getLockOnBox3()
    {
        return Config::where('name', 'lockOnBox3')->first()['value'] === 'T';
    }

    public static function getEnforceLogoutTime()
    {
        $Prefs = explode(',', Xisprefs::first()->prefSet3);
        return $Prefs[2] === 'T';
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'compname' => 'comp_name'
    );

    public $Messages = [
        'config_multi_column_key' => 'The value for (:triplet) is already defined.'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $section = isset($data['section']) ? $data['section'] : null;
        $value = isset($data['value']) ? $data['value'] : null;
        $app = isset($data['app']) ? $data['app'] : null;
        
        $Rules = [
            'app' => array('in:xis,XIS,xps,XPS,xst,XST,login,Login,LOGIN,lobbyDashboard,LOBBYDASHBOARD,lobbydashbaord,All,all,ALL'),
            'name' => array('standard', 'between:0,32', 'configMultiColumnKey:'.$section.','.$value.','.$app.','.$id),
            'value' => array('standard'),
            'comp_name' => array('standard', 'between:0,100'),
            'section' => array('standard', 'between:0,32', Rule::notIn(['read_only'])),
        ];

        // add an error message replacer to handle plan validation

        \Validator::replacer('configMultiColumnKey', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':triplet', implode(', ', $parameters), $message);
        });

        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['app','name','section','value'], 'required', function () use ($id) {
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
