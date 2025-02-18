<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Developer extends Model
{
    use SoftDeletes;
    protected $table = 'developers';
    protected $fillable = [
        'name',
        'project_num',
        'unit_num',
        'phone_num',
        'address',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime', // Casts to a Carbon datetime instance
        'updated_at' => 'datetime', // Casts to a Carbon datetime instance
        'deleted_at' => 'datetime', // Casts to a Carbon datetime instance (for soft deletes)
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
