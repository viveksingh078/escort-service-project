<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'category',
        'message',
        'attachment',
        'status',
        'user_id'
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}

