<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'feature'
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
                return \App\Models\Feature::whereIn('id', $featureIds)->get();
            }
        }
        return collect();
    }
}
