<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'region_id',
        'subregion',
        'subregion_id',
        'nationality',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'wikiDataId',
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    // Custom method to get escorts_count from usermeta table
    public function getEscortsCountAttribute()
    {
        return DB::table('usermeta')
            ->where('meta_key', 'country')
            ->where('meta_value', $this->id)
            ->count();
    }
}