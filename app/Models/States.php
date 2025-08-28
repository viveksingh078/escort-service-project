<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    // Explicit table name if not standard (optional)
    protected $table = 'states';

    // Fillable fields for mass-assignment
    protected $fillable = ['name', 'country_id'];
}
