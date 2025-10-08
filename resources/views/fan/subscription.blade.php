@extends('fan.layout')
@section('title', 'Fan Subscription')
@section('content')

<<<<<<< HEAD
=======
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background: #ffffff; border-radius: 16px;">
                <div class="card-body p-4">
                    
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0">
                            <i class="fa-solid fa-crown text-warning me-2"></i>
                            My Subscriptions
                        </h3>
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold">
                            <i class="fa-solid fa-user me-1"></i>
                            {{ Auth::guard('fan')->user()->first_name }}
                        </span>
                    </div>

                    <!-- Current Active Subscription -->
                    @php
                        $activeBilling = App\Models\Billing::where('fan_id', Auth::guard('fan')->id())
                            ->where('status', 'active')
                            ->with('plan', 'escort')
                            ->latest()
                            ->first();
                    @endphp

                    @if($activeBilling)
                        <div class="alert mb-4" style="background-color: #fff3cd; border: 2px solid #ffc107; border-radius: 12px;">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="text-dark mb-2">
                                        <i class="fa-solid fa-check-circle text-warning me-2"></i>
                                        Active Subscription
                                    </h5>
                                    <p class="text-dark mb-1">
                                        <strong>Plan:</strong> {{ $activeBilling->plan->name ?? 'N/A' }} 
                                        <span class="badge bg-warning text-dark ms-2 fw-bold">${{ $activeBilling->amount }}</span>
                                    </p>
                                    <p class="text-dark mb-1">
                                        <strong>Escort:</strong> {{ $activeBilling->escort->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-dark mb-1">
                                        <strong>Subscribed On:</strong> {{ \Carbon\Carbon::parse($activeBilling->created_at)->format('d M Y, h:i A') }}
                                    </p>
                                    <p class="text-dark mb-1">
                                        <strong>Valid Until:</strong> 
                                        @if($activeBilling->end_date)
                                            {{ \Carbon\Carbon::parse($activeBilling->end_date)->format('d M Y, h:i A') }}
                                        @else
                                            Lifetime
                                        @endif
                                    </p>
                                    @if($activeBilling->end_date)
                                        @php
                                            $endDate = \Carbon\Carbon::parse($activeBilling->end_date);
                                            $now = \Carbon\Carbon::now();
                                            $daysLeft = $now->diffInDays($endDate, false);
                                            $hoursLeft = $now->diffInHours($endDate, false);
                                        @endphp
                                        <p class="text-dark mb-0">
                                            <strong>Time Remaining:</strong> 
                                            @if($daysLeft > 0)
                                                <span class="badge bg-success text-white fw-bold">
                                                    <i class="fa-solid fa-clock me-1"></i>
                                                    {{ $daysLeft }} days left
                                                </span>
                                            @elseif($hoursLeft > 0)
                                                <span class="badge bg-warning text-dark fw-bold">
                                                    <i class="fa-solid fa-clock me-1"></i>
                                                    {{ $hoursLeft }} hours left
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white fw-bold">
                                                    <i class="fa-solid fa-exclamation-triangle me-1"></i>
                                                    Expired
                                                </span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 fw-bold">
                                        <i class="fa-solid fa-star me-1"></i>
                                        ACTIVE
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert mb-4" style="background-color: #f8f9fa; border: 2px solid #6c757d; border-radius: 12px;">
                            <h5 class="text-dark mb-2">
                                <i class="fa-solid fa-exclamation-triangle text-secondary me-2"></i>
                                No Active Subscription
                            </h5>
                            <p class="text-secondary mb-0">
                                You don't have any active subscriptions. Choose a plan below to get started!
                            </p>
                        </div>
                    @endif

                    <!-- Available Plans -->
                    <h4 class="text-dark mb-3">
                        <i class="fa-solid fa-gem text-warning me-2"></i>
                        Available Plans
                    </h4>

                    @php
                        $plans = App\Models\SubscriptionPlans::orderBy('price')->get();
                        $currentPlanId = $activeBilling->plan_id ?? null;
                    @endphp

                    <div class="row">
                        @foreach($plans as $plan)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 position-relative" 
                                     style="background: {{ $plan->id == $currentPlanId ? 'linear-gradient(135deg, #ffc107, #ffca2c)' : '#ffffff' }}; 
                                            border: {{ $plan->id == $currentPlanId ? '3px solid #ffc107' : '2px solid #dee2e6' }};
                                            border-radius: 12px; 
                                            {{ $plan->id == $currentPlanId ? 'box-shadow: 0 0 20px rgba(255, 193, 7, 0.5);' : 'box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);' }}">
                                    
                                    <!-- Current Plan Badge -->
                                    @if($plan->id == $currentPlanId)
                                        <div class="position-absolute top-0 start-50 translate-middle">
                                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold">
                                                <i class="fa-solid fa-crown me-1"></i>
                                                CURRENT PLAN
                                            </span>
                                        </div>
                                    @endif

                                    <div class="card-body text-center p-4">
                                        <!-- Plan Icon -->
                                        <div class="mb-3">
                                            @if(strtolower($plan->name) == 'silver')
                                                <i class="fa-solid fa-medal text-secondary" style="font-size: 48px;"></i>
                                            @elseif(strtolower($plan->name) == 'gold')
                                                <i class="fa-solid fa-trophy text-warning" style="font-size: 48px;"></i>
                                            @elseif(strtolower($plan->name) == 'platinum')
                                                <i class="fa-solid fa-crown text-info" style="font-size: 48px;"></i>
                                            @else
                                                <i class="fa-solid fa-star text-primary" style="font-size: 48px;"></i>
                                            @endif
                                        </div>

                                        <!-- Plan Name -->
                                        <h5 class="text-dark fw-bold mb-2">{{ $plan->name }}</h5>
                                        
                                        <!-- Plan Price -->
                                        <div class="mb-3">
                                            <span class="text-dark fs-2 fw-bold">${{ $plan->price }}</span>
                                            <span class="text-secondary">/month</span>
                                        </div>

                                        <!-- Plan Features -->
                                        <div class="mb-4">
                                            @if(isset($plan->description) && $plan->description)
                                                <p class="text-secondary small">{{ $plan->description }}</p>
                                            @endif
                                            
                                            @if($plan->selectedFeatures && count($plan->selectedFeatures) > 0)
                                                <ul class="list-unstyled text-dark small">
                                                    @foreach($plan->selectedFeatures as $feature)
                                                        <li><i class="fa-solid fa-check text-warning me-2"></i>{{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <ul class="list-unstyled text-dark small">
                                                    <li><i class="fa-solid fa-check text-warning me-2"></i>Access to premium content</li>
                                                    <li><i class="fa-solid fa-check text-warning me-2"></i>Priority support</li>
                                                    <li><i class="fa-solid fa-check text-warning me-2"></i>Duration: {{ $plan->duration_days ?? 30 }} days</li>
                                                </ul>
                                            @endif
                                        </div>

                                        <!-- Action Button -->
                                        @php
                                          $isCurrentPlan = $plan->id == $currentPlanId;
                                          $isUpgrade = $activeBilling && $plan->price > ($activeBilling->plan->price ?? 0);
                                          $isDowngrade = $activeBilling && $plan->price < ($activeBilling->plan->price ?? 0);
                                        @endphp

                                        {{-- Debug info --}}
                                        <div style="background: rgba(255,0,0,0.1); padding: 5px; margin-bottom: 10px; font-size: 10px; color: red;">
                                          Plan: {{ $plan->name }} (${{ $plan->price }}) |
                                          Current: {{ $activeBilling->plan->name ?? 'N/A' }} (${{ $activeBilling->plan->price ?? 'N/A' }}) |
                                          isCurrent: {{ $isCurrentPlan ? 'YES' : 'NO' }} |
                                          isUpgrade: {{ $isUpgrade ? 'YES' : 'NO' }} |
                                          isDowngrade: {{ $isDowngrade ? 'YES' : 'NO' }}
                                        </div>

                                        @if($isCurrentPlan)
                                          <button class="btn btn-warning btn-lg w-100 fw-bold text-dark" disabled>
                                            <i class="fa-solid fa-check me-2"></i>
                                            ACTIVE PLAN
                                          </button>
                                        @elseif($isDowngrade)
                                          {{-- DOWNGRADE: Directly activate this plan --}}
                                          <form method="POST" action="{{ route('subscription.downgrade') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="new_plan_id" value="{{ $plan->id }}">
                                            <input type="hidden" name="current_plan_id" value="{{ $activeBilling->plan->id }}">
                                            <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold"
                                                    onclick="return confirm('Are you sure you want to downgrade to {{ $plan->name }}? This will activate immediately.')">
                                              <i class="fa-solid fa-arrow-down me-2"></i>
                                              DOWNGRADE TO {{ strtoupper($plan->name) }}
                                            </button>
                                          </form>
                                        @elseif($isUpgrade)
                                          {{-- UPGRADE: Redirect to choose-plan page --}}
                                          <a href="{{ route('choose.plan', ['escort_id' => $activeBilling->escort_id]) }}" 
                                             class="btn btn-warning btn-lg w-100 fw-bold text-dark">
                                            <i class="fa-solid fa-arrow-up me-2"></i>
                                            UPGRADE TO {{ strtoupper($plan->name) }}
                                          </a>
                                        @else
                                          {{-- No active subscription - SELECT PLAN --}}
                                          <a href="{{ route('home') }}" 
                                             class="btn btn-outline-warning btn-lg w-100 fw-bold">
                                            <i class="fa-solid fa-shopping-cart me-2"></i>
                                            SELECT PLAN
                                          </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    <!-- Subscription History -->
                    <div class="mt-5">
                        <h4 class="text-dark mb-3">
                            <i class="fa-solid fa-history text-warning me-2"></i>
                            Subscription History
                        </h4>

                        @php
                            $allBillings = App\Models\Billing::where('fan_id', Auth::guard('fan')->id())
                                ->with('plan', 'escort')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
                        @endphp

                        @if($allBillings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-warning">
                                        <tr>
                                            <th class="text-dark">Date</th>
                                            <th class="text-dark">Plan</th>
                                            <th class="text-dark">Escort</th>
                                            <th class="text-dark">Amount</th>
                                            <th class="text-dark">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allBillings as $billing)
                                            <tr>
                                                <td class="text-dark">{{ $billing->created_at->format('d M Y') }}</td>
                                                <td class="text-dark">{{ $billing->plan->name ?? 'N/A' }}</td>
                                                <td class="text-dark">{{ $billing->escort->name ?? 'N/A' }}</td>
                                                <td class="text-dark">${{ $billing->amount }}</td>
                                                <td>
                                                    @if($billing->status == 'active')
                                                        <span class="badge bg-warning text-dark">Active</span>
                                                    @elseif($billing->status == 'pending')
                                                        <span class="badge bg-secondary">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ ucfirst($billing->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert" style="background-color: #f8f9fa; border: 2px solid #6c757d; border-radius: 12px;">
                                <p class="text-secondary mb-0">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    No subscription history found.
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.badge {
    font-size: 0.9em;
}
</style>
>>>>>>> 23c30d7 (Escort project)

@endsection