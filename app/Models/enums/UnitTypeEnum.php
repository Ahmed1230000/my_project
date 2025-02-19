<?php

namespace App\Models\Enums;

enum UnitTypeEnum: string
{
    case APARTMENT = 'apartment';
    case VILLA = 'villa';
    case TOWNHOUSE = 'townhouse';
    case STUDIO = 'studio';
    case PENTHOUSE = 'penthouse';
    case DUPLEX = 'duplex';
    case OFFICE_SPACE = 'office_space';
    case RETAIL_STORE = 'retail_store';
    case WAREHOUSE = 'warehouse';
    case SERVICED_APARTMENT = 'serviced_apartment';
}
