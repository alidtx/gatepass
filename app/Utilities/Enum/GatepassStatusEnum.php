<?php

namespace App\Utilities\Enum;

use App\Utilities\Enum\BasicEnum;

/* To get the Keys,
 * Use: OrderStatusEnum::getKeys()
 * To get the Values,
 * Use: OrderStatusEnum::getValues()
 */

abstract class GatepassStatusEnum extends BasicEnum
{
    // To call it anywhere, just call: GatepassStatusEnum::Active
    const CREATED = 0;
    const UPDATED = 1;
    const APPROVED = 2;
    const REJECTED = 3;
    const FINALLY_APPROVED = 4;
    const DRAFT = 5;
}
