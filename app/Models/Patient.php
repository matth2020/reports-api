<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;
use App\Models\User;
use App\Models\Config;
use DB;

/**
 * Class Patient.
 *
 *
 * @SWG\Definition(
 *   definition="Patient",
 *   required={"firstname", "lastname","mi","dob","chart"}
 * )
 */
class Patient extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patient';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'patient_id';

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
    protected $hidden = ['external_id', 'PV1segement', 'PIDsegement', 'botCheck', 'lockby', 'lockState', 'lock_id'];

    protected $fillable = ['firstname',
        'lastname',
        'mi',
        'dob',
        'phone',
        'addr1',
        'addr2',
        'city',
        'state',
        'zip',
        'displayname',
        'chart',
        'eContact',
        'eContactNum',
        'email',
        'smsphone',
        'PIDsegment',
        'PV1segment',
        'gender',
        'ssn',
        'fax'];
    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="patient_id",
     *  description="Id of the patient from the database.",
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
     *  example="John",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="firstname",
     *  description="Patient firstname",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $firstname;

    /**
     * @SWG\Property(
     *  example="Doe",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="lastname",
     *  description="Patient lastname",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $lastname;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="mi",
     *  description="Patient middle initial",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $mi;

    /**
     * @SWG\Property(
     *  example="1973-08-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="dob",
     *  description="Patients date of birth",
     *  minLength=10,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $dob;

    /**
     * @SWG\Property(
     *  example="2015-03-03",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="shot_start",
     *  description="Date of the patients first shot",
     *  minLength=10,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $shot_start;

    /**
     * @SWG\Property(
     *  example="2015-10-03",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="maint_start",
     *  description="Date patient started maintenance",
     *  minLength=10,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $maint_start;

    /**
     * @SWG\Property(
     *  example="+1-503-379-0110",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $phone;

    /**
     * @SWG\Property(
     *  example="+1-503-379-0112",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $sms_phone;

    /**
     * @SWG\Property(
     *  example="+1-503-379-0116",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $fax;

    /**
     * @SWG\Property(
     *  example="9954 SW Arctic Ave.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="addr1",
     *  description="Line 1 of the patient address",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $addr1;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="addr2",
     *  description="Line 2 of the patient address",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $addr2;

    /**
     * @SWG\Property(
     *  example="Beaverton",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="city",
     *  description="Patient's city",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $city;

    /**
     * @SWG\Property(
     *  example="Oregon",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="state",
     *  description="Patient's state",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $state;

    /**
     * @SWG\Property(
     *  example="97005",
     *  pattern="^[0-9a-zA-Z-]{0,45}$",
     *  title="zip",
     *  description="Patient's zip",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $zip;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="province",
     *  description="Patient's province",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $province;

    /**
     * @SWG\Property(
     *  example="USA",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="country",
     *  description="Patient's country",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $country;

    /**
     * @SWG\Property(
     *  example="John Doe",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="displayname",
     *  description="Patient displayname",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $displayname;

    /**
     * @SWG\Property(
     *  example="something.jpg",
     *  pattern="^([a-zA-Z0-1_-]+){1}\.[a-zA-Z0-1]+$",
     *  title="face_image",
     *  description="Future use.",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $face_path;

    /**
     * @SWG\Property(
     *  example="A12273",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="chart",
     *  description="Patient's chart number",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $chart;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="lock_id",
     *  description="Id of lock from the padlock table",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $lock_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="user_id",
     *  description="Id of user from the user table",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $user_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="provider_id",
     *  description="Id of provider from the provider table",
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
     *  example="Patient forgot epipen last visit.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="patient_notes",
     *  description="Free form notes attached to the patient record.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $patient_notes;

    /**
     * @SWG\Property(
     *  example="Patient prefers Nurse Betty.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="login_notes",
     *  description="Free form notes attached to the patient record. This field is not HIPPA compliant",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $login_notes;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="phone_note",
     *  description="?????",
     *  minLength=0,
     *  maxLength=400,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $phone_note;

    /**
     * @SWG\Property(
     *  example="johndoe@someplace.com",
     *  pattern="^[a-zA-Z0-9-.]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9]+$",
     *  title="email",
     *  description="Patient's email address (used for patient contact)",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="archived",
     *  description="Is patient accessible in the system (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $archived;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="box1",
     *  description="Value associated with box 1 custom tracking field (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $box1;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="box2",
     *  description="Value associated with box 2 custom tracking field (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="",
     * )
     *
     * @var string
     */
    private $box2;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="box3",
     *  description="Value associated with box 3 custom tracking field (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="",
     * )
     *
     * @var string
     */
    private $box3;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="pid_segment",
     *  description="Patients HL7 PID segment if used by interface.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $pid_segment;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="pv1_segment",
     *  description="Patients HL7 PV1 segment if used by interface.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $pv1_segment;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[mMfF]{1}$",
     *  title="gender",
     *  description="Gender of patient (future use)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $gender;

    /**
     * @SWG\Property(
     *  example="123456789",
     *  pattern="^[0-9]{9}$",
     *  title="ssn",
     *  description="Patient's social security number",
     *  minLength=9,
     *  maxLength=9,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $ssn;

    /**
     * @SWG\Property(
     *  example="+1-503-379-0114",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $e_contact_num;

    /**
     * @SWG\Property(
     *  example="Suzie Q",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="e_contact",
     *  description="Emergency contact name",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $e_contact;

    /**
     * @SWG\Property(
     *  example="contact_by",
     *  pattern="^((email)|(sms)|(both)|(none))$",
     *  title="contact_by",
     *  description="Patients preferred contact method",
     *  minLength=0,
     *  maxLength=5,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $contact_by;

    /**
     * @SWG\Property(
     *  example="list_optin",
     *  pattern="^[0-9]+(,[0-9]+)*$",
     *  title="list_optin",
     *  description="CSV list of patient selected message_id's to subscribe to.",
     *  minLength=0,
     *  maxLength=65,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $list_optin;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="review_by",
     *  description="?????",
     *  minLength=0,
     *  maxLength=150,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $review_by;

    /**
     * Relationships
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_id');
    }
    public function contactLogs()
    {
        return $this->hasMany('App\Models\ContactLog', 'patient_id');
    }
    public function flags()
    {
        return $this->hasMany('App\Models\Flags', 'patient_id');
    }
    public function logins()
    {
        return $this->hasMany('App\Models\Login', 'patient_id');
    }
    public function loginState()
    {
        return $this->hasMany('App\Models\Login', 'patient_id')
                  ->orderBy('loginTime', 'desc')
                  ->where(DB::raw('date(loginTime)=date(now())'))
                  ->where('state', '<>', 0)
                  ->limit(1);
    }
    public function patientQuestionnaires()
    {
        return $this->hasMany('App\Models\PatientQuestionnaire', 'patient_id');
    }

    public function Questionnaires()
    {
        return $this->hasManyThrough('App\Models\Questionnaire', 'App\Models\PatientQuestionnaire', 'patient_id', 'questionnaire_id', 'patient_id', 'questionnaire_id');
    }
    public function locks()
    {
        return $this->hasManyThrough('App\Models\Padlock', 'App\Models\PatientConfig', 'patient_id', 'lock_id', 'patient_id', 'lock_id');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image', 'face_image_id', 'image_id');
    }

    public function codes()
    {
        return $this->hasManyThrough('App\Models\Code', 'App\Models\PatientCode', 'patient_id', 'flag_id', 'patient_id', 'flag_id');
    }

    public function prescriptions()
    {
        return $this->hasMany('App\Models\Prescription', 'patient_id');
    }
    public function compounds()
    {
        return $this->hasManyThrough('App\Models\Compound', 'App\Models\Prescription', 'patient_id', 'rx_id', 'patient_id', 'prescription_id');
    }
    public function trackingConfigs()
    {
        return $this->hasMany('App\Models\TrackingConfig', 'patient_id');
    }
    public function trackingValues()
    {
        return $this->hasMany('App\Models\TrackingValue', 'patient_id');
    }
    public function skintests()
    {
        return $this->hasMany('App\Models\Skintest', 'patient_id');
    }
    public function configs()
    {
        return $this->hasManyIfSchema('1.04', 'App\Models\PatientConfig', 'patient_id');
    }

    /**
     * Mutators to alter data before saving to DB.
     */
    public function setPatientNotesAttribute($value)
    {
        $this->attributes['patient_notes'] = preg_replace('/[(\r\n)(\n\r)]/', '<enter>', $value);
    }

    public function setLoginNotesAttribute($value)
    {
        $this->attributes['login_notes'] = preg_replace('/[(\r\n)(\n\r)]/', '<enter>', $value);
    }

    public function setPhoneNotesAttribute($value)
    {
        $this->attributes['phoneNotes'] = preg_replace('/[(\r\n)(\n\r)]/', '<enter>', $value);
    }

    public function setSmsPhoneAttribute($value)
    {
        $this->attributes['smsphone'] = $this->fixPhone($value);
    }

    public function setEContactNumAttribute($value)
    {
        $this->attributes['eContactNum'] = $this->fixPhone($value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $this->fixPhone($value);
    }

    public function setGenderAttribute($value)
    {
        $this->attributes['gender'] = strtoupper($value);
    }

    public function setBox1Attribute($value)
    {
        $this->attributes['box1'] = strtoupper($value);
    }

    public function setBox2Attribute($value)
    {
        $this->attributes['box2'] = strtoupper($value);
    }

    public function setBox3Attribute($value)
    {
        $this->attributes['box3'] = strtoupper($value);
    }

    public function setContactbyAttribute($value)
    {
        switch (strtoupper($value)) {
            case "SMS":
                $value = "T,F,F";
                break;
            case "EMAIL":
                $value = "F,T,F";
                break;
            case "BOTH":
                $value = "F,F,T";
                break;
            case "NO CONTACT":
                $value = "F,F,F";
                break;
            default:
                $value = null;
        }
        $this->attributes['contactby'] = $value;
    }

    /**
     * Accessors.
     */
    // Created: JVP-Feb-2018, Modified: _____by______
    public function getReportnameAttribute()
    {
        //return "JOE";
        return $this->attributes['lastname'].", ".$this->attributes['firstname']." ".$this->attributes['mi']." (".$this->attributes['chart'].")";
    }

    /**
     * Helpers
     */
    public function getLockOnBox3()
    {
        return $this['box3'] === 'T' && Config::getLockOnBox3();
    }

    // XA: Skin test with no subsequent RX
    // Created: JVP-Feb-2018, Modified: _____by______
    public function getRecentprescriptionAttribute()
    {
        $rxs = $this->prescriptions
        ->where('strikethrough', '<>', 'T')
        ->max('timestamp');
        return $rxs;
    }
    public function getIdcodeAttribute($value)
    {
        if ($value != '' && !is_null($value)) {
            return '****';
        } else {
            return null;
        }
    }

    public function getPatientNotesAttribute($value)
    {
        $value = preg_replace('/<enter>/i', "\r\n", $value);
        $value = preg_replace('/\|\|\|POP/', '', $value);
        $value = preg_replace('/\|\|\|NOPOP/', '', $value);
        return $value;
    }

    public function getPatientIdAttribute($value)
    {
        return (int)$value;
    }

    public function getLoginNotesAttribute($value)
    {
        return preg_replace('/<enter>/', "\r\n", $value);
    }

    public function getPhonenotesAttribute($value)
    {
        return preg_replace('/<enter>/', "\r\n", $value);
    }

    public function getContactbyAttribute($value)
    {
        switch ($value) {
            case "T,F,F":
                $value = "SMS";
                break;
            case "F,T,F":
                $value = "Email";
                break;
            case "F,F,T":
                $value = "Both";
                break;
            case "F,F,F":
                $value = "No contact";
                break;
            default:
                $value = null;
        }
        return $value;
    }

    public function getReviewbyAttribute($value)
    {
        if ($value != '' && !is_null($value)) {
            $ReviewArray = array();
            //ensure there is no trailing comma
            $value = rtrim($value, ',');
            $ReviewDetails = explode(',', $value);
            //build an object out of each comma separated user_id and date
            //which are now stored in the ReviewArray
            for ($i=0; $i < sizeof($ReviewDetails); $i=$i+2) {
                try {
                    $User = User::findOrfail($ReviewDetails[$i]);
                    $Username = $User->displayname;
                } catch (ModelNotFoundException $e) {
                    $Username = null;
                }

                $Review = app()->make('stdClass');
                $Review->user_id = $ReviewDetails[$i];
                $Review->username = $Username;
                $Review->date = $ReviewDetails[$i+1];
                array_push($ReviewArray, $Review);
            }
            return $ReviewArray;
        } else {
            return null;
        }
    }

    public function markDeleted($RequestOptions)
    {
        $this->setArchivedAttribute('T');
        $this->save();

        return $this;
    }

    /**
     * An array of fields that need to be converted from one name
     * in the database (array index) to another in the json object (value).
     */
    public static $DBtoRestConversion = array(
        'maintStart' => 'maintenance_start',
        'eContactNum' => 'e_contact_number',
        'phoneNotes' => 'phone_notes',
        'listOptIn' => 'list_opt_in',
        'shotStart' => 'shot_start',
        'faceimage' => 'face_image',
        'eContact' => 'e_contact',
        'smsphone' => 'sms_phone',
        'reviewby' => 'review_by',
        'contactby' => 'contact_by',
        'idcode' => 'id_code'
    );

    public $Messages = [
        'firstname.distinct_patient' => 'The combination of first, last, middle, date of birth, and chart must be unique in the system.',
        'pv1_segment.p_v1' => 'The :attribute must be empty or a string that starts with \'PV1\'.',
        'pid_segment.p_i_d' => 'The :attribute must be empty or a string that starts with \'PID\'.',
        '*.phone' => 'The :attribute is not a valid phone number format. Should be +1-000-000-0000',
        'dob' => 'The correct date format is YYYY-MM-DD'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $mi = isset($data['mi']) ? $data['mi'] : null;
        $dob = isset($data['dob']) ? $data['dob'] : null;
        $chart = isset($data['chart']) ? $data['chart'] : null;

        if (isset($data['phone'])) {
            $Phone = $data['phone'];
        } else {
            $Patient= Patient::find($id);
            if (!is_null($Patient)) {
                $Patient = $Patient->toArray();
                $Phone=isset($Patient['phone']) ? $Patient['phone'] : null;
            } else {
                $Phone = null;
            }
        }
        $LastFour = is_null($Phone) ? null : substr($Phone, strlen($Phone) - 4, strlen($Phone));

        $Rules = [
            'firstname' => array('standard', 'between:0,45', 'distinctPatient:'.$id.','.$firstname.','.$mi.','.$lastname.','.$dob.','.$chart),
            'lastname' => array('standard', 'between:0,45'),
            'mi' => array('standard', 'between:0,45'),
            'dob' => array('date_format:Y-m-d'),
            'archived' => array('in:t,T,f,F'),
            'provider_id' => array('exists:provider,provider_id,deleted,F'),
            'display_name' => array('standard', 'between:0,45'),
            'user_id' => array('exists:user,user_id,deleted,F'),
            'contact_by' => array('in:sms,email,both,no contact'),
            'external_id' => array('standard', 'between:0,32'),
            'review_by' => array('standard', 'between:0,150'),
            'face_image' => array('standard', 'between:0,45'),
            'e_contact' => array('standard', 'between:0,45'),
            'list_opt_in' => array('optins', 'between:0,65'),
            'phone_notes' => array('notes', 'between:0,400'),
            'province' => array('standard', 'between:0,50'),
            'country' => array('standard', 'between:0,50'),
            'addr1' => array('standard', 'between:0,100'),
            'addr2' => array('standard', 'between:0,100'),
            'city' => array('standard', 'between:0,100'),
            'state' => array('standard', 'between:0,45'),
            'maint_start' => array('date_format:Y-m-d'),
            'lock_id' => array('exists:padlock,lock_id'),
            'shot_start' => array('date_format:Y-m-d'),
            'bot_check' => array('date_format:Y-m-d'),
            'chart' => array('standard','between:0,45'),
            'id_code' => array(
                'regex:/^[0-9]{4}$/',
                function ($attribute, $value, $fail) use ($LastFour) {
                    if ($value == $LastFour) {
                        $fail('For security reasons, the pin/idcode may not be the same as the last four digits of your phone number.');
                    }
                },
                function ($attribute, $value, $fail) use ($LastFour, $id) {
                    $Query = Patient::where('idcode', $value)
                        ->whereRaw("right(phone,4)='".$LastFour."'");
                    if (!is_null($id)) {
                        $Query = $Query->where('patient_id', '<>', $id);
                    }
                    $Count = $Query->count();
                    if ($Count > 0) {
                        $fail('This PIN is already in use. Please try a different PIN.');
                    }
                },
            ),
            'ssn' => array('regex:/^[0-9]{9}$/'),
            'patient_notes' => array('notes'),
            'e_contact_num' => array('phone'),
            'pv1_segment' => array('PV1'),
            'pid_segment' => array('PID'),
            'gender' => array('in:m,M,f,F'),
            'box1' => array('in:t,T,f,F'),
            'box2' => array('in:t,T,f,F'),
            'box3' => array('in:t,T,f,F'),
            'sms_phone' => array('phone'),
            'phone' => array('phone'),
            'zip' => array('zipcode'),
            'email' => array('email'),
            'fax' => array('phone'),
            'waitlist_id' => array('exists:login,login_id')
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['firstname','lastname','mi','dob','chart'], 'required', function () use ($id) {
            return is_null($id);
        });

        $Validator->sometimes(['chart'], 'unique:patient,chart,'.$id.',patient_id,archived,F', function () {
            //require unique unless the reqUniqueChart? is explicitly false
            return Config::where('section', 'prefs')->where('name', 'reqUniqueChart?')->where('value', 'F')->count() == 0;
        });
        return $Validator;
    }
}
