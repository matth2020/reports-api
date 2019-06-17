<?php

namespace App\Models;

/**
 * Class Profile.
 *
 *
 * @SWG\Definition(
 *   definition="Profile",
 *   required={"profile_name"}
 * )
 */
class Profile extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'provider_config';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'provider_config_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    public $hidden = ['paAlertLast','paAlertPeriod', 'paAlertEvent', 'paAlertVol', 'doseRules', 'custDils'];

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="provider_config_id",
     *  description="Id of the provider_config from the database.",
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
     * @SWG\Property(
     *  example="1",
     *  pattern="^(less)|(greater)$",
     *  title="auto_select_dilutions",
     *  description="'greater' for increasing dilution ordering (1, 10, 100...), 'less' for decreasing ordering",
     *  minLength=0,
     *  maxLength=1,
     *  type={"string","null"},
     *  default="null",
     * )
     *
     * @var string
     */
    private $auto_select_dilutions;

    /**
     * @SWG\Property(
     *  example="16448250,16776960,65280,9002752,0,0,0,0",
     *  pattern="^([0-9]+,){7}[0-9]+$",
     *  title="color",
     *  description="CSV of 8 integer values representing profile colors",
     *  minLength=0,
     *  maxLength=100,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $color;

    /**
     * @SWG\Property(
     *  example="WHT,YLW,GRN,GOLD,,,,",
     *  pattern="^([A-Z]+,){7}[A-Z]+$",
     *  title="color_names",
     *  description="CSV list of 8 color names. (Supported colors are RED,YLW,BLUE,GRN,SLVR,ORNG,PRPL,WHT,LTGR,LTBL,PINK,GOLD)",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $color_names;

    /**
     * @SWG\Property(
     *  example="0,1,2,3,4,5,8,8",
     *  pattern="^([0-9],){7}[0-9]{1}$",
     *  title="dilutions10",
     *  description="CSV list of up to 8 dilution exponents (8=not used) example: 2 -> 10^2 = dilution 1:100",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $dilutions10;

    /**
     * @SWG\Property(
     *  example="0,1,2,3,8,8,8,8",
     *  pattern="^([0-9],){7}[0-9]{1}$",
     *  title="dilutions5",
     *  description="CSV list of up to 8 dilution exponents (8=not used) example: 2 -> 5^2 = dilution 1:25",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $dilutions5;

    /**
     * @SWG\Property(
     *  example="0,10,20,55,100",
     *  pattern="",
     *  title="cust_dils",
     *  description="CSV list of up to 8 custom dilutions",
     *  minLength=0,
     *  maxLength=100,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $cust_dils;

    /**
     * @SWG\Property(
     *  example="12,9,6,3,3,0,0,0",
     *  pattern="^([0-9]+,){7}[0-9]+$",
     *  title="expirations10",
     *  description="CSV list of 8 values indicating number of months before expiration for associated dilution in dilutions10 field",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $expirations10;

    /**
     * @SWG\Property(
     *  example="12,9,6,3,0,0,0,0",
     *  pattern="^([0-9]+,){7}[0-9]+$",
     *  title="expirations5",
     *  description="CSV list of 8 values indicating number of months before expiration for associated dilution in dilutions5 field",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $expirations5;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="",
     *  title="billrate10",
     *  description="?????",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $billrate10;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="",
     *  title="billrate5",
     *  description="?????",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $billrate5;

    /**
     * @SWG\Property(
     *  example="10",
     *  pattern="^(10)|(5)|(-1)$",
     *  title="profile_rate",
     *  description="Profile rate 10=10fold, 5=5fold, -1=custom",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $profile_rate;

    /**
     * @SWG\Property(
     *  example="0",
     *  pattern="^[0-9]$",
     *  title="offset",
     *  description="Offset to begin bottle numbering.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $offset;

    /**
     * @SWG\Property(
     *  example="Default profile",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Visible name of profile",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $profile_name;

    /**
     * @SWG\Property(
     *  example="0",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="low_glyc",
     *  description="Low glycerine target",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $low_glyc;

    /**
     * @SWG\Property(
     *  example="0",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="high_glyc",
     *  description="High glycerine target",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $high_glyc;

    /**
     * @SWG\Property(
     *  example="5 mL",
     *  pattern="^[0-9]{1,2}( mL){1}$",
     *  title="def_vial_size",
     *  description="Default vial size",
     *  minLength=0,
     *  maxLength=5,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $def_vial_size;

    /**
     * @SWG\Property(
     *  example="2017-01-01",
     *  pattern="^[0-9]{4}(-[0-9]{2}){2}$",
     *  title="pa_alert_last",
     *  description="????",
     *  minLength=0,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $pa_alert_last;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="",
     *  title="pa_alert_period",
     *  description="????",
     *  minLength=0,
     *  maxLength=11,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $pa_alert_period;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="",
     *  title="pa_alert_event",
     *  description="????",
     *  minLength=0,
     *  maxLength=11,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $pa_alert_event;

    /**
     * @SWG\Property(
     *  example="2.3",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="pa_alert_vol",
     *  description="????",
     *  minLength=0,
     *  maxLength=11,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $pa_alert_vol;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tfTF]$",
     *  title="incl_dil_name",
     *  description="Include diluent name (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $incl_dil_name;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="pref_glyc_dil",
     *  description="Extract_id for the preferred glycerine diluent",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="null",
     * )
     *
     * @var int
     */
    private $pref_glyc_dil;

    /**
     * @SWG\Property(
     *  example="2",
     *  pattern="^[0-9]+$",
     *  title="pref_aq_dil",
     *  description="Extract_id for the preferred aqueous diluent",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $pref_aq_dil;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="provider_id",
     *  description="Id of the provider this config belongs to.",
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
     *  example="F",
     *  pattern="^[tfTF]$",
     *  title="deleted",
     *  description="Is this provider config available for use (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $deleted;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="",
     *  title="dose_rules",
     *  description="??????",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $dose_rules;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="",
     *  title="default_fill_volume",
     *  description="??????",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $default_fill_volume;

    /**
     * Relationships
     */
    public function compounds()
    {
        return $this->hasMany('App\Models\Compound', 'provider_config_id');
    }
    public function prescription()
    {
        return $this->hasMany('App\Models\Prescription', 'provider_config_id');
    }
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id');
    }
    public function providerDef()
    {
        return $this->hasMany('App\Models\ProviderDef', 'provider_config_id');
    }

    public function hasDilution($Dilution)
    {
        if ($this->profileRate === 10) {
            $dilutions = explode(',', $this['dilutions10']);
            foreach ($dilutions as $key => $dilution) {
                if (pow(10, (int)$dilution) == (int)$Dilution) {
                    return true;
                }
            }
        } elseif ($this->profileRate === 5) {
            $dilutions = explode(',', $this['dilutions5']);
            foreach ($dilutions as $key => $dilution) {
                if (pow(5, (int)$dilution) == (int)$Dilution) {
                    return true;
                }
            }
        } elseif ($this->profileRate === -1) {
            $dilutions = explode(',', $this['dilutions10']);
            foreach ($dilutions as $key => $dilution) {
                if ((int)$dilution == (int)$Dilution) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getDilutionColor($Dilution)
    {
        if ($this->profileRate === 10 && $this->dilutions10 != '8,8,8,8,8,8,8,8') {
            //10 fold dilutions
            $dilutions = explode(',', $this['dilutions10']);
            foreach ($dilutions as $key => $dilution) {
                if (pow(10, (int)$dilution) == (int)$Dilution) {
                    $Colors = explode(',', $this['colorNames']);
                    return $Colors[$key];
                }
            }
            return null;
        } elseif ($this->profileRate === 5 && $this->dilutions5 != '8,8,8,8,8,8,8,8') {
            //5 fold dilutions
            $dilutions = explode(',', $this['dilutions5']);
            foreach ($dilutions as $key => $dilution) {
                if (pow(5, (int)$dilution) == (int)$Dilution) {
                    $Colors = explode(',', $this['colorNames']);
                    return $Colors[$key];
                }
            }
            return null;
        } elseif ($this->profileRate === -1) {
            //custom dilutions
            $dilutions = explode(',', $this['dilutions10']);
            foreach ($dilutions as $key => $dilution) {
                if ((int)$dilution == (int)$Dilution) {
                    $Colors = explode(',', $this['colorNames']);
                    return $Colors[$key];
                }
            }
            return null;
        } else {
            return null;
        }
    }

    /**
     * Mutators to alter data before saving to DB.
     */
    public function setInclDilNamesAttribute($value)
    {
        $this->attributes['inclDilNames'] = strtoupper($value);
    }

    public function setDoseRulesAttribute($value)
    {
        $this->attributes['doseRules'] = preg_replace('/[(\r\n)(\n\r)]/', '<enter>', $value);
    }

    /**
     * Accessors.
     */
    public function getNumorderAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        } elseif ((int)$value === 0) {
            return 'descending_dilution';
        } else {
            return 'ascending_dilution';
        }
    }

    public function getProfileIdAttribute($value)
    {
        return (int)$value;
    }
    /**
     * Mutators
     */
    public function setNumorderAttribute($value)
    {
        if (strtolower($value) === 'descending_dilution') {
            $this->attributes['numorder'] = 0;
        } else {
            $this->attributes['numorder'] = 1;
        }
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'provider_config_id' => 'profile_id',
        'numorder' => 'bottle_numbering_order',
        'profileRate' => 'dilution_rate',
        'profileName' => 'name',
        'lowGlyc' => 'low_glycerin_limit',
        'highGlyc' => 'high_glycerin_limit',
        'defVialSize' => 'default_vial_size',
        'inclDilName' => 'include_diluent_name',
        'prefGlycDil' => 'preferred_glycerin_diluent_id',
        'prefAqDil' => 'preferred_aqueous_diluent_id'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => array('standard', 'between:1,45'),
            'provider_id' => array('exists:provider,provider_id,deleted,F'),
            'offset' => array('integer', 'between:-2,2'),
            'low_glycerin_limit' => array('numeric', 'between:0,100.00'),
            'high_glycerin_limit' => array('numeric', 'between:0,100.00'),
            'default_vial_size' => array('standard', 'between:0,15', 'regex:/^(\d+)/u'),
            'include_diluent_name' => 'in:t,T,f,F',
            'preferred_glycerin_diluent_id' => 'exists:extract,extract_id',
            'preferred_aqueous_diluent_id' => 'exists:extract,extract_id',
            'bottle_numbering_order' => 'in:ascending_dilution,descending_dilution',
            'dilution_steps.*.dilution' => array('required', 'integer', 'between:0,100000000'),
            'dilution_steps.*.bill_rate' => 'numeric',
            'dilution_steps.*.expiration' => array('required', 'integer', 'between:0,60'),
            'dilution_steps.*.color_name' => array('required', 'standard', 'between:0,4')
        ];
        return $Rules;
    }

    public $Messages = [
        'name.standard' => 'The name can only contain upper and lower case letters, digits, spaces, and -/\:,!#?()&+{|}\~<=>@\[\]^_:%".'
    ];

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes([], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
