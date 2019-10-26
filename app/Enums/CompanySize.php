<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CompanySize extends Enum
{
    const micro =   0;
    const small =   1;
    const medium = 2;
    const large = 3;
    const extra_large = 4;

    public static function getDescription($value): string
    {
        if ($value === self::micro) {
            return '1-10';
        }
        elseif($value === self::small){
        	return '11-50';
        }
        elseif($value === self::medium){
        	return '51-100';
        }
        elseif($value === self::large){
        	return '101-500';
        }
        elseif($value === self::extra_large){
        	return '500+';
     
        }
        return parent::getDescription($value);
    }
}
