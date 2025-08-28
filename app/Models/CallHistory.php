<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }
    
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    
    public function participants()
    {
        return $this->hasMany(CallParticipant::class);
    }
}
