<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Clinic.
 *
 *
 * @SWG\Definition(
 *   required={"name","non_xtract_clinic"},
 * )
 */
class Clinic extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'clinic';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'clinic_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    protected $hidden = ['external_id'];

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="clinic_id",
     *  description="Id of the clinic from the database.",
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
     * @SWG\Property(
     *  example="Xtract Solutions",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Line 1 of clinic name",
     *  minLength=0,
     *  maxLength=150,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="Allergy and Asthma",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name2",
     *  description="Line 2 of clinic name",
     *  minLength=0,
     *  maxLength=150,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name2;

    /**
     * @SWG\Property(
     *  example="Xtract",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="abbreviation",
     *  description="Abbreviated clinic name",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $abbreviation;

    /**
     * @SWG\Property(
     *  example="James Baker",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="contact",
     *  description="Name of primary contact",
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
     *  example="9954 SW Arctic Ave.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="addr1",
     *  description="Line 1 of the clinic address",
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
     *  description="Line 2 of the clinic address",
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
     *  description="City of clinic",
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
     *  description="State of clinic",
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
     *  description="ZIP code of clinic",
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
     *  description="Province of clinic",
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
     *  pattern="^[a-zA-Z0-9-\\./\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="country",
     *  description="Country of clinic",
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
     *  example="+1-503-379-0110",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default=""
     * )
     * @var string
     */
    private $phone;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default=""
     * )
     * @var string
     */
    private $phone2;

    /**
     * @SWG\Property(
     *  example="+1-503-715-1378",
     *  pattern="\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
     *      2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
     *      4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$",
     *  type={"string","null"},
     *  default=""
     * )
     * @var string
     */
    private $fax;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Status of clinic entry in the system",
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
     *  title="non_xtract_clinic",
     *  description="Clinic is using Xtract software (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $non_xtract_clinic;

    /**
     * Relationships
     */
    public function logins()
    {
        return $this->hasMany('App\Models\Login', 'clinic_id');
    }
    public function prescriptions()
    {
        return $this->hasMany('App\Models\Prescription', 'clinic_id');
    }

    /**
     * Mutators to alter data before saving to DB.
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $this->fixPhone($value);
    }

    public function setPhone2Attribute($value)
    {
        $this->attributes['phone2'] = $this->fixPhone($value);
    }

    public function setFaxAttribute($value)
    {
        $this->attributes['fax'] = $this->fixPhone($value);
    }

    /**
     * Accessors
     */
    public function getClinicIdAttribute($value)
    {
        return (int)$value;
    }

    /**
     * An array of fields that need to be converted from one name
     * in the database (array index) to another in the json object (value).
     */
    public static $DBtoRestConversion = array(
        'nonXtract' => 'non_xtract_clinic'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => array('standard', 'between:0,150', 'unique:clinic,name,'.$id.',clinic_id,deleted,F'),
            'non_xtract_clinic' => array('in:t,T,f,F'),
            'abbreviation' => array('standard', 'between:0,45'),
            'province' => array('standard', 'between:0,45'),
            'contact' => array('standard', 'between:0,45'),
            'country' => array('standard', 'between:0,45'),
            'name2' => array('standard', 'between:0,150'),
            'state' => array('standard', 'between:0,45'),
            'addr1' => array('standard', 'between:0,45'),
            'addr2' => array('standard', 'between:0,45'),
            'city' => array('standard', 'between:0,45'),
            'deleted' => array('in:t,T,f,F'),
            'phone2' => array('phone', 'nullable'),
            'zip' => array('zipcode'),
            'phone' => array('phone'),
            'fax' => array('phone'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name','non_xtract_clinic'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
