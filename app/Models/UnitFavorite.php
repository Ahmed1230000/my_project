<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitFavorite extends Model
{
    use SoftDeletes;
    protected $table = 'unit_favorite';
    protected $fillable = [
        'user_id',
        'unit_id'
    ];
    protected $cast = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
