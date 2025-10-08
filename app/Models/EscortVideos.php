<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EscortVideos extends Model
{
    use HasFactory;

    protected $table = 'escort_media';

    protected $fillable = [
        'escort_id',
        'title',
        'file_path',
        'thumbnail_path',
        'description',
        'is_public',
    ];

    /**
     * Relation: Video belongs to Escort
     */
    public function escort()
    {
        return $this->belongsTo(User::class, 'escort_id');
    }
}
