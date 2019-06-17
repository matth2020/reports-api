<?php

namespace App\Models;

/**
 * Class Provider.
 *
 *
 * @SWG\Definition(
 *   definition="Provider",
 *   required={"first","last","display_name","display_name_short"}
 * )
 */
class Provider extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'provider';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'provider_id';

    /**
     * The attributes that are hidden from public view.
     *
     * @var array
     */
    protected $hidden = ['external_id'];

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
     *  title="provider_id",
     *  description="Id of the provider from the database.",
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
     *  example="Mark",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="first",
     *  description="Provider firstname",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $first;

    /**
     * @SWG\Property(
     *  example="Smith",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="last",
     *  description="Provider lastname",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $last;

    /**
     * @SWG\Property(
     *  example="J",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="mi",
     *  description="Provider middle initial",
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
     *  example="Jr",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="suffix",
     *  description="Provider's suffix",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $suffix;

    /**
     * @SWG\Property(
     *  example="John Smith",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="display_name",
     *  description="Provider display name",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $display_name;

    /**
     * @SWG\Property(
     *  example="John S",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="display_name_short",
     *  description="Short version of provider display name",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $display_name_short;

    /**
     * @SWG\Property(
     *  example="+1-503-379-0110",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default="",
     * )
     * @var string
     */
    private $phone;

    /**
     * @SWG\Property(
     *  example="+1-503-379-0116",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default="",
     * )
     * @var string
     */
    private $fax;

    /**
     * @SWG\Property(
     *  example="johndoe@someplace.com",
     *  pattern="^[a-zA-Z0-9-.]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9]+$",
     *  title="email",
     *  description="Provider's email address (used for patient contact)",
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
     *  example="9954 SW Arctic Ave.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="addr1",
     *  description="Line 1 of the provider address",
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
     *  description="Line 2 of the provider address",
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
     *  description="Provider's city",
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
     *  description="Provider's state",
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
     *  description="Provider's zip",
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
     *  example="USA",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="country",
     *  description="Provider's country",
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
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="province",
     *  description="Provider's province",
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
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="provider_notes",
     *  description="Provider's notes",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $provider_notes;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[0-9]*$",
     *  title="rate",
     *  description="Provider's bill rate (future use)",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $rate;

    /**
     * @SWG\Property(
     *  example="John_Smith.jpg",
     *  pattern="^([a-zA-Z0-1_-]+){1}\.[a-zA-Z0-1]+$",
     *  title="face_image",
     *  description="Image file for provider's picture (future use)",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $face_image;

    /**
     * @SWG\Property(
     *  example="184QZ098",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="license_number",
     *  description="Provider's license number",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $license_number;

    /**
     * @SWG\Property(
     *  example="09AC9213",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="provider_number",
     *  description="Provider's number",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $provider_number;

    /**
     * @SWG\Property(
     *  example="0sf789er213",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="dea_number",
     *  description="Provider's DEA number",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $dea_number;

    /**
     * @SWG\Property(
     *  example="Jane R",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="contact",
     *  description="General contact for provider",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $contact;

    /**
     * @SWG\Property(
     *  example="+1-503-379-3463",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default="",
     * )
     * @var string
     */
    private $contact_phone;

    /**
     * @SWG\Property(
     *  example="wer2398324k",
     *  pattern="^[a-zA-Z0-9-\./\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="account",
     *  description="Provider's account",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $account;

    /**
     * @SWG\Property(
     *  example="FF6592",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="emr_code",
     *  description="Provider's EMR assigned code",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $emr_code;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="general",
     *  description="General notes/codes field for provider. Potentially customer specific",
     *  minLength=0,
     *  maxLength=150,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $general;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Is the provider deleted in the system (t/f)",
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
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="non_xtract_provider",
     *  description="Is provider an external provider or are they using the xtract systems.",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $non_xtract_provider;

    /**
     * Relationships
     */
    public function patients()
    {
        return $this->hasMany('App\Models\Patient', 'provider_id');
    }
    public function prescriptions()
    {
        return $this->hasMany('App\Models\Prescription', 'provider_id');
    }
    public function providerConfigs()
    {
        return $this->hasMany('App\Models\Profile', 'provider_id');
    }
    public function providerDefs()
    {
        return $this->hasMany('App\Models\ProviderDef', 'provider_id');
    }
    public function skintests()
    {
        return $this->hasMany('App\Models\Skintest', 'provider_id');
    }

    /**
     * Mutators to alter data before saving to DB.
     */
    public function setProviderNotesAttribute($value)
    {
        $this->attributes['provider_notes'] = preg_replace('/[(\r\n)(\n\r)]/', '<enter>', $value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $this->fixPhone($value);
    }

    public function setContactPhoneAttribute($value)
    {
        $this->attributes['contactPhone'] = $this->fixPhone($value);
    }

    public function setFaxAttribute($value)
    {
        $this->attributes['fax'] = $this->fixPhone($value);
    }

    /**
     * Accessor.
     */
    public function getProviderNotesAttribute($value)
    {
        return preg_replace('/<enter>/', '\r\n', $value);
    }

    public function getProviderIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'displaynameS' => 'display_name_short',
        'nonXtract' => 'non_xtract_provider',
        'licensenumber' => 'license_number',
        'providerNum' => 'provider_number',
        'contactPhone' => 'contact_phone',
        'contactName' => 'contact_name',
        'displayname' => 'display_name',
        'faceimage' => 'face_image',
        'deaNum' => 'dea_number',
        'emrCode' => 'emr_code'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'display_name_short' => array('standard', 'between:0,45', 'unique:provider,displaynames,'.$id.',provider_id,deleted,F'),
            'display_name' => array('standard', 'between:0,45', 'unique:provider,displayname,'.$id.',provider_id,deleted,F'),
            'non_xtract_provider' => array('in:t,T,f,F'),
            'license_number' => array('alpha_dash', 'between:0,45'),
            'provider_number' => array('alpha_dash', 'between:0,45'),
            'contact_name' => array('standard', 'between:0,100'),
            'external_id' => array('standard', 'between:0,32'),
            'face_image' => array('standard', 'between:0,45'),
            'emr_code' => array('alpha_dash', 'between:0,11'),
            'account' => array('alpha_dash', 'between:0,45'),
            'dea_number' => array('alpha_dash', 'between:0,45'),
            'country' => array('standard', 'between:0,50'),
            'suffix' => array('standard', 'between:0,45'),
            'first' => array('standard', 'between:0,45'),
            'addr1' => array('standard', 'between:0,45'),
            'addr2' => array('standard', 'between:0,45'),
            'state' => array('standard', 'between:0,45'),
            'general' => array('notes', 'between:0,150'),
            'city' => array('standard', 'between:0,45'),
            'last' => array('standard', 'between:0,45'),
            'mi' => array('standard', 'between:0,45'),
            'rate' => array('regex:/^[0-9]{0,11}$/'),
            'provider_notes' => array('notes'),
            'contact_phone' => array('phone'),
            'deleted' => array('in:t,T,f,F'),
            'zip' => array('zipcode'),
            'phone' => array('phone'),
            'email' => array('email'),
            'fax' => array('phone')
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['display_name','display_name_short','non_xtract_provider'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
