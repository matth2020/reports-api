<?php

namespace App\Models;

/**
 * Class Panel.
 *
 *
 * @SWG\Definition(
 *   definition="Panel",
 *   required={"profile_name"}
 * )
 */
class Panel extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'panel';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'panel_id';

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="panel_id",
     *  description="Id of the panel from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $panel_id;

    /**
     * @SWG\Property(
     *  example="grasses",
     *  title="name",
     *  description="Name of the panel",
     *  minLength=1,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="null",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  title="class",
     *  description="class of the panel",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $class;

    /**
     * @SWG\Property(
     *  example="2",
     *  title="panel_column",
     *  description="Column to place the panel in",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $panel_column;

    public $timestamps = false;

    /**
     * Relationships
     */
    public function antigens()
    {
        return $this->hasManyThrough('App\Models\Antigen', 'App\Models\PanelAntigen', 'panel_id', 'antigen_id', 'panel_id', 'antigen_id');
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'panelcol' => 'panel_column'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => array('standard', 'between:1,45', 'unique:panel,name'),
            'class' => array('standard', 'between:1,45'),
            'panel_column' => array('integer', 'between:0,100')
        ];
        return $Rules;
    }

    public $Messages = [
        'name.standard' => 'The name can only contain upper and lower case letters, digits, spaces, and -/\:,!#?()&+{|}\~<=>@\[\]^_:%".'
    ];

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
