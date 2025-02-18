<?php

namespace App\Models\enums;


enum PlaceTypeEnum: string
{
    case Apartment = 'Gym';
    case House = 'Club';
    case Condo = 'university';
    case Townhouse = 'hospital';
    case Other = 'other';
}
