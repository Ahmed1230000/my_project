<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmenityUnit extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'amenity_units';
    protected $fillable = ['amenity_id', 'unit_id'];

    protected $casts = [
        'amenity_id'  => 'integer',
        'unit_id'     => 'integer',
        'createed_at' => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];
}
