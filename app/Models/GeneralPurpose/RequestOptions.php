<?php

namespace App\Models\GeneralPurpose;

/**
 * Class requestOptions.
 */
class RequestOptions
{
    /**
    * The filter string used to remove properties from the object before returning
    *
    * @var string
    */
    public $filter;

    /**
    * A limit integer to be applied to the query
    *
    * @var int
    */
    public $limit;

    /**
    * An offset integer to be applied to the query
    *
    * @var int
    */
    public $offset;

    /**
    * An id of the primary resource being requested
    *
    * @var int
    */
    public $id;

    /**
    * ID of the patient whos resources is being requested
    *
    * @var int
    */
    public $patient_id;

    /**
    * Is a search request?
    *
    * @var bool
    */
    public $isSearch;

    /**
    * Request method (POST, PUT, GET, DELETE)
    *
    * @var string
    */
    public $method;

    /**
    * User object that made the request
    *
    * @var /App/Models/User
    */
    public $user;
}
