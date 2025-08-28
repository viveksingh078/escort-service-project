<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'user_id',
        'escort_id',
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'invoice_id',
        'status'
    ];
}