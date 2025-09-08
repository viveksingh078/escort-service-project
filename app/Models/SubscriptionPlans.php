<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlans extends Model
{
    use HasFactory;

     protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'features'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
    ];

    // Relationship with features (if needed)
    public function getSelectedFeaturesAttribute()
    {
        if ($this->feature) {
            $featureIds = json_decode($this->feature, true);
            if (is_array($featureIds)) {
                return \App\Models\Features::whereIn('id', $featureIds)->get();
            }
        }
        return collect();
    }
}
