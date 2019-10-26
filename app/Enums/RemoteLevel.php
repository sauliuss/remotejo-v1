<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RemoteLevel extends Enum
{
    const RemoteFriendly =   0;
    const RemoteFirst =   1;
    const FullyRemote = 2;
}
