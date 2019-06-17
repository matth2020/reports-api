<?php

namespace App\Models;

use App\Models\BaseModel;
use Laravel\Passport\HasApiTokens;
use Illuminate\Validation\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Schema;

class User extends BaseModel implements AuthenticatableContract
{
    use HasApiTokens, Authenticatable;//, Authorizable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'displayname'];

    protected $hidden = ['password', 'remember_token', 'faceimage', 'lockState'];

    /**
     * Defines how users are identified for passport
     */
    public function findForPassport($username)
    {
        return $this->where('displayname', $username)->where('deleted', 'F')->first();
    }

    /**
     * Relationships
     */
    public function compounds()
    {
        return $this->hasMany('App\Models\Compound', 'user_id');
    }
    public function flags()
    {
        return $this->hasMany('App\Models\Flag', 'user_id');
    }
    public function injections()
    {
        return $this->hasMany('App\Models\Injection', 'user_id');
    }
    public function prescriptions()
    {
        return $this->hasMany('App\Models\Prescription', 'user_id');
    }
    public function vials()
    {
        return $this->hasMany('App\Models\Vial', 'user_id');
    }
    public function skintests()
    {
        return $this->hasMany('App\Models\Skintest', 'user_id');
    }
    public function configs()
    {
        return $this->hasMany('App\Models\UserConfig', 'user_id', 'user_id');
    }
    public function userGroups()
    {
        return $this->hasManyThrough('App\Models\UserGroup', 'App\Models\UserGroupUser', 'user_id', 'user_group_id', 'user_id', 'user_group_id');
    }
    public function userGroupsAssignments()
    {
        return $this->hasMany('App\Models\UserGroupUser', 'user_id', 'user_id');
    }

    /**
     * Accessors
     */
    public function getUserIdAttribute($value)
    {
        return (int)$value;
    }

    /**
     * helper
     */
    public function getPrivileges()
    {
        // load the required relationships
        $this->load('userGroups.privileges');
        // build an array of all scopes
        $Privileges = [];
        $this->userGroups->each(function ($userGroup, $idx) use (&$Privileges) {
            $userGroup->privileges->each(function ($privilege, $idx2) use (&$Privileges) {
                array_push($Privileges, $privilege->name);
            });
        });
        // remove dup scopes and return result
        // then reindex after dups are removed so json conversion
        // will keep it as an array rather than an object
        return array_values(array_unique($Privileges));
    }
    public function hasPrivilege($priv)
    {
        // case insensitive search of users privileges
        return in_array(strtolower($priv), array_map('strtolower', $this->getPrivileges()));
    }
    
    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array();

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'displayname' => ['between:0,45'],
            'firstname' => ['between:0,45'],
            'lastname' => ['between:0,45'],
            'title'  => ['between:0,45'],
            'privilege' => ['between:0,45'],
            'deleted' => ['in:t,T,f,F'],
            'general' => ['standard','between:0,150'],
            'email' => ['email'],
            'account_id' => ['exists:account,account_id,deleted,F']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['displayname','password'], 'required', function () use ($id) {
            return is_null($id);
        });
        $Validator->sometimes(['displayname'], 'unique:user,displayname,0,user_id,deleted,F', function () use ($id) {
            return is_null($id);
        });
        $Validator->sometimes(['displayname'], 'unique:user,displayname,'.$id.',user_id,deleted,F', function () use ($id) {
            return !is_null($id);
        });

        return $Validator;
    }
}

/**
 * Class swaggerUser.
 *
 *
 * @SWG\Definition(
 *   definition="swaggerUser",
 * )
 */
class swaggerUser
{
    /**
    *  @SWG\Property(
    *  example="1",
    *  pattern="^[0-9]+$",
    *  title="user_id",
    *  description="Id of the user entry from the database.",
    *  minLength=0,
    *  maxLength=11,
    *  type={"integer","null"},
    *  default=""
    * )
    *
    * @var int
    */
    private $user_id;

    /**
     * @SWG\Property(
     *  example="John Doe",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="displayname",
     *  description="Username to display on the system",
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
     *  example="John",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="firstname",
     *  description="The users firstname",
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
     *  description="The users lastname",
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
     *  example="Dr.",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="title",
     *  description="The users title",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @SWG\Property(
     *  example="Some notes",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="general",
     *  description="A general notes field",
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
     *  description="Is the user deleted in the system (t/f)",
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
     *  example="Admin",
     *  pattern="^[tTfF]{1}$",
     *  title="privilege",
     *  description="The user account privilege level",
     *  type={"string","null"},
     *  enum={"Admin","User", "liteUser"},
     *  default="",
     * )
     *
     * @var string
     */
    private $privilege;
}
