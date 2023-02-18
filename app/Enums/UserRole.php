<?php

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum UserRole: int
{
    use EnumToArray;

    case SUPERADMIN = 1;
    case ADMIN = 2;
    case DESIGNER = 4;
    case ACCOUNTANT = 5;
}
