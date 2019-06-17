<?php

namespace App\Models\GeneralPurpose;

/**
 * Class Jsend200.
 *
 *
 * @SWG\Definition(
 *   required={"status"}
 * )
 */
class Jsend200
{
    /**
     * @SWG\Property(
     *  example="success"
     * )
     *
     * @var int
     */
    private $status;

    /**
     * @SWG\Property(
     *  example="Query result"
     * )
     *
     * @var mixed
     */
    private $data;
}
