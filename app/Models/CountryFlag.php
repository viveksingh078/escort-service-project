<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserMeta;

class CountryFlag extends Model
{
    protected $table = 'country_flags';
    protected $fillable = ['country_id', 'name', 'flag_path'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function escorts()
{
    return $this->hasManyThrough(
        User::class,        // Related model
        Usermeta::class,    // Intermediate model
        'meta_value',       // Foreign key on usermeta (points to country_flags.id)
        'id',               // Foreign key on users table
        'id',               // Local key on country_flags
        'user_id'           // Local key on usermeta table
    )->where('role', 'escort'); // Only escorts
}
}