<?php

namespace App\Models;

/**
 * Class PurchaseOrder.
 *
 *
 * @SWG\Definition(
 *   definition="PurchaseOrder",
 * )
 */
class PurchaseOrder extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'purchase_order';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'purchase_order_id';

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
     *  title="purchase_order_id",
     *  description="Id of the purchase_order from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $purchase_order_id;

    /**
     * @SWG\Property(
     *  pattern="",
     *  title="set_orders",
     *  description="An array of set_order objects",
     *  type="array",
     *  @SWG\Items(ref="#/definitions/TreatmentSet")
     * )
     * @var details   */
    private $set_orders;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="bill_to",
     *  description="Id of address to bill to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $bill_to;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="ship_to",
     *  description="Id of address to ship to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $ship_to;

    /**
     * @SWG\Property(
     *  example="custom notes",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="note",
     *  description="Notes attached to the purchase order",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $note;

    /**
     * @SWG\Property(
     *  example="2017-08-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="created_at",
     *  description="The date on which the purchase_order was created.",
     *  minLength=10,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $created_at;

    /**
     * Relationships
     */
    public function treatmentSets()
    {
        return $this->hasMany('App\Models\TreatmentSet', 'purchase_order_id');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\PurchaseOrderStatuses', 'status_id', 'purchase_order_status_id');
    }
    public function queueState()
    {
        //if any sub treatment sets are queued the order is queued
        $this->queue_state = 'not queued';
        foreach ($this->treatmentSets as $TreatmentSet) {
            $TreatmentSet->compoundDetails();
            if ($TreatmentSet->queue_state === 'queued') {
                $this->queue_state = 'queued';
                return $this;
            }
        }
        return $this;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    public $Messages = [
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'account_id' => 'exists:account,account_id,archived,F',
            'bill_to' => 'exists:address,address_id,archived,F',
            'ship_to' => 'exists:address,address_id,archived,F',
            'status_id' => 'exists:purchase_order_status,purchase_order_status_id',
            'bill_to_name' => ['standard','between:0,46'],
            'bill_to_phone' => 'phone',
            'ship_to_name' => ['standard','between:0,46'],
            'ship_to_phone' => 'phone',
            'note' => 'standard'
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['account_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
