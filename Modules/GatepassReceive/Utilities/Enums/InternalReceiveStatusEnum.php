<?php

namespace Modules\GatepassReceive\Utilities\Enums;

use App\Utilities\Enum\BasicEnum;

/* To get the Keys,
 * Use: InternalReceiveStatusEnum::getKeys()
 * To get the Values,
 * Use: InternalReceiveStatusEnum::getValues()
 */

abstract class InternalReceiveStatusEnum extends BasicEnum
{
    // To call it anywhere, just call: OrderStatusEnum::Active
    const FULLY_RECEIVED = 0;
    const SHORT_RECEIVED = 1;
    const EXCESS_RECEIVED = 2;
}
