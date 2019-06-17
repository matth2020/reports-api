<?php

namespace App\Models;

/**
 * Class PanelAntigen.
 *
 *
 * @SWG\Definition(
 *   definition="PanelAntigen",
 *   required={"profile_name"}
 * )
 */
class PanelAntigen extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'panel_antigen';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'panel_antigen_id';

    public $timestamps = false;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="panel_antigen_id",
     *  description="Id of the panel_antigen from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $panel_antigen_id;

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
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="antigen_id",
     *  description="Id of the antigen from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $antigen_id;

    /**
     * Relationships
     */

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'panel_id' => array('exists:panel,panel_id'),
            'antigen_id' => array('exists:antigen,antigen_id,deleted,F')
        ];
        return $Rules;
    }

    public $Messages = [
        'name.standard' => 'The name can only contain upper and lower case letters, digits, spaces, and -/\:,!#?()&+{|}\~<=>@\[\]^_:%".'
    ];

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['antigen_id','panel_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
