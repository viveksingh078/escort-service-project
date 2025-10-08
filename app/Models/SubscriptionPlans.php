<?php

<<<<<<< HEAD
=======


>>>>>>> 23c30d7 (Escort project)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlans extends Model
{
    use HasFactory;

<<<<<<< HEAD
     protected $table = 'subscription_plans';
=======
    protected $table = 'subscription_plans';
>>>>>>> 23c30d7 (Escort project)

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'features'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
<<<<<<< HEAD
=======
        'features' => 'array',
>>>>>>> 23c30d7 (Escort project)
    ];

    // Relationship with features (if needed)
    public function getSelectedFeaturesAttribute()
    {
<<<<<<< HEAD
        if ($this->feature) {
            $featureIds = json_decode($this->feature, true);
            if (is_array($featureIds)) {
                return \App\Models\Features::whereIn('id', $featureIds)->get();
            }
        }
        return collect();
    }
=======
        $features = $this->features; // Raw value lo

        // Agar string hai, toh JSON decode karo (handle escaped strings)
        if (is_string($features)) {
            $features = json_decode($features, true); // true for array
            if (json_last_error() !== JSON_ERROR_NONE) {
                $features = []; // Agar decode fail ho, empty array
            }
        }

        if (!empty($features) && is_array($features)) {
            return \App\Models\Features::whereIn('id', $features)->pluck('name')->toArray();
        }
        return [];
    }

>>>>>>> 23c30d7 (Escort project)
}
