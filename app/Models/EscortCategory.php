<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscortCategory extends Model
{
    // Explicit table name if not standard (optional)
    protected $table = 'escort_categories';

    // Fillable fields for mass-assignment
    protected $fillable = ['name', 'slug'];

    // Relation For Escorts
    public function escorts()
    {
        return $this->hasMany(\App\Models\Usermeta::class, 'meta_value', 'id')
            ->where('meta_key', 'category_id');
    }


}
