<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'user_id',
<<<<<<< HEAD
        'escort_id',
=======
        'fan_id',
        'escort_id',
        'plan_id',
>>>>>>> 23c30d7 (Escort project)
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
<<<<<<< HEAD
        'invoice_id',
        'status'
    ];
=======
        'amount',
        'invoice_id',
        'status',
        'subscription_type',
        'previous_plan_id',
        'credit_amount',
        'starts_at',
        'expires_at'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function escort()
    {
        return $this->belongsTo(User::class, 'escort_id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlans::class, 'plan_id');
    }

    public function previousPlan()
    {
        return $this->belongsTo(SubscriptionPlans::class, 'previous_plan_id');
    }
>>>>>>> 23c30d7 (Escort project)
}