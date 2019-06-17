<?php

namespace App\Models\GeneralPurpose;

/**
 * Class MixObject.
 *
 *
 * @SWG\Definition(
 *   definition="MixObject",
 *   required={"constituents"}
 * )
 */
class MixObject
{
    /**
     * @SWG\Property(
     *  pattern="",
     *  title="constituents",
     *  type="array",
     *  @SWG\Items(ref="#/definitions/MixItem")
     * )
     * @var array
     * */
    private $constituents;
}

/**
 * Class MixItem.
 *
 *
 * @SWG\Definition(
 *   definition="MixItem",
 *   required={"inventory_id","dose"}
 * )
 */
class MixItem
{
    /**
    * @SWG\Property(
    *  example="7",
    *  title="inventory_id",
    *  description="Id of the inventory object from the database",
    *  type="integer",
    *  default="",
    * )
    * @var int
    */
    private $inventory_id;
    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="dose",
     *  description="The dose for this inventory item",
     *  minLength=0,
     *  maxLength=5,
     *  default="null",
     * )
     *
     * @var float
     */
    private $dose;
}
