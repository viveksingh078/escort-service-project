<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'transaction_id',
        'invoice_number',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'gateway_response_code',
        'gateway_response_message',
        'payment_details',
        'customer_name',
        'customer_email',
        'customer_phone',
        'description',
        'paid_at',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
    ];
}
