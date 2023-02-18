<?php

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum CustomerCategory: string
{
    use EnumToArray;

    case INTERIOR_DESIGN = 'interior-design';
    case PRODUCT_DESIGN = 'product-design';
    case ARCHITETURE = 'architecture';
    case FURNITURE_SHOP = 'furniture-shop';
    case INDUSTRIAL = 'industrial';
    case REAL_ESTATE = 'real-estate';
    case OTHER = 'other';
}
