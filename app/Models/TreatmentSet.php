<?php

namespace App\Models;

use DB;

/**
 * Class TreatmentSet.
 *
 *
 * @SWG\Definition(
 *   definition="TreatmentSet",
 * )
 */
class TreatmentSet extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'treatment_set';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'treatment_set_id';

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
     *  title="set_order_id",
     *  description="Id of the set_order from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $set_order_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="purchase_order_id",
     *  description="Id of the purchase_order that the set_order belongs to.",
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
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="patient_id",
     *  description="Id of the patient the set_order belongs to.",
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
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="provider_id",
     *  description="Id of the provider the set_order belongs to.",
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
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="prescription_id",
     *  description="Id of the prescription the set_order belongs to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $prescription_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="clinic_id",
     *  description="Id of the clinic the set_order belongs to.",
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
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="priority",
     *  description="The priority of the set order (must be a value in the list returned from config where name='priority_names')",
     *  minLength=0,
     *  maxLength=11,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $priority;

    /**
     * @SWG\Property(
     *  example="2017-08-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="created_at",
     *  description="The date on which the set_order was created.",
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
    public function status()
    {
        return $this->belongsTo('App\Models\TreatmentSetStatus', 'status_id', 'treatment_set_status_id');
    }
    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder', 'purchase_order_id');
    }
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id');
    }
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id');
    }
    public function prescription()
    {
        return $this->belongsTo('App\Models\Prescription', 'prescription_id');
    }
    public function compounds()
    {
        return $this->hasMany('App\Models\Compound', 'treatment_set_id');
    }

    /*
     * Helper functions
     */

    public function delete()
    {
        DB::transaction(
            function () {
                // delete the vials first, to avoid constraint violations
                foreach ($this->compounds as $Compound) {
                    foreach ($Compound->vials as $Vial) {
                        $Vial->delete();
                    }
                }

                // delete the dosing and compound rows
                foreach ($this->compounds as $Compound) {
                    foreach ($Compound->vials as $Vial) {
                        if ($Vial->dosing) {
                            $Vial->dosing->delete();
                        }
                    }
                    $Compound->delete();
                }

                // and finally the treatment set
                parent::delete();
            },
            3
        );
    }

    public function compoundDetails()
    {
        $dilutions = [];
        $Compounds = [];
        foreach ($this->compounds as $compound) {
            if (!in_array($compound, $Compounds)) {
                array_push($Compounds, $compound);
            }
        }

        $queueState = false;
        foreach ($Compounds as $compound) {
            array_push($dilutions, $compound->dilution);
            foreach ($compound->vials as $vial) {
                if ($vial->postponed=='T') {
                    $queueState = true;
                    break;
                }
            }
        }
        $this->queue_state = $queueState ? 'queued' : 'not queued';

        if (isset($Compounds[0])) {
            $this->size = $Compounds[0]->size;
            if (isset($Compounds[0]->vials[0])) {
                $this->tray_location = $Compounds[0]->vials[0]->traylocation;
            }
            $this->name = $Compounds[0]->name;
            $this->note = $Compounds[0]->compound_note;
        }
        return $this->dilutions = $dilutions;
    }
    public function extracts($includeIds = false)
    {
        $this->dosings = isset($this->compounds[0]) ? $this->compounds[0]->dosings : [];
        foreach ($this->dosings as $Extract) {
            unset($Extract->clickOrder);
            unset($Extract->weight);
            $Extract->name = $Extract->extract->name;
            unset($Extract->extract);
            if (!$includeIds) {
                unset($Extract->dosing_id);
                unset($Extract->compound_id);
                unset($Extract->prescription_id);
            }
        }
        return $this;
    }
    public function rxDetails()
    {
        return $this->prescription->name();
    }
    public function sortDilutions()
    {
        // proper sorting
        if ($this->prescription->profile) {
            $NumOrder = $this->prescription->profile->numOrder;
        } else {
            $NumOrder = 1; //assume normal ordering
        }
        unset($this->prescription->profile);
        $dilutions = $this->dilutions;
        // here is were we need to sort the incoming dilutions request
        if ($NumOrder = 1) {
            //sort ascending for normal bottle numbering
            usort($dilutions, [$this, "sortBottlesAsc"]);
        } else {
            //sort descending for reverse bottle numbering
            usort($dilutions, [$this, "sortBottlesDesc"]);
        }
        $this->dilutions = $dilutions;
        return $this;
    }
    public function finalize()
    {
        $this->compoundDetails();
        $this->extracts();
        $this->rxDetails();
        $this->sortDilutions();
        unset($this->compounds);
        return $this;
    }
    protected function sortBottlesAsc($bottleA, $bottleB)
    {
        if ($bottleA == $bottleB) {
            return 0;
        }
        return ($bottleA < $bottleB) ? -1 : 1;
    }

    protected function sortBottlesDesc($bottleA, $bottleB)
    {
        if ($bottleA == $bottleB) {
            return 0;
        }
        return ($bottleA < $bottleB) ? 1 : -1;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'treatment_set_id' => 'set_order_id',
        'compounds' => 'vials'
    );

    public $Messages = [
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'transaction' => ['integer','distinct:treatment_set,transaction'],
            'purchase_order_id' => 'exists:purchase_order,purchase_order_id',
            'patient_id' => 'exists:patient,patient_id,archived,F',
            'provider_id' => function ($attribute, $value, $fail) use ($id) {
                $GoodProvider = Provider::where($attribute, $value)->where('deleted', 'F')->count() > 0;
                if (!is_null($id)) {
                    // if id is not null then this is an update so see if the value is changing
                    $ChangedTs = TreatmentSet::where($attribute, '<>', $value)->where('treatment_set_id', $id)->count() > 0;
                } else {
                    // if its a new treatment_set row then its changed...
                    $ChangedTs = true;
                }

                if ($ChangedTs && !$GoodProvider) {
                    $fail('The selected '.$attribute.' must be a valid provider_id where deleted = F.');
                }
            },
            'prescription_id' => 'exists:prescription,prescription_id,strikethrough,F',
            'clinic_id' => 'exists:clinic,clinic_id,deleted,F',
            'priority' => ['standard','between:0,45'], //probably need something better here since I think specific priorities are allowed based on db config
            'source' => 'in:AUTO,API,XIS,XPS,XST',
            'status_id' => ['nullable','exists:treatment_set_status,treatment_set_status_id']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(
            ['patient_id','provider_id','prescription_id','clinic_id','source'],
            'required',
            function () use ($id) {
                return is_null($id);
            }
        );

        if (!is_null($id)) {
            $TreatmentSet = TreatmentSet::find($id);

            // not allowed to change these values

            $Validator->sometimes(
                ['transaction', 'purchase_order_id', 'prescription_id'],
                function ($attribute, $value, $fail) use ($TreatmentSet) {
                    if (!is_null($value) && $value !== $TreatmentSet[$attribute]) {
                        $fail('The ' . $attribute . ' cannot be updated.');
                    }
                },
                function () {
                    return true;
                }
            );
        }

        return $Validator;
    }
}
