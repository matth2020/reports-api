<?php

namespace App\Models\GeneralPurpose;

/**
 * Class TrackingConfigRow.
 *
 *
 * @SWG\Definition(
 *   definition="TrackingConfigRow",
 *   required={"name"}
 * )
 */
class TrackingConfigRow
{
    /**
    * @SWG\Property(
    *  example="peak flow",
    *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
    *  title="name",
    *  description="Name of the trackingconfig item",
    *  minLength=0,
    *  maxLength=32,
    *  type={"string","null"},
    *  default="",
    * )
    *
    * @var string
    */
    private $name;
    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="minimum",
     *  description="Minimum value for this tracking item. Values below this level will trigger a lockout event.",
     *  minLength=0,
     *  maxLength=5,
     *  default="null",
     * )
     *
     * @var float
     */
    private $minimum;
    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="maximum",
     *  description="Maximum value for this tracking item. Values above this level will trigger a lockout event.",
     *  minLength=0,
     *  maxLength=5,
     *  default="null",
     * )
     *
     * @var float
     */
    private $maximum;
}
