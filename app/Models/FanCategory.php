<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FanCategory extends Model
{
    //
    // Explicit table name if not standard (optional)
    protected $table = 'fan_categories';

    // Fillable fields for mass-assignment
    protected $fillable = ['name', 'slug'];
}
