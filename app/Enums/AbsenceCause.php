<?php

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum AbsenceCause:string {
    use EnumToArray;
    case HOLIDAYS = 'holidays';
    case SICKNESS = 'sickness';
    case PERMIT = 'permit';
}
