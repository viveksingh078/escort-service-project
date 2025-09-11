<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    //
    protected $fillable = ['support_ticket_id', 'message', 'admin_id'];
}
