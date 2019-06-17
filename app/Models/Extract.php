<?php

namespace App\Models;

/**
 * Class Extract.
 *
 *
 * @SWG\Definition(
 *   definition="Extract",
 *   required={"name"}
 * )
 */
class Extract extends BaseModel
{
    protected $fillable = ['extract_id', 'name', 'latinname', 'manufacturer', 'code', 'ndc', 'abbreviation', 'visible', 'percentGlycerin', 'percentPhenol', 'percentHSA', 'dilution', 'units', 'cost', 'sub', 'specificgravity', 'outdatealert', 'compatibility_class_id', 'imagefile', 'isDiluent', 'silhouette', 'color', 'topline', 'firstline', 'secondline', 'seasonStart', 'seasonEnd', 'deleted', 'updated_at', 'updated_by', 'created_at', 'created_by'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'extract';

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
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="extract_id",
     *  description="Id of the extract from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $extract_id;

    /**
     * @SWG\Property(
     *  example="Aspergillus Fumigatus",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Extract name",
     *  minLength=0,
     *  maxLength=100,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="latin_name",
     *  description="Extract latin name",
     *  minLength=0,
     *  maxLength=100,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $latin_name;

    /**
     * @SWG\Property(
     *  example="Greer",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="manufacturer",
     *  description="Extract manufacturer",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $manufacturer;

    /**
     * @SWG\Property(
     *  example="GM3A04",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="code",
     *  description="Extract manufacturer code",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $code;

    /**
     * @SWG\Property(
     *  example="MOLD",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="abbreviation",
     *  description="Extract class abbreviation",
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
     *  example="T",
     *  pattern="^[tTfF]{1}$",
     *  title="is_visible",
     *  description="Display extract (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="T",
     * )
     *
     * @var string
     */
    private $is_visible;

    /**
     * @SWG\Property(
     *  example="50.00",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="percent_glycerin",
     *  description="Percent glycerin contained in the extract.",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $percent_glycerin;

    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="percent_phenol",
     *  description="Percent phenol contained in the extract.",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $percent_phenol;

    /**
     * @SWG\Property(
     *  example="0.00",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="percent_hsa",
     *  description="Percent HSA contained in the extract.",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $percent_hsa;

    /**
     * @SWG\Property(
     *  example="1:20",
     *  pattern="",
     *  title="dilution",
     *  description="Dilution of the extract. (Must be present in selected provider config)",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $dilution;

    /**
     * @SWG\Property(
     *  example="2",
     *  pattern="^[0-9]+$",
     *  title="units",
     *  description="Units of extract.",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $units;

    /**
     * @SWG\Property(
     *  example="1.50",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="cost",
     *  description="Cost of extract",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $cost;

    /**
     * @SWG\Property(
     *  example="3,5,23",
     *  pattern="^([0-9]+,?)+$",
     *  title="substitutions",
     *  description="CSV list of extract_ids that may be substituted",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $substitutions;

    /**
     * @SWG\Property(
     *  example="1.16",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="specific_gravity",
     *  description="Specific gravity of the extract",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $specific_gravity;

    /**
     * @SWG\Property(
     *  example="5",
     *  pattern="^[0-9]+$",
     *  title="outdate_alert",
     *  description="Number of months before outdate to display alert",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $outdate_alert;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="compatibility_class_id",
     *  description="Link to compatibility_class table",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $compatibility_class_id;

    /**
     * @SWG\Property(
     *  example="MOLDS_ASPERGILLUS_FUMIGATUS.jpg",
     *  pattern="^([a-zA-Z0-1_-]+){1}\.[a-zA-Z0-1]+$",
     *  title="image_file",
     *  description="Name of image in the images directory.",
     *  minLength=0,
     *  maxLength=150,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $image_file;

    /**
     * @SWG\Property(
     *  example="null",
     *  pattern="^([a-zA-Z0-1_-]+){1}\.[a-zA-Z0-1]+$",
     *  title="silhouette",
     *  description="Name of silhouette image in the images directory (ENT dilution).",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $silhouette;

    /**
     * @SWG\Property(
     *  example="null",
     *  pattern="",
     *  title="color",
     *  description="Color of extract/dilution (Supported colors are RED,YLW,BLUE,GRN,SLVR,ORNG,PRPL,WHT,LTGR,LTBL,PINK,GOLD).",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $color;

    /**
     * @SWG\Property(
     *  example="null",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="top_line",
     *  description="Top line of text for image.",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $top_line;

    /**
     * @SWG\Property(
     *  example="null",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="first_line",
     *  description="first bottom line of text for image.",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $first_line;

    /**
     * @SWG\Property(
     *  example="null",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="second_line",
     *  description="Second bottom line of text for image.",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $second_line;

    /**
     * @SWG\Property(
     *  example="1/1",
     *  pattern="^((0?[1-9])|(1[0-2]))\/(([1-2][0-9])|(3[0-1])|(0?[0-9]))$",
     *  title="season_start",
     *  description="Month day of season start.",
     *  minLength=0,
     *  maxLength=5,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $season_start;

    /**
     * @SWG\Property(
     *  example="12/31",
     *  pattern="^((0?[1-9])|(1[0-2]))\/(([1-2][0-9])|(3[0-1])|(0?[0-9]))$",
     *  title="season_end",
     *  description="Month day of season end.",
     *  minLength=0,
     *  maxLength=5,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $season_end;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Extract deleted (t/f)",
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
     * Accessors
     */
    public function getExtractIdAttribute($value)
    {
        return (int)$value;
    }
    public function getVisibleAttribute($value)
    {
        if ($value === '' || is_null($value)) {
            return 'T';
        } else {
            return $value;
        }
    }
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

    /**
     * An array of fields that need to be converted from one name
     * in the database (array index) to another in the json object (value).
     */
    public static $DBtoRestConversion = array(
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

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            // these are all actually antigen rules but since antigen
            // and extract are being treated the same they are here too
            'test_order' => array('integer', 'min:1'),
            'for_test_only' => array('standard', 'between:0,45'),
            'extract_id' => 'exists:extract,extract_id',
            'clinic_part_number' => array('standard', 'between:0,32'),

            // extract rules
            'name' => array('standard', 'between:0,100'),
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
