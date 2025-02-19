<?php

namespace App\Models\Enums;

enum UnitStatusEnum: string
{
    case AVAILABLE = 'available';
    case SOLD = 'sold';
    case RENTED = 'rented';
    case UNDER_CONSTRUCTION = 'under_construction';
}
