<?php

namespace App\Models;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

/**
 * Class Compound.
 *
 *
 * @SWG\Definition(
 *   definition="Compound",
 *   required={"name"}
 * )
 */
class Compound extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'compound';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'compound_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['currVol', 'user_id', 'timestamp', 'size', 'name', 'color', 'dilution', 'bottleNum', 'rx_id', 'compound_note'];

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
    protected $hidden = ['dose_scan','inventory_scan',];

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="vial_num",
     *  description="Bottle number",
     *  minLength=0,
     *  maxLength=45,
     *  default="null",
     * )
     * )
     *
     * @var string
     */
    private $vial_number;
    
    /**
     * @SWG\Property(
     *  example="100",
     *  pattern="^[0-9]+$",
     *  title="dilution",
     *  description="Dilution of the vial",
     *  minLength=0,
     *  maxLength=45,
     *  default="null",
     * )
     *
     * @var int
     */
    private $dilution;

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function providerConfig()
    {
        return $this->belongsTo('App\Models\Profile', 'provider_config_id');
    }
    public function prescription()
    {
        return $this->belongsTo('App\Models\Prescription', 'rx_id');
    }
    public function treatmentSet()
    {
        return $this->belongsTo('App\Models\TreatmentSet', 'treatment_set_id');
    }
    public function dosings()
    {
        return $this->hasManyThrough('App\Models\Dosing', 'App\Models\Vial', 'compound_id', 'dosing_id', 'compound_id', 'dosing_id');
    }
    public function injections()
    {
        return $this->hasMany('App\Models\Injection', 'compound_id');
    }
    public function vials()
    {
        return $this->hasMany('App\Models\Vial', 'compound_id');
    }

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = strtoupper($value);
    }

    /**
     * Accessors
     */
    public function getCompoundIdAttribute($value)
    {
        return (int)$value;
    }
    public function getDilutionAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'compound_note' => 'vial_note',
        'bottleNum' => 'vial_number',
        'currVol' => 'current_volume',
        'compound_id' => 'vial_id',
        'rx_id' => 'prescription_id',
        'provider_config_id' => 'profile_id',
        'shipMethod' => 'ship_method',
        'shipWith' => 'ship_with',
        'compound_id' => 'vial_id'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'active' => 'in:t,T,f,F',
            'user_id' => 'exists:user,user_id,deleted,F',
            'compound_note' => 'standard',
            'name' => array('standard','between:0,150'),
            'timestamp' => 'date_format:Y-m-d H:i:s',
            'size' => 'in:5 mL,10 mL',
            'color' => 'color',
            'dilution' => 'integer',
            'vial_number' => 'between:0,45',
            'current_volume' => 'decimal52',
            'tray_location' => array('standard','between:0,45'),
            'prescription_id' => 'exists:prescription,prescription_id,strikethrough,F',
            'profile_id' => function ($attribute, $value, $fail) use ($id) {
                $GoodProfile = Profile::where('provider_config_id', $value)->where('deleted', 'F')->count() > 0;
                if (!is_null($id)) {
                    // if id is not null then this is an update so see if the value is changing
                    $ChangedCompound = Compound::where('provider_config_id', '<>', $value)->where('provider_config_id', $id)->count() > 0;
                } else {
                    // if its a new compound row then its changed...
                    $ChangedCompound = true;
                }

                if ($ChangedCompound && !$GoodProfile) {
                    $fail($attribute.' must be a valid profile_id where deleted = F.');
                }
            },
            'shipMethod' => array('standard','between:0,20'),
            'shipWith' => array('standard','between:0,20'),
            'tracking' => array('standard','between:0,50'),
            'external_id' => array('standard','between:0,100'),
            'DIN' => array('standard','between:0,20'),
            'compound_receipt_id' => array('nullable','exists:compound_receipt,compound_receipt_id'),
            'treatment_set_id' => 'exists:treatment_set,treatment_set_id'
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $data = $Validator->getData();

        $Validator->sometimes(
            ['active'],
            Rule::unique('compound')->where('rx_id', $data['prescription_id'])->where('dilution', $data['dilution']),
            function () use ($data) {
                return isset($data['active']) ? strtoupper($data['active']) == 'T' : false;
            }
        )->sometimes(['user_id','timestamp','size','color','dilution','vial_number','prescription_id','profile_id','treatment_set_id', 'name'], 'required', function () use ($id) {
            return is_null($id);
        })->sometimes(['vial_id'], 'required', function () use ($id) {
            return $id === -1;
        });

        return $Validator;
    }
}
