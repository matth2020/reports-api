<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Address.
 *
 *
 * @SWG\Definition(
 *   definition="Address"
 * )
 */
class Address extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'address';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'address_id';

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
    protected $hidden = ['archived'];

    /**
    * @SWG\Property(
    *  example="7",
    *  title="address_id",
    *  description="Id of the address object from the database",
    *  type="integer",
    *  default="",
    * )
    * @var int
    */
    private $address_id;

    /**
    * @SWG\Property(
    *  example="12345 somewhere st",
    *  title="address_line_1",
    *  description="",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $address_line_1;

    /**
    * @SWG\Property(
    *  example="appt 2",
    *  title="address_line_2",
    *  description="",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $address_line_2;

    /**
    * @SWG\Property(
    *  example="Portland",
    *  title="city",
    *  description="",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $city;

    /**
    * @SWG\Property(
    *  example="OR",
    *  title="state",
    *  description="",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $state;

    /**
    * @SWG\Property(
    *  example="97229",
    *  title="zip",
    *  description="",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $zip;

    /**
    * @SWG\Property(
    *  example="",
    *  title="province",
    *  description="",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $province;

    /**
    * @SWG\Property(
    *  example="USA",
    *  title="country",
    *  description="",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $country;

    /**
     * relationships
     */
    public function patientBilling()
    {
        return $this->hasMany('App\Models\Patient', 'bill_to', 'address_id');
    }
    public function patientShipping()
    {
        return $this->hasMany('App\Models\Patient', 'ship_to', 'address_id');
    }
    public function patientPrimary()
    {
        return $this->hasMany('App\Models\Patient', 'address_id', 'address_id');
    }
    public function providerPrimary()
    {
        return $this->hasMany('App\Models\Provider', 'address_id', 'address_id');
    }
    public function clinicPrimary()
    {
        return $this->hasMany('App\Models\Clinic', 'address_id', 'address_id');
    }
    public function accountBilling()
    {
        return $this->hasMany('App\Models\Account', 'bill_to', 'address_id');
    }
    public function accountShipping()
    {
        return $this->hasMany('App\Models\Account', 'ship_to', 'address_id');
    }
    public function accountPrimary()
    {
        return $this->hasMany('App\Models\Account', 'address_id', 'address_id');
    }
    public function purchaseOrderBilling()
    {
        return $this->hasMany('App\Models\PurchaseOrder', 'bill_to', 'address_id');
    }
    public function purchaseOrderShipping()
    {
        return $this->hasMany('App\Models\PurchaseOrder', 'ship_to', 'address_id');
    }
    /**
     * helpers
     */
    public function markDeleted($RequestOptions)
    {
        // if anything is linking to the row, we cant delete it so we should just
        // archive it but if nothing has ever pointed to it, its safe to delete

        if ($this->patientBilling()->count() === 0 &&
            $this->patientShipping()->count() === 0 &&
            $this->patientPrimary()->count() === 0 &&
            $this->providerPrimary()->count() === 0 &&
            $this->clinicPrimary()->count() === 0 &&
            $this->accountBilling()->count() === 0 &&
            $this->accountShipping()->count() === 0 &&
            $this->accountPrimary()->count() === 0 &&
            $this->purchaseOrderBilling()->count() === 0 &&
            $this->purchaseOrderShipping()->count() === 0
        ) {
            $this->delete();
        } else {
            $this->archived = 'T';
            $this->save();
        }
        return $this;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array();

    protected function makeValidationRules($id, $data = null)
    {
        // Keep validation intentially loose... if it fits in the db
        // we should accept it... some log is better than no log.
        // We will trim strings if necessary in the controller.
        $Rules = [
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
