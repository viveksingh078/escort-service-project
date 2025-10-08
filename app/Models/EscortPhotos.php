<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EscortPhotos extends Model
{
    use HasFactory;

    protected $table = 'escort_media';

    protected $fillable = [
        'escort_id',
        'title',
        'file_path',
        'description',
        'is_public',
        'is_approved',
    ];

    /**
     * Relation: Photo belongs to Escort
     */
    public function escort()
    {
        return $this->belongsTo(User::class, 'escort_id');
    }
}
