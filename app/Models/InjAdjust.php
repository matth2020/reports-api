<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InjAdjust.
 *
 *
 * @SWG\Definition(
 *   required={"prescription_number","dose","dilution","date"},
 * )
 */
class InjAdjust extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'injadjust';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'injAdjust_id';

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
    protected $hidden = [];

    /**
    * @SWG\Property(
    *  example="0.20",
    *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
    *  title="dose",
    *  description="Adjusted dose value",
    *  minLength=0,
    *  maxLength=5,
    *  default="null",
    * )
    *
    * @var float
    */
    private $dose;

    /**
    * @SWG\Property(
    *  example="1000",
    *  title="dilution",
    *  description="Adjusted dilution value",
    *  minLength=0,
    *  maxLength=10,
    *  default="null",
    * )
    *
    * @var int
    */
    private $dilution;

    /**
    * @SWG\Property(
    *  example="90003",
    *  title="prescription_number",
    *  description="Prescription number that the adjustment should be applied to.",
    *  minLength=0,
    *  maxLength=10,
    *  default="null",
    * )
    *
    * @var int
    */
    private $prescription_number;

    /**
     * @SWG\Property(
     *  example="2017-08-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="date",
     *  description="The date on which the adjustment should be administered.",
     *  minLength=10,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $date;

    /**
     * @SWG\Property(
     *  example="Initial dose",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="reason",
     *  description="Free form notes as to why the adjustment was made.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $reason;

    /**
     * Relationships
     */
    public function injection()
    {
        return $this->hasOne('App\Models\Injection', 'inj_adjust_id', 'injAdjust_id');
    }

    public function prescription()
    {
        return $this->belongsTo('App\Models\Prescription', 'prescription_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'adjby', 'user_id');
    }
    /**
     * Accessors
     */
    public function getDateAttribute($value)
    {
        if ($value === '' || is_null($value)) {
            return $value;
        } else {
            return Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getInjAdjustIdAttribute($value)
    {
        return (int)$value;
    }

    /**
     * Mutators
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('n/j/Y');
    }
    public function setDilutionAttribute($value)
    {
        // if its a string it might have a leading 1: so remove it.
        $this->attributes['dilution'] = is_string($value) ? str_replace('1:', '', $value) : $value;
    }
    // for injectionAdjust, if the injection is linked to an injection then it is only marked deleted
    // otherwise it can be deleted for real
    public function markDeleted($RequestOptions)
    {
        //This used to be used such that if the adjust was linked to
        //an injection it would be marked deleted=T and if not it would
        //be deleted for real. This caused a problem during transactions
        //when an adjustment was linked to an injection not yet committed
        //to the db...because it wasn't committed the adjust wasn't linked
        //as far as the db was concerned so it was deleted for real, then
        //when the injection was committed the foreign key constraint
        //would fail.
        //Leaving the old code for now as we might come up with a nicer
        //way to handle this in the future but for now, we wont remove
        //any adjusts from the db ever.
        $this->load('injection');
        $this->injection();
        // if (is_null($this->injection)) {
        //     $this->delete();
        // } else {
        $this->deleted = 'T';
        $this->save();
        // }
        
        return $this;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'adjby' => 'adjusted_by',
        'injAdjust_id' => 'injection_adjust_id'
    );

    public $Messages = [
        'dilution.tp_dilution' => 'The dilution provided is outside of the treatment plan.',
        'dose.tp_dose_dilution' => 'The dose provided is outside of the treatment plan.'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        if (!isset($data['prescription_id'])) {
            $prescriptionId = $this->prescription_id;
        } else {
            $prescriptionId = $data['prescription_id'];
        }
        $Rules = [
            'injection_adjust_id' => array('unique:injection,inj_adjust_id'), //if its associated with an inj you cant edit it.
            'prescription_id' => array('exists:prescription,prescription_id,strikethrough,F'),
            'dilution' => array('between:0,45'),
            'date' => array('date_format:Y-m-d'),
            'deleted' => array('in:t,T,f,F'),
            'reason' => array('standard', 'between:0,45'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['prescription_id','dose','dilution','date'], 'required', function () use ($id) {
            return is_null($id);
        });

        $data = $Validator->getData();
        if (!is_null($id) && (isset($data['dose']) || isset($data['dilution']))) {
            //if they are updating dose or dilution make sure we have all the data we need.
            $oldAdjust = InjAdjust::find($id);
        }
        $dose = isset($data['dose']) ? $data['dose'] : $oldAdjust->dose;
        $dilution = isset($data['dilution']) ? $data['dilution'] : $oldAdjust->dilution;
        $rx_id = isset($data['prescription_id']) ? $data['prescription_id'] : $oldAdjust->prescription_id;

        $Validator->sometimes(['dose'], 'tpDoseDilution:'.$dose.','.$dilution.','.$rx_id, function () use ($data) {
            return (isset($data['dose']) && !isset($data['override_non_xis_injection']));
        });

        $Validator->sometimes(['dilution'], 'tpDilution:'.$dilution.','.$rx_id, function () use ($data) {
            return (isset($data['dilution']) && !isset($data['override_non_xis_injection']));
        });

        return $Validator;
    }
}
