<?php

namespace App\Models;

/**
 * Class Antigen_Extract.
 *
 *
 * @SWG\Definition(
 *   definition="Antigen_Extract",
 *   required={"name"}
 * )
 */
class Antigen_Extract extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'antigen_extract';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'extract_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Relationships
     */
    public function dosings()
    {
        return $this->hasMany('App\Models\Dosing', 'extract_id');
    }
    public function providerDefs()
    {
        return $this->hasMany('App\Models\ProviderDef', 'extract_id');
    }
    public function unitType()
    {
        return $this->belongsTo('App\Models\Units', 'units', 'units_id');
    }
    public function compatibilityClass()
    {
        return $this->belongsTo('App\Models\CompatibilityClass', 'compatibility_class_id');
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        // these actually belong to the extract table but since antigen
        // and extract are "blury" they need to be here.
        'latinname' => 'latin_name',
        'visible' => 'is_visible',
        'percentGlycerin' => 'percent_glycerin',
        'percentPhenol' => 'percent_phenol',
        'percentHSA' => 'percent_hsa',
        'specificgravity' => 'specific_gravity',
        'outdatealert' => 'outdate_alert',
        'imagefile' => 'image_file',
        'isDiluent' => 'is_diluent',
        'topline' => 'icon_top_line',
        'firstline' => 'icon_middle_line',
        'secondline' => 'icon_bottom_line',
        'seasonStart' => 'season_start',
        'seasonEnd' => 'season_end',
        'compatibilityClass' => 'compatibility_class',
        'sub' => 'substitutes'
    );

    public $Messages = [
        'season' => 'Season must be of the form [*]m/d, where m = month and d = day.'
    ];

    /**
     * Accessors
     */
    public function getSubAttribute($value)
    {
        if ($value === '' || is_null($value)) {
            return null;
        } else {
            $Array = explode(',', $value);
            return array_map(function ($item) {
                return ['extract_id' => intval($item)];
            }, $Array);
        }
    }
    public function getForTestOnlyAttribute($value)
    {
        if ($value === '' || is_null($value)) {
            return 'F';
        } else {
            return $value;
        }
    }
    public function getVisibleAttribute($value)
    {
        if ($value === '' || is_null($value)) {
            return 'T';
        } else {
            return $value;
        }
    }
    /**
     * Mutators
     */
    public function setSubAttribute($value)
    {
        if (is_string($value)) {
            // this handles the case they send it back as a csv as its in the
            // db. Shouldn't happen but no reason not to allow it. Not properly
            // validated though...
            return $value;
        } elseif (is_array($value)) {
            $Subs = [];
            foreach ($value as $item) {
                if (isset($item['extract_id'])) {
                    array_push($Subs, $item['extract_id']);
                }
            }
            $this->attributes['sub'] = implode(',', $Subs);
        }
    }

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'test_order' => array('integer', 'min:1'),
            'extract_id' => 'exists:extract,extract_id',
            'compatibility_class_id' => 'exists:compatibility_class,compatibility_class_id',
            'name' => array('standard', 'between:0,100'),
            'clinic_part_number' => array('standard', 'between:0,32'),
            'for_test_only' => array('standard', 'between:0,45'),
            // these are all actuall extract rules but since antigen
            // and extract are being treated the same they are here too
            'latin_name' => array('standard', 'between:0,100'),
            'manufacturer' => array('standard', 'between:0,45'),
            'abbreviation' => array('standard', 'between:0,45'),
            'ndc' => array('standard', 'between:0,13'),
            'is_visible' => array('standard', 'between:0,10'),
            'percent_glycerin' => array('numeric', 'between:0,100.00'),
            'percent_phenol' => array('numeric', 'between:0,100.00'),
            'percent_hsa' => array('numeric', 'between:0,100.00'),
            'dilution' => array('standard', 'between:0,45'),
            'units' => 'exists:units,units_id',
            'cost' => array('standard', 'between:0,45'),
            'substitutes' => array('array'),
            'specific_gravity' => array('standard', 'between:0,45'),
            'outdate_alert' => array('standard', 'between:0,45'),
            'compatibility_class_id' => 'exists:compatibility_class,compatibility_class_id',
            'image_file' => array('standard', 'between:0,150'),
            'is_diluent' => array('standard', 'between:0,5'),
            'silhouette' => array('standard', 'between:0,45'),
            'color' => array('standard', 'between:0,45'),
            'icon_top_line' => array('standard', 'between:0,45'),
            'icon_middle_line' => array('standard', 'between:0,45'),
            'icon_bottom_line' => array('standard', 'between:0,45'),
            'season_start' => array('season'),
            'season_end' => array('season'),
            'deleted' => array('standard', 'between:0,45'),
        ];

        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['test_order'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
