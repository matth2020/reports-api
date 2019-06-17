<?php

namespace App\Models;

/**
 * Class Dosing.
 *
 *
 * @SWG\Definition(
 *   definition="Dosing",
 *   required={"name"}
 * )
 */
class Dosing extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'dosing';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'dosing_id';

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
     *  title="inventory_id",
     *  description="Id of the inventory item for this associated dose",
     *  minLength=0,
     *  maxLength=11,
     *  default="null",
     * )
     * )
     *
     * @var int
     */
    private $inventory_id;
    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="dose",
     *  description="Prescriptions dose for this extract",
     *  minLength=0,
     *  maxLength=5,
     *  default="null",
     * )
     *
     * @var float
     */
    private $dose;

    /**
     * Relationships
     */
    public function extract()
    {
        return $this->belongsTo('App\Models\Extract', 'extract_id');
    }
    public function vials()
    {
        return $this->hasMany('App\Models\Vial', 'dosing_id');
    }
    public function prescription()
    {
        return $this->belongsTo('App\Models\Prescription', 'prescription_id');
    }

    /**
     * Accessors
     */
    public function getDosingIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'clickOrder' => 'click_order'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'dose' => ['decimal63','numericGte:0'],
            'prescription_id' => 'exists:prescription,prescription_id,strikethrough,F',
            'extract_id' => 'exists:extract,extract_id,deleted,F'
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['dose','prescription_id','extract_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
