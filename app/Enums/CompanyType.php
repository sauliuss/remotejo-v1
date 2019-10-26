<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CompanyType extends Enum
{
    const PrivateCompany =   0;
    const PublicCompany =   1;
    const VcFunded = 2;
    const Bootstrapped = 3;

    public static function getDescription($value): string
    {
        if ($value === self::PrivateCompany) {
            return 'Private';
        }
        elseif($value === self::PublicCompany){
        	return 'Public';
        }
        elseif($value === self::VcFunded){
        	return 'VC Funded';
        }
        return parent::getDescription($value);
    }
}
