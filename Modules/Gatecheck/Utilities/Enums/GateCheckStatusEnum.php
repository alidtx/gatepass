<?php

namespace Modules\Gatecheck\Utilities\Enums;

use App\Utilities\Enum\BasicEnum;

/* To get the Keys,
 * Use: GateCheckStatusEnum::getKeys()
 * To get the Values,
 * Use: GateCheckStatusEnum::getValues()
 */

abstract class GateCheckStatusEnum extends BasicEnum
{
    // To call it anywhere, just call: GateCheckStatusEnum::Active
    const CREATED = 0;
    const RELEASED = 1;
    const HELD = 2;
}
