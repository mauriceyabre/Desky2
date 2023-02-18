<?php

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum AttendanceStatus: string {
    use EnumToArray;
    case NEW = 'new';
    case PENDING = 'pending';
    case APPROVED = 'approved';

}
