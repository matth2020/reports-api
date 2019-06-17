<?php

namespace App\Models;

/**
 * Class TrackingConfig.
 *
 *
 * @SWG\Definition(
 *   definition="TrackingConfig",
 *   required={"patient_id"}
 * )
 */

class TrackingConfig extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'trackingconfig';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'trackingConfig_id';

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
     *  title="patient_id",
     *  description="Id of the patient that the trackingconfig object applies to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $patient_id;

    /**
     * @SWG\Property(
     *  example={},
     *  pattern="",
     *  title="values",
     *  description="An array of objects describing system level tracking values",
     *  type="array",
     *  @SWG\Items(ref="#/definitions/TrackingConfigRow")
     * )
     * @var plan   */
    private $values;

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient');
    }

    /**
     * Accessors
     */
    public function getTrackingConfigIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'trackingConfig_id' => 'tracking_config_id',
        'trackingName' => 'tracking_name',
        'min' => 'minimum',
        'max' => 'maximum'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'tracking_name' => array('exists:config,name,config_id,'.$data['config_id']),
            'minimum' => array('decimal52','min:-1','numeric'),
            'maximum' => array('decimal52','min:-1','numeric')
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
