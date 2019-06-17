<?php

namespace App\Models;

use App\Http\Controllers\Injection\InjectionDueController;
use Illuminate\Http\Request;
use App\Models\Xisprefs;
use Carbon\Carbon;

/**
 * Class Injection.
 *
 *
 * @SWG\Definition(
 *   definition="Injection",
 *   required={"dose"}
 * )
 */

class Injection extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'injection';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'injection_id';

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
    protected $hidden = ['predicted_tpdetails_id', 'tpdetails_id', 'timecheckin', 'timeinjection', 'timeexcuse', 'code', 'question', 'vials', 'reaction_image', 'user_id', 'treatment_plan_id', 'compound_id', 'inj_adjust_id'];

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
     * @SWG\Property(
     *  example="mid right arm",
     *  pattern="(midR)|(midL)|(lowerR)|(lowerL)|(upperR)|(upperL)|(other)",
     *  title="site",
     *  description="The location on the body where the injection was given",
     *  minLength=0,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $site;

    /**
     * @SWG\Property(
     *  example="Patient forgot epipen.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="notes_patient",
     *  description="Free form notes attached to the injection record.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $notes_patient;

    /**
     * @SWG\Property(
     *  example="Patient forgot epipen.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="notes_user",
     *  description="Free form notes attached to the injection record.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $notes_user;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="systemic_reaction",
     *  description="One of the valid systemic reaction values configured in the system",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $systemic_reaction;

    /**
     * @SWG\Property(
     *  example="<10mm",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="local_reaction",
     *  description="One of the valid local reaction values configured in the system",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $local_reaction;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="compound_id",
     *  description="Id of the compound object from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $vial_id;

    /**
     * @SWG\Property(
     *  example="2017-08-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="datetime_administered",
     *  description="The date on which the injection was administered.",
     *  minLength=10,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $datetime_administered;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="barcode",
     *  description="barcode of the compound object from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $barcode;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Is the injection marked as deleted.",
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
     *  example="Dr. Smith",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="attending",
     *  description="Name of the attending provider.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $attending;

    public static function getReactionNames()
    {
        $ReactionStrings = Xisprefs::firstOrFail();
        $LocalNames = explode(',', $ReactionStrings->reactNamesL);
        $SystemicNames = explode(',', $ReactionStrings->reactNamesS);
        $Reactions = app()->make('stdClass');
        $Reactions->systemic = $SystemicNames;
        $Reactions->local = $LocalNames;

        return $Reactions;
    }
    public function getReaction()
    {
        $Reactions = $this::getReactionNames();
        if (in_array($this->sysreaction, array_slice($Reactions->systemic, 1))) {
            return $this->sysreaction;
        } elseif (in_array($this->reaction, array_slice($Reactions->local, 1))) {
            return $this->reaction;
        } else {
            return null;
        }
    }
    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function compound()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id');
    }
    public function adjust()
    {
        return $this->belongsTo('App\Models\InjAdjust', 'inj_adjust_id');
    }


    /**
     * Mutators to alter data before saving to DB.
     */
    public function setNotespatientAttribute($value)
    {
        $this->attributes['notespatient'] = preg_replace('/(\r\n)/', '<ENTER>', $value);
    }

    /**
     * Accessor.
     */
    public function getNotesPatientAttribute($value)
    {
        $value = preg_replace('/(<enter>)|(<ENTER>)/', "\r\n", $value);
        return $value;
    }

    public function getInjectionIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'notesuser' => 'notes_user',
        'sysreaction' => 'systemic_reaction',
        'reaction' => 'local_reaction',
        'tp_step' => 'treatment_plan_step',
        'notespatient' => 'notes_patient',
        'date' => 'datetime_administered',
        'timestamp' => 'datetime_entered',
        'compound_id' => 'vial_id'
    );

    public $Messages = [
        'vial_id.exists' => 'The requested vial does not exist or is not active.',
        'site.in' => 'Site must be in upperL,upperR,lowerL,lowerR,midL,midR,other',
        'override_dose_warning.required' => 'The provided dose does not match the predicted dose. Please ensure the value is correct and attach the override_dose_warning property with a value of T to continue.',
        'override_dilution_warning.required' => 'The provided dilution does not match the predicted dilution. Please ensure the barcode and/or vial_id is correct and attach the override_dilution_warning property with a value of T to continue.',
        'override_date_warning.required' => 'The indicated injection is not due per treatment plan. To record this injection anyway, attach the override_date_warning property with a value of T to continue.',
        'local_reaction.in_reactions' => 'The provided local reaction is not defined in this system.',
        'systemic_reaction.in_reactions' => 'The provided systemic reaction is not defined in this system.',
        'adjustment_due.required' => 'The selected dose and dilution do not match the pending adjustment. Please delete the pending adjustment or injection the required dose and dilution.'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'barcode' => 'exists:vial,barcode',
            'site' => 'in:upperL,upperR,lowerL,lowerR,midL,midR,other',
            'notes_patient' => 'standard',
            'datetime_administered' => ['date_format:Y-m-d H:i:s','before:'.Carbon::now()->endOfDay()->toDateTimeString()],
            'notes_user' => ['standard', 'between:0,45'],
            'local_reaction' => 'inReactions:local',
            'systemic_reaction' => 'inReactions:systemic',
            'deleted' => 'in:t,T,f,F',
            'dose' => ['numericGte:0','numericLte:100']
        ];
        return $Rules;
    }

    protected static function getCompound($data)
    {
        $nonXis = isset($data['override_non_xis_injection']) && $data['override_non_xis_injection'] === true;

        if (isset($data['vial_id'])) {
            $Query = new Compound;
            if (!$nonXis) {
                $Query = $Query->where('active', 'T');
            }
            return $Query->find($data['vial_id']);
        } elseif (isset($data['barcode'])) {
            $Query = new Compound;
            if (!$nonXis) {
                $Query = $Query->where('active', 'T');
            }
            return $Query->whereHas('vials', function ($query) use ($data) {
                $query->where('barcode', $data['barcode']);
            })->first();
        }
        return null;
    }

    protected static function getInjectionDue($patientId, $rxId)
    {
        $InjectionDueController = new InjectionDueController();
        $fakeRequest = Request::create('/v1/patient/'.$patientId.'/prescription/'.$rxId.'/injectiondue', 'GET'); //

        $data = $InjectionDueController->getInjectionDue($fakeRequest, $patientId, $rxId, true /*forensic*/)->getData();
        if (isset($data->next_injection)) {
            return $data;
        } else {
            return null;
        }
    }

    protected static function isLate($InjectionDue, $data)
    {
        //If its not a good date just say it isn't due
        if (isset($data['datetime_administered']) && !preg_match('/^(19[0-9]{2})|(20[0-9]{2})-(0[1-9]|1[0-2])-((0[1-9])|([12][0-9])|(30)|(31)) ((0[0-9])|(1[0-9])|(2[0-3])):([0-5][0-9]):([0-5][0-9])$/i', $data['datetime_administered'])) {
            return false;
        }

        $date = isset($data['datetime_administered']) ? Carbon::parse($data['datetime_administered']) : Carbon::now();
        if (isset($InjectionDue->forensic_nextInjection)) {
            $maxDate = isset($InjectionDue->forensic_nextInjection->max_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $InjectionDue->forensic_nextInjection->max_date) : null;
        } else {
            $maxDate = null;
        }

        return (!is_null($maxDate) && !$maxDate->gt($date)) || is_null($maxDate);
    }

    protected static function isDue($InjectionDue, $data)
    {
        //If its not a good date just say it isn't due
        if (isset($data['datetime_administered']) && !preg_match('/^(19[0-9]{2})|(20[0-9]{2})-(0[1-9]|1[0-2])-((0[1-9])|([12][0-9])|(30)|(31)) ((0[0-9])|(1[0-9])|(2[0-3])):([0-5][0-9]):([0-5][0-9])$/i', $data['datetime_administered'])) {
            return false;
        }

        $date = isset($data['datetime_administered']) ? Carbon::parse($data['datetime_administered']) : Carbon::now();
        if (isset($InjectionDue->forensic_nextInjection)) {
            $minDate = isset($InjectionDue->forensic_nextInjection->min_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $InjectionDue->forensic_nextInjection->min_date) : null;
        } else {
            $minDate = null;
        }

        return (!is_null($minDate) && !$minDate->gt($date)) || is_null($minDate);
    }

    protected static function isPredictedDose($InjectionDue, $data)
    {
        $expectedDose = isset($InjectionDue->dose) ? $InjectionDue->dose : 'ASK';
        $expectedDose = is_numeric($expectedDose) ? $expectedDose : strtoupper($expectedDose);
        return isset($data['dose']) && (($data['dose'] == $expectedDose) || $expectedDose === 'ASK');
    }

    protected static function isPredictedDilution($InjectionDue, $dilution)
    {
        $expectedDilution = isset($InjectionDue->dilution) ? $InjectionDue->dilution : 'ASK';
        $expectedDilution = is_numeric($expectedDilution) ? $expectedDilution : strtoupper($expectedDilution);
        return $expectedDilution == $dilution || $expectedDilution === 'ASK';
    }

    protected static function isHistorical($data)
    {
        if (!isset($data['datetime_administered']) || !preg_match('/^(19[0-9]{2})|(20[0-9]{2})-(0[1-9]|1[0-2])-((0[1-9])|([12][0-9])|(30)|(31)) ((0[0-9])|(1[0-9])|((2[0-3])):([0-5][0-9]):([0-5][0-9])|(00:00:00))$/i', $data['datetime_administered'])) {
            return false; //if no date was provided, its being given right now
        }
        if (isset($data['override_non_xis_injection']) && $data['override_non_xis_injection'] == true) {
            // all non xis are treated as historical and not validated
            // against tp etc.
            return true;
        } else {
            $RequestedDate = $data['datetime_administered'];
            return !Carbon::parse($RequestedDate)->isToday();
        }
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $data = $Validator->getData();
        $nonXis = isset($data['override_non_xis_injection']) && $data['override_non_xis_injection'] === true;
        $Compound = $this::getCompound($data);

        if (!is_null($Compound)) {
            $rxId = $Compound->rx_id;
            $dilution = $Compound->dilution;
            
            if (!$nonXis) {
                // this is NOT a non-xis-injection so it needs full validation
                $InjectionDue = $this::getInjectionDue($data['patient_id'], $rxId);
                $NextInjection = !is_null($InjectionDue) ? $InjectionDue->next_injection : null;

                if (!is_null($NextInjection) && !self::isHistorical($data)) {
                    //If its an adjustment, make sure the details match the adjust otherwise return the adjustment
                    //error
                    if (isset($NextInjection->forensic_adjust)) {
                        $Validator->sometimes(['adjustment_due'], ['required'], function () use ($NextInjection, $data, $dilution) {
                            return (
                                !$this::isPredictedDose($NextInjection, $data) ||
                                !$this::isPredictedDilution($NextInjection, $dilution)
                            );
                        });
                    } else {
                        //if expectedDose doesn't match the dose provided require the overrideDoseWarning property.
                        $Validator->sometimes(['override_dose_warning'], ['required'], function () use ($NextInjection, $data) {
                            return !$this::isPredictedDose($NextInjection, $data);
                        });

                        //if expectedDilution doesn't match the dilution found with the provide compound_id or barcode,
                        //require the overrideDilutionWarning property.
                        $Validator->sometimes(['override_dilution_warning'], ['required'], function () use ($NextInjection, $dilution) {
                            return !$this::isPredictedDilution($NextInjection, $dilution);
                        });

                        //if the date provided (or now if no date was provided) is not within the interval for the injection
                        //require the overrideDateWarning;
                        $Validator->sometimes(['override_date_warning'], ['required'], function () use ($NextInjection, $data) {
                            return !$this::isDue($NextInjection, $data);
                        });
                    }
                }
                //The return true makes this an always rule but I am attaching it here because we have already calculated
                //the dilution from barcode or compound_id so there was no reason to do it a second time in
                //makeValidationRules.
                $Validator->sometimes(['dose'], 'tpDoseDilution:'.$data['dose'].','.$dilution.','.$Compound->rx_id, function () {
                    return true;
                });
            }
        }

        $Validator->sometimes(['vial_id'], 'exists:compound,compound_id,active,T', function () use ($nonXis) {
            return !$nonXis;
        });

        $Validator->sometimes(['dose','site'], 'required', function () use ($id) {
            return is_null($id);
        });
        //Either barcode or compound_id must be provided during create
        $Validator->sometimes(['barcode'], 'required_without:vial_id', function () use ($id) {
            return is_null($id);
        });

        $Validator->sometimes(['vial_id'], 'required_without:barcode', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
