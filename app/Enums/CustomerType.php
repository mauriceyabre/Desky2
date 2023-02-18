<?php

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum CustomerType: string
{
    use EnumToArray;

    case COMPANY = 'company';
    case PERSON = 'person';
    case PUBLIC_ADMINISTRATION = 'pa';
    case CONDOMINIUM = 'condo';
}
