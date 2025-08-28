<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    // Explicit table name if not standard (optional)
    protected $table = 'cities';

    // Fillable fields for mass-assignment
    protected $fillable = ['name', 'state_id'];
}
