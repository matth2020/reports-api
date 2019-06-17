<?php

namespace App\Models;

/**
 * Class ProviderDef.
 *
 *
 * @SWG\Definition(
 *   definition="ProviderDef",
 *   required={"provider_id","provider_config_id","dose"}
 * )
 */
class ProviderDef extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'provider_def';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'provider_def_id';

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
     *  title="provider_def_id",
     *  description="Id of the provider_def from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $provider_def_id;

    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="dose",
     *  description="Providers default dose for this extract",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $dose;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="last",
     *  description="Provider's default in season start date for this extract ?????",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $in_season_start;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="last",
     *  description="Provider's default in season end date for this extract ?????",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $in_season_end;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="outdates5",
     *  description="Provider's default outdate for 5 fold prescriptions ?????",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $outdates5;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="outdates10",
     *  description="Provider's default outdate for 10 fold prescriptions ?????",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $outdates10;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Is this provider_def accessible in the system (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="",
     * )
     *
     * @var string
     */
    private $deleted;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="extract_id",
     *  description="Id of the extract this provider_def applies to.",
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
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="provider_id",
     *  description="Id of the provider this provider_def applies to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $provider_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="provider_config_id",
     *  description="Id of the provider_config this provider_def applies to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $provider_config_id;

    /**
     * Relationships
     */
    public function extract()
    {
        return $this->belongsTo('App\Models\Extract', 'extract_id');
    }
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id');
    }
    public function providerConfig()
    {
        return $this->belongsTo('App\Models\Profile', 'provider_config_id');
    }

    /**
     * Accessors
     */
    public function getProviderDefIdAttribute($value)
    {
        return (int)$value;
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
