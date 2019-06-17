<?php

namespace App\Models;

use Illuminate\Validation\Validator;
use App\Models\Login;
use Carbon\Carbon;

/**
 * Class Login.
 *
 *
 * @SWG\Definition(
 *   definition="Login",
 *   required={"patient_id","state"}
 * )
 */
class Login extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'login';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'login_id';

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
     *  title="login_id",
     *  description="Id of the login entry from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $login_id;

    /**
     * @SWG\Property(
     *  example="John Doe",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Patient approved (HIPPA compliant) identification name",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="2016-08-30 13:27:53",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) (2[0-4]|[0-1][0-9])(:([0-5][0-9]|60)){2}$",
     *  title="login_time",
     *  description="Date/time when patient logged in at kiosk",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $login_time;

    /**
     * @SWG\Property(
     *  example="2016-08-30 13:57:23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) (2[0-4]|[0-1][0-9])(:([0-5][0-9]|60)){2}$",
     *  title="excuse_time",
     *  description="Date/time when nurse scheduled patient to be excused",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $excuse_time;

    /**
     * @SWG\Property(
     *  example="2016-08-30 13:34:53",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) (2[0-4]|[0-1][0-9])(:([0-5][0-9]|60)){2}$",
     *  title="time_next",
     *  description="Time of next injection for RUSH?",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $time_next;

    /**
     * @SWG\Property(
     *  example="2016-08-30 13:44:53",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) (2[0-4]|[0-1][0-9])(:([0-5][0-9]|60)){2}$",
     *  title="time_out",
     *  description="what is this for???",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $time_out;

    /**
     * @SWG\Property(
     *  example="2016-08-30 13:53:53",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) (2[0-4]|[0-1][0-9])(:([0-5][0-9]|60)){2}$",
     *  title="time_left",
     *  description="Date/time when patient left. Updated each time a patient attempts logout even when unsuccessful.",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $time_left;

    /**
     * @SWG\Property(
     *  example="something.jpg",
     *  pattern="^([a-zA-Z0-1_-]+){1}\.[a-zA-Z0-1]+$",
     *  title="image_file",
     *  description="Future use.",
     *  minLength=0,
     *  maxLength=150,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $image_path;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="patient_id",
     *  description="Id of the patient associated with this login event.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $patient_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^(0|1)$",
     *  title="state",
     *  description="1 if patient is currently logged in. 0 if logged out",
     *  minLength=0,
     *  maxLength=11,
     *  type={"string","null"},
      *  enum={"logged_out","waiting_to_be_excused","waiting_for_injection","with_injection_staff"},
     *  default="",
     * )
     *
     * @var int
     */
    private $state;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="clinic_id",
     *  description="Location at which patient logged in. Links to clinic_id.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $clinic_id;

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id');
    }

    /**
     * Accessors
     */
    public function getStateAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'logged_out';
            case 1:
                return 'waiting_for_injection';
            case 2:
                return 'with_injection_staff';
            case 3:
                return 'waiting_to_be_excused';
            default:
                return 'unknown_state';
        }
    }

    public function getLoginIdAttribute($value)
    {
        return (int)$value;
    }
    
    /**
     * Mutators
     */
    public function setStateAttribute($value)
    {
        switch (strtolower($value)) {
            case 'waiting_for_injection':
                $value = 1;
                break;
            case 'with_injection_staff':
                $value = 2;
                break;
            case 'waiting_to_be_excused':
                $value = 3;
                break;
            default:
                $value = 0;
                break;
        }

        $this->attributes['state'] = $value;
    }

    public function scopeSearchLoginState($query, $column, $value)
    {
        if (is_null($value) || $value === '%') {
            //no search so don't change query
            return $query;
        } elseif (!strpos($value, '%')) {
            //if it doens't contain a % its not a wildcard search
            return $query->like('state', $value);
        } else {
            //here we need to do complicated stuff to figure out the actual search since the
            //user will be searching for a string like 'logged%' but actual states are int
            
            //convert to regex
            $pattern = '/'.str_replace('%', '.*', $value).'/';
            
            return $query->where(function ($query2) use ($pattern) {
                if (preg_match($pattern, 'logged_out')) {
                    $query2 = $query2->orWhere('state', 0);
                }
                if (preg_match($pattern, 'waiting_to_be_excused')) {
                    $query2 = $query2->orWhere('state', 3);
                }
                if (preg_match($pattern, 'waiting_for_injection')) {
                    $query2 = $query2->orWhere('state', 1);
                }
                if (preg_match($pattern, 'with_injection_staff')) {
                    $query2 = $query2->orWhere('state', 2);
                }
            });
        }
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'excuseTime' => 'excuse_time',
        'loginTime' => 'login_time',
        'timeOut' => 'scheduled_departure',
        'timeLeft' => 'last_departure_attempt',
    );

    public $Messages = [
        'override_early_logout.required' => 'You have not yet completed the required wait time. Are you sure you want to leave early?'
    ];

    // filtering call above rest function and does the filter.
    // check model for HIDDEN
    // createing new attributes
    // finalize to create new variables with any logic.
    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => array('standard', 'between:1,45'),
            'state' => array(
                'in:waiting_for_injection,with_injection_staff,waiting_to_be_excused,logged_out',
            ),
            'login_time' => array('date_format:Y-m-d H:i:s'),
            'excuse_time' => array('date_format:Y-m-d H:i:s'),
            'last_departure_attempt' => array('date_format:Y-m-d H:i:s'),
            'scheduled_departure' => array('date_format:Y-m-d H:i:s'),
            'encounter_id' => array('nullable', 'integer')

        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $data = $Validator->getData();
        $Validator->sometimes(['patient_id','name'], 'required', function () use ($id, $data) {
            return is_null($id) && (!isset($data['state']) || (isset($data['state']) && $data['state'] != 'logged_out'));
        });

        $Validator->sometimes(
            ['patient_id'],
            // make sure patient isn't locked due to box3
            function ($attribute, $value, $fail) {
                try {
                    $Patient = Patient::find($value);
                    if ($Patient->getLockOnBox3()) {
                        $fail('Please see front desk staff to complete login process.');
                    }
                } catch (exception $e) {
                }
            },
            function () use ($id, $data) {
                return is_null($id) && (!isset($data['state']) || (isset($data['state']) && $data['state'] != 'logged_out'));
            }
        );

        $EnforceLogoutTime = Config::getEnforceLogoutTime();
        // make sure the user isn't doing double logout
        $Validator->sometimes(
            ['state'],
            [
                // if the user is trying to double logout
                function ($attribute, $value, $fail) use ($id, $data) {
                    if (!is_null($id)) {
                        if (Login::find($id)->state === $value) {
                            $fail('You are not currently logged into the system.');
                        }
                    } elseif (isset($data['patient_id'])) {
                        if (Login::where('patient_id', $data['patient_id'])->get()->count() === 0) {
                            $fail('You are not currently logged into the system.');
                        }
                    }
                },
                // if the user is trying to logout early
                function ($attribute, $value, $fail) use ($data, $Validator, $EnforceLogoutTime) {
                    try {
                        $Login = Login::where('patient_id', $data['patient_id'])
                            ->where('state', '<>', '0')
                            ->firstOrFail();
                        if (is_null($Login->timeOut)) {
                            if ($EnforceLogoutTime) {
                                $fail('The staff has not scheduled your release. Please talk to the front desk staff if this is an error.');
                            }
                        } elseif (Carbon::parse($Login->timeOut)->gt(Carbon::now())) {
                            $Min = Carbon::parse($Login->timeOut)->diffInMinutes(Carbon::now());
                            if ($EnforceLogoutTime) {
                                $fail('You are not scheduled to leave for another '.$Min.' minutes. Please try logging out again at that time.');
                            }
                        }
                    } catch (exception $e) {
                    }
                }
            ],
            function () use ($id, $data) {
                return isset($data['state']) && $data['state'] === 'logged_out';
            }
        );

        $Validator->sometimes(
            ['override_early_logout'],
            ['required'],
            function () use ($EnforceLogoutTime, $data) {
                if ($data['state'] !== 'logged_out') {
                    return false;
                } else {
                    try {
                        $Login = Login::where('patient_id', $data['patient_id'])
                                    ->where('state', '<>', '0')
                                    ->firstOrFail();
                        return !$EnforceLogoutTime && (is_null($Login->timeOut) || Carbon::parse($Login->timeOut)->gt(Carbon::now()));
                    } catch (exception $e) {
                        return false;
                    }
                }
            }
        );

        $Validator->sometimes(['name'], function ($attribute, $value, $fail) {
            if (Login::where('state', '<>', '0')->where('name', $value)->count() > 0) {
                return $fail('This display name is already in use. Please select a unique display name.');
            }
        }, function () use ($data) {
            return isset($data['manual_login']) && $data['manual_login'] === true;
        });

        $Validator->sometimes('patient_id', ['exists:patient,patient_id,archived,F', function ($attribute, $value, $fail) use ($id) {
            if (is_null($id) && Login::where('patient_id', $value)->where('state', '<>', '0')->count() !== 0) {
                return $fail('You are already logged into the system. Please logout before logging in again.');
            }
        }], function () use ($data) {
            return (!isset($data['manual_login']) || $data['manual_login'] === false) && (!isset($data['state']) || ($data['state'] !== 'logged_out'));
        });

        return $Validator;
    }
}
