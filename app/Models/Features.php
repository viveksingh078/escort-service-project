<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
     // Explicit table name if not standard (optional)
    protected $table = 'features';

    // Fillable fields for mass-assignment
    protected $fillable = ['name', 'description'];
}
