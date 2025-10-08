<?php

namespace App\Services;

use App\Models\User;
use App\Models\SubscriptionPlans;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Process subscription plan change (upgrade/downgrade)
     */
    public function processSubscriptionChange($userId, $newPlanId, $escortId, $billingData)
    {
        $user = User::findOrFail($userId);
        $newPlan = SubscriptionPlans::findOrFail($newPlanId);
        
        // Get current active subscription
        $currentSubscription = $this->getCurrentSubscription($userId, $escortId);
        
        if ($currentSubscription) {
            $currentPlan = SubscriptionPlans::find($currentSubscription->plan_id);
            $changeType = $this->determineChangeType($currentPlan, $newPlan);
            
            // Handle different change types
            switch ($changeType) {
                case 'upgrade':
                    return $this->handleUpgrade($user, $currentSubscription, $newPlan, $escortId, $billingData);
                case 'downgrade':
                    return $this->handleDowngrade($user, $currentSubscription, $newPlan, $escortId, $billingData);
                case 'same_plan':
                    return $this->handleSamePlan($user, $currentSubscription, $newPlan, $escortId, $billingData);
            }
        } else {
            // New subscription
            return $this->handleNewSubscription($user, $newPlan, $escortId, $billingData);
        }
    }
    
    /**
     * Get current active subscription for user and escort
     */
    public function getCurrentSubscription($userId, $escortId)
    {
        return Billing::where('fan_id', $userId)
            ->where('escort_id', $escortId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }
    
    /**
     * Determine if it's upgrade, downgrade, or same plan
     */
    private function determineChangeType($currentPlan, $newPlan)
    {
        if (!$currentPlan) {
            return 'new';
        }
        
        if ($currentPlan->id === $newPlan->id) {
            return 'same_plan';
        }
        
        // Compare by price to determine upgrade/downgrade
        if ($newPlan->price > $currentPlan->price) {
            return 'upgrade';
        } else {
            return 'downgrade';
        }
    }
    
    /**
     * Handle subscription upgrade
     */
    private function handleUpgrade($user, $currentSubscription, $newPlan, $escortId, $billingData)
    {
        // Calculate prorated amount for upgrade
        $remainingDays = $this->calculateRemainingDays($currentSubscription);
        $currentPlan = SubscriptionPlans::find($currentSubscription->plan_id);
        
        // Calculate credit for remaining time
        $dailyRate = $currentPlan->price / $currentPlan->duration_days;
        $creditAmount = $dailyRate * $remainingDays;
        
        // Calculate new plan cost
        $newPlanCost = $newPlan->price;
        $upgradeAmount = max(0, $newPlanCost - $creditAmount);
        
        // Create billing record for upgrade
        $billing = Billing::create([
            'user_id' => $user->id,
            'fan_id' => $user->id,
            'escort_id' => $escortId,
            'plan_id' => $newPlan->id,
            'amount' => $upgradeAmount,
            'status' => 'pending',
            'subscription_type' => 'upgrade',
            'previous_plan_id' => $currentSubscription->plan_id,
            'credit_amount' => $creditAmount,
            'first_name' => $billingData['first_name'],
            'last_name' => $billingData['last_name'],
            'address' => $billingData['address'],
            'city' => $billingData['city'],
            'state' => $billingData['state'],
            'zip_code' => $billingData['zip_code'],
            'country' => $billingData['country'],
        ]);
        
        return [
            'type' => 'upgrade',
            'billing' => $billing,
            'credit_amount' => $creditAmount,
            'upgrade_amount' => $upgradeAmount,
            'message' => 'Upgrade processed with credit for remaining time'
        ];
    }
    
    /**
     * Handle subscription downgrade
     */
    private function handleDowngrade($user, $currentSubscription, $newPlan, $escortId, $billingData)
    {
        // For downgrade, we'll start the new plan after current expires
        $billing = Billing::create([
            'user_id' => $user->id,
            'fan_id' => $user->id,
            'escort_id' => $escortId,
            'plan_id' => $newPlan->id,
            'amount' => $newPlan->price,
            'status' => 'pending',
            'subscription_type' => 'downgrade',
            'previous_plan_id' => $currentSubscription->plan_id,
            'starts_at' => $currentSubscription->expires_at,
            'first_name' => $billingData['first_name'],
            'last_name' => $billingData['last_name'],
            'address' => $billingData['address'],
            'city' => $billingData['city'],
            'state' => $billingData['state'],
            'zip_code' => $billingData['zip_code'],
            'country' => $billingData['country'],
        ]);
        
        return [
            'type' => 'downgrade',
            'billing' => $billing,
            'message' => 'Downgrade scheduled to start after current plan expires'
        ];
    }
    
    /**
     * Handle same plan renewal
     */
    private function handleSamePlan($user, $currentSubscription, $newPlan, $escortId, $billingData)
    {
        // Extend current subscription
        $newExpiryDate = $this->calculateNewExpiryDate($currentSubscription, $newPlan);
        
        $billing = Billing::create([
            'user_id' => $user->id,
            'fan_id' => $user->id,
            'escort_id' => $escortId,
            'plan_id' => $newPlan->id,
            'amount' => $newPlan->price,
            'status' => 'pending',
            'subscription_type' => 'renewal',
            'expires_at' => $newExpiryDate,
            'first_name' => $billingData['first_name'],
            'last_name' => $billingData['last_name'],
            'address' => $billingData['address'],
            'city' => $billingData['city'],
            'state' => $billingData['state'],
            'zip_code' => $billingData['zip_code'],
            'country' => $billingData['country'],
        ]);
        
        return [
            'type' => 'renewal',
            'billing' => $billing,
            'message' => 'Plan renewed successfully'
        ];
    }
    
    /**
     * Handle new subscription
     */
    private function handleNewSubscription($user, $newPlan, $escortId, $billingData)
    {
        $billing = Billing::create([
            'user_id' => $user->id,
            'fan_id' => $user->id,
            'escort_id' => $escortId,
            'plan_id' => $newPlan->id,
            'amount' => $newPlan->price,
            'status' => 'pending',
            'subscription_type' => 'new',
            'first_name' => $billingData['first_name'],
            'last_name' => $billingData['last_name'],
            'address' => $billingData['address'],
            'city' => $billingData['city'],
            'state' => $billingData['state'],
            'zip_code' => $billingData['zip_code'],
            'country' => $billingData['country'],
        ]);
        
        return [
            'type' => 'new',
            'billing' => $billing,
            'message' => 'New subscription created'
        ];
    }
    
    /**
     * Calculate remaining days in current subscription
     */
    private function calculateRemainingDays($subscription)
    {
        if (!$subscription->expires_at) {
            return 0;
        }
        
        $now = now();
        $expiresAt = \Carbon\Carbon::parse($subscription->expires_at);
        
        if ($expiresAt->isPast()) {
            return 0;
        }
        
        return $now->diffInDays($expiresAt);
    }
    
    /**
     * Calculate new expiry date for renewal
     */
    private function calculateNewExpiryDate($currentSubscription, $newPlan)
    {
        $currentExpiry = $currentSubscription->expires_at ? 
            \Carbon\Carbon::parse($currentSubscription->expires_at) : now();
        
        return $currentExpiry->addDays($newPlan->duration_days);
    }
    
    /**
     * Get subscription status for user and escort
     */
    public function getSubscriptionStatus($userId, $escortId)
    {
        $subscription = $this->getCurrentSubscription($userId, $escortId);
        
        if (!$subscription) {
            return [
                'status' => 'none',
                'plan' => null,
                'expires_at' => null
            ];
        }
        
        return [
            'status' => $subscription->status,
            'plan' => SubscriptionPlans::find($subscription->plan_id),
            'expires_at' => $subscription->expires_at,
            'subscription_type' => $subscription->subscription_type ?? 'new'
        ];
    }
}