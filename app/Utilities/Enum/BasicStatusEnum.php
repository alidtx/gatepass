<?php

namespace App\Utilities\Enum;

use App\Utilities\Enum\BasicEnum;

/* To get the Keys,
 * Use: OrderStatusEnum::getKeys()
 * To get the Values,
 * Use: OrderStatusEnum::getValues()
 */

abstract class BasicStatusEnum extends BasicEnum
{
    // To call it anywhere, just call: OrderStatusEnum::Active
    const INACTIVE = 0;
    const ACTIVE = 1;
}
