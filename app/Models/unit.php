<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class unit extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    protected $table = 'units';
    protected $fillable = [
        'unit_type',
        'unit_area',
        'unit_status',
        'number_bedrooms',
        'number_bathrooms',
        'expected_delivery_date',
        'location_id',
        'developer_id',
        'project_id',
        'user_id'
    ];

    protected $casts = [
        'expected_delivery_date' => 'date', // Casts to a Carbon date instance
        'created_at' => 'datetime', // Casts to a Carbon datetime instance
        'updated_at' => 'datetime', // Casts to a Carbon datetime instance
        'deleted_at' => 'datetime', // Casts to a Carbon datetime instance (for soft deletes)
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'unit_favorites')->withTimestamps();
    }
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_units')->withTimestamps();
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
