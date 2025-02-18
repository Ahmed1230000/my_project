<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class amenity extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'amenities';
    protected $fillable = [
        'name',
        'place_type',
        'distance'
    ];

    protected $casts = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function units()
    {
        return $this->belongsToMany(unit::class, 'amenity_units')->withTimestamps();
    }
}
