<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\FinalizeCollection;
use Illuminate\Validation\Validator;
use function DeepCopy\deep_copy;
use App\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class BaseModel extends Model
{
    public static function atLeastSchema($testVersion)
    {
        $Version = XtractSchema::first()->version;
        //get everything before the RCXX that may be present
        $VersionArray = explode('R', strtoupper($Version));
        //remove the _ that may have been before RCXX
        $Version = trim($VersionArray[0], '_');
        return (float) $testVersion <= (float) $Version;
    }
    public function hasManyIfSchema($Version, $ClassName, $Table1Id, $Table2Id = null)
    {
        // if table 2 id is null it should be the same as table1 id
        $Table2Id = is_null($Table2Id) ? $Table1Id : $Table2Id;
        if ($this->atLeastSchema($Version)) {
            return $this->hasMany($ClassName, $Table1Id, $Table2Id);
        } else {
            // this is a bit of a hack but since we must return a relationship of some
            // sort, return a relationship on xtract schema which we know exists, where
            // the table_id matches the xtract schema version (which will never happen)
            return $this->hasMany('App\Models\XtractSchema', 'version', $Table1Id);
        }
    }
    /**
     * this causes all models to apply their designated "conversion" array
     * when being translated to an array or JSON so that proper API names are
     * returned rather than the DB name which may be confusing.
     * @return Array An array object with updated property names according
     *                  to the models conversion array.
     */
    public function toArray()
    {
        $ObjectProps = parent::toArray();
        //$Conversion = $this->getConversion();
        foreach ($ObjectProps as $key => $Column) {
            //determine the new name of the object property
            if (array_key_exists($key, $this::$DBtoRestConversion)) {
                $Name = $this::$DBtoRestConversion[$key];
            } else {
                $Name = $key;
            }
            //if new name and column name don't match, it needs to be changed
            if ($Name !== $key) {
                //store the value of the property
                $Value = $ObjectProps[$key];

                //Unset the old property name
                unset($ObjectProps[$key]);

                //set the new property if its not null
                if (!is_null($Value)) {
                    $ObjectProps[$Name] = $Value;
                }
            } else {
                //if the name was good already, we still need to remove
                //it if null
                if (is_null($ObjectProps[$key])) {
                    unset($ObjectProps[$key]);
                }
            }
        }

        return $ObjectProps;
    }

    /**
    * Mutators to alter data before saving to DB.
    */
    public function setDeletedAttribute($value)
    {
        $this->attributes['deleted'] = strtoupper($value);
    }

    public function setNonXtractAttribute($value)
    {
        $this->attributes['nonXtract'] = strtoupper($value);
    }

    public function setArchivedAttribute($value)
    {
        $this->attributes['archived'] = strtoupper($value);
    }

    /**
     * Custom query scopes.
     */
    public function scopelike($query, $column, $value)
    {
        if (is_null($value) || $value==='%') {
            return $query;
        } elseif (is_numeric($value)) {
            return $query->where($column, $value);
        } else {
            return $query->where($column, 'like', $value);
        }
    }

    public function scopeSearch($query, $request)
    {
        //get all db column names for this type of object
        $ColumnNames = $this->getTableColumns();
        // get all of the properties the user wanted to search for
        $SearchData = $request->input();

        foreach ($SearchData as $key => $value) {
            // for each property name, run get the db version of the name
            // if one exists
            $ConvertedName = array_search($key, $this::$DBtoRestConversion);
            $ColumnName = array_search($key, $ColumnNames);
            if ($ConvertedName !== false) {
                $Name = $ConvertedName;
            } elseif ($ColumnName !== false) {
                $Name = $key;
            } else {
                // they searched for a prop that isn't on the object
                // so skip this one
                continue;
            }
            //now use the (possibly) converted name and value to add
            //the appropriate like clause to the query
            if ($Name !== '' && !is_null($value)) {
                //if set, apply it to the search query
                $query->like($Name, $value);
            }
        }
        return $query;
    }

    public function scopeLimitOffset($query, $limit, $offset)
    {
        if (!is_null($limit) && !is_null($offset)) {
            return $query->skip($offset)->take($limit);
        } elseif (!is_null($limit)) {
            return $query->take($limit);
        } else {
            return $query;
        }
    }


    /**
     * General helper functions.
     */

    /**
     * Get the column names associated with an object
     * @return Array array of column names.
     */
    public function getTableColumns()
    {
        $ColumnNames = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        //delete the primary key from the columnNames since we don't allow that to
        //be changed.
        if (!is_null($this->getKeyName())) {
            unset($ColumnNames[array_search($this->getKeyName(), $ColumnNames)]);
        }
        //Some places our schema uses special column names that need to be quoted so find and replace these.
        $Index = array_search('all', $ColumnNames);
        if ($Index) {
            $ColumnNames[$Index] = '`all`';
        }
        
        return $ColumnNames;
    }

    /**
     * Force phone number to a standard format
     * @param  [type] $phone [description]
     * @return [type]        [description]
     */
    public function fixPhone($phone)
    {
        //reformat phone number to +XXXXXXXX
        $search = array(' (', ') ', '(', ')', '-');
        $replace = array('', '', '', '', '');

        return str_replace($search, $replace, $phone);
    }

    /**
    * Set the keys for a save update query.
    * This is a fix for tables with composite keys
    * TODO: Investigate this later on
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        if (is_array($this->primaryKey)) {
            foreach ($this->primaryKey as $pk) {
                $query->where($pk, '=', $this->original[$pk]);
            }
            return $query;
        } else {
            return parent::setKeysForSaveQuery($query);
        }
    }

    // validation errors
    protected $ValidationErrors;

    public $GlobalMessages = [
        // validation messages that should effect all objects can go here
        'decimal63' => 'The :attribute field must be decimal value with a maximum characteristic of 3 digits and a maximum mantissa of 3 digits.',
        'numeric_gte' => 'The :attribute field must be non-negative.'
    ];

    public $Messages = [
        // these messages are to be overridden within specific object classes and
        // will be merged with (and take precedence over) the global messages above.
    ];
    
    public function validate($data, $objectId)
    {
        $ValidationMessages = array_merge(
            $this->GlobalMessages,
            $this->Messages
        );
        // make a new validator object
        $Validator = \Validator::make($data, $this->makeValidationRules($objectId, $data), $ValidationMessages);

        // add sometimes rules
        $Validator = $this->attachSometimesRules($Validator, $objectId);

        // check for failure
        if ($Validator->fails()) {
            // set errors and return false
            $this->ValidationErrors = $Validator->errors();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors()
    {
        return $this->ValidationErrors;
    }

    public function markDeleted($RequestOptions)
    {
        if (isset($this->deleted)) {
            $this->deleted = 'T';
            $this->save();
        } else {
            $this->delete();
        }
        
        return $this;
    }

    /**
     * Accepts the object from the database and a filter and unsets all object
     * properties not present in the filter array
     * @param  [type]  $object  Object from the database
     * @param  array|-1         the filter of properties to return
     * @return [type]           the final object.
     */
    public function filterProperties($object, $filter, $newObject = null)
    {
        $newObj = is_null($newObject) ? deep_copy($object) : $newObject;
        if ($filter !== -1) {
            //make a new object of the same type as the original containing only the properties
            //in the filter array
            if (is_array($object)) {
                $className = 'stdClass';
            } else {
                $className = get_class($object);
            }
            if ($className !== 'Illuminate\Database\Eloquent\Collection' && !is_array($object)) {
                $newObj = is_null($newObject) ? new $className : $newObject;
                foreach ($filter as $properties) {
                    $idx = strpos($properties, '.');
                    if ($idx) {
                        $property = substr($properties, 0, $idx);
                        $rest = substr($properties, $idx + 1);
                    } else {
                        $property = substr($properties, 0);
                        $rest = null;
                    }
                    $dbName = $className === 'stdClass' ? $property : $newObj->getPropDbName($property);

                    if ($idx != 0) {
                        if (is_array($object)) {
                            $newObj[$dbName] = $this::filterProperties($object[$dbName], [$rest], $newObj[$dbName]);
                        } else {
                            $newObj->{$dbName} = $this::filterProperties($object->{$dbName}, [$rest], $newObj->{$dbName});
                        }
                    } else {
                        if (is_array($object)) {
                            $newObj[$dbName] = $object[$dbName];
                        } elseif ($className === 'stdClass') {
                            // standard class objects must be addressed as objects
                            $newObj->{$dbName} = $object->{$dbName};
                        } else {
                            // non standard class objects can be extended to be treated like an array. this gets around looking
                            // for a property that also happens to exist as a method on the object like "visible"
                            $newObj[$dbName] = $object[$dbName];
                        }
                    }
                }
            } else {
                foreach ($object as $key => $element) {
                    $newEl = isset($newObj[$key]) ? $newObj[$key] : null;
                    $newObj[$key] = $this->filterProperties($element, $filter, $newEl);
                }
            }
        }
        return $newObj;
    }
    private function getPropDbName($property)
    {
        $dbName = array_search($property, $this::$DBtoRestConversion);
        if (!$dbName) {
            // if property wasn't in the DBtoRestConversion array, assume
            // that it needs no conversion and is in fact the db version of the name
            $dbName = $property;
        }
        return $dbName;
    }
    // holds name conversion array for each model
    public static $DBtoRestConversion = array();
}
