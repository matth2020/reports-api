<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Postpone.
 *
 *
 * @SWG\Definition(
 *   definition="Postpone",
 * )
 */

class Postpone extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'postpone';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'postpone_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Relationships
     */
    public function compound1()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id1', 'compound_id');
    }
    public function compound2()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id1', 'compound_id');
    }
    public function compound3()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id1', 'compound_id');
    }
    public function compound4()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id1', 'compound_id');
    }
    public function compound5()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id1', 'compound_id');
    }
    public function compound6()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id1', 'compound_id');
    }
    public function compound7()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id1', 'compound_id');
    }
    public function compound8()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id8', 'compound_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * An array of fields that need to be converted from one name
     * in the database (array index) to another in the json object (value).
     */
    public static $DBtoRestConversion = array(
        'postpone_id' => 'mix_queue_id',
        'labelPrinted' => 'label_printed',
        'postponeDate' => 'date_queued'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [];
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
