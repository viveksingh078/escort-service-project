@extends('layouts.app')
@section('title', 'Choose Plan')

@section('content')
  <style>
    .choose-plan-page {
      background: radial-gradient(circle at top, #0d0f16, #000);
      min-height: 100vh;
      padding-bottom: 50px;
    }

    .pricing-title {
      letter-spacing: 1px;
      font-size: 28px;
      font-weight: 700;
      color: #fff;
    }

    .plan-card {
      border: 0;
      background: linear-gradient(160deg, #1b1e29, #11141d);
      color: #fff;
      box-shadow: 0 15px 40px rgba(0, 0, 0, .6);
      border-radius: 20px;
      overflow: hidden;
      position: relative;
      transition: transform .3s ease, box-shadow .3s ease;
      height: 100%;
    }

    .plan-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, .75);
    }

    /* Current Plan Styling */
    .plan-card.current-plan {
      background: linear-gradient(160deg, #2d3748, #1a202c);
      border: 2px solid #ffc107;
      box-shadow: 0 0 30px rgba(255, 193, 7, 0.4);
    }

    .current-badge {
      position: absolute;
      top: 14px;
      right: 14px;
      background: linear-gradient(45deg, #ffc107, #ff9800);
      color: #1a1d25;
      font-weight: 700;
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 12px;
      text-transform: uppercase;
      box-shadow: 0 0 15px rgba(255, 193, 7, .6);
    }

    .plan-badge {
      position: absolute;
      top: 14px;
      right: 14px;
      font-weight: 700;
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 12px;
      text-transform: uppercase;
    }

    .plan-badge.silver {
      background: linear-gradient(145deg, #C0C0C0, #E5E5E5);
      box-shadow: 0 4px 15px rgba(200, 200, 200, 0.6);
      color: #333;
    }

    .plan-badge.gold {
      background: linear-gradient(145deg, #FFD700, #FFC300);
      box-shadow: 0 4px 15px rgba(255, 215, 0, 0.6);
      color: #333;
    }

    .plan-badge.platinum {
      background: linear-gradient(145deg, #BFCFE0, #DDE5F2);
      box-shadow: 0 4px 15px rgba(189, 210, 230, 0.7);
      color: #333;
    }

    .plan-icon {
      font-size: 48px;
      margin-bottom: 15px;
    }

    .plan-name {
      font-size: 24px;
      font-weight: bold;
      color: #fff;
      margin-bottom: 10px;
    }

    .plan-price {
      font-size: 40px;
      font-weight: 900;
      color: #ffc107;
      margin-bottom: 5px;
    }

    .plan-duration {
      color: rgba(255, 255, 255, 0.7);
      font-size: 14px;
      margin-bottom: 20px;
    }

    .plan-features {
      list-style: none;
      padding: 0;
      margin: 20px 0;
      font-size: 13px;
      color: #b9bcc6;
    }

    .plan-features li {
      margin-bottom: 8px;
      display: flex;
      align-items: center;
    }

    .plan-features li i {
      color: #28c76f;
      margin-right: 8px;
    }

    /* Buttons */
    .btn-premium {
      background: linear-gradient(45deg, #ffc107, #ff9800);
      border: 0;
      color: #1a1d25;
      font-weight: 700;
      padding: 12px 16px;
      border-radius: 999px;
      font-size: 15px;
      text-transform: uppercase;
      transition: all .3s ease;
      width: 100%;
    }

    .btn-premium:hover {
      filter: brightness(0.95);
      box-shadow: 0 0 20px rgba(255, 193, 7, 0.6);
      transform: translateY(-2px);
    }

    .btn-premium:disabled {
      opacity: 0.8;
      cursor: not-allowed;
    }

    /* Modal Styles */
    .modal-content {
      background: linear-gradient(160deg, #1b1e29, #11141d);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      color: #fff;
    }

    .modal-header {
      background: linear-gradient(45deg, #ffc107, #ff9800);
      color: #1a1d25;
      border-radius: 20px 20px 0 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-body {
      background: linear-gradient(160deg, #1b1e29, #11141d);
    }

    .modal-footer {
      background: linear-gradient(160deg, #1b1e29, #11141d);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 0 0 20px 20px;
    }

    .form-control {
      background: #161a24;
      border: 1px solid rgba(255, 255, 255, .12);
      color: #fff;
      border-radius: 10px;
      font-size: 14px;
      padding: 10px 12px;
    }

    .form-control::placeholder {
      color: #8b8f98;
    }

    .form-control:focus {
      background: #161a24;
      border-color: #ffc107;
      color: #fff;
      box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    .form-label {
      color: #b9bcc6;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .btn-secondary {
      background: #6c757d;
      border: none;
      color: #fff;
    }

    .btn-warning {
      background: linear-gradient(45deg, #ffc107, #ff9800);
      border: none;
      color: #1a1d25;
      font-weight: 700;
    }

    .btn-warning:hover {
      filter: brightness(0.95);
      box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
    }
  </style>

  <div class="choose-plan-page">
    <div class="container py-5">
      <h3 class="mb-4 text-center pricing-title">Choose a Plan for {{ $escort->name }}</h3>
      
      @if(session('success'))
        <div class="alert alert-success text-center mb-4">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger text-center mb-4">
          @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
          @endforeach
        </div>
      @endif
      
      @if(Auth::guard('fan')->check())
        @if($currentSubscription['status'] !== 'none' && $currentSubscription['status'] === 'paid')
          <div class="alert alert-warning text-center mb-4">
            <div class="row align-items-center">
              <div class="col-md-8">
                <strong>Current Plan:</strong> {{ $currentSubscription['plan']->name }}
                <span class="badge bg-warning text-dark ms-2">${{ $currentSubscription['plan']->price }}</span>
                @if($currentSubscription['expires_at'])
                  <br><small>Expires: {{ \Carbon\Carbon::parse($currentSubscription['expires_at'])->format('M d, Y h:i A') }}</small>
                @endif
              </div>
              <div class="col-md-4">
                @if($currentSubscription['expires_at'])
                  @php
                    $endDate = \Carbon\Carbon::parse($currentSubscription['expires_at']);
                    $now = \Carbon\Carbon::now();
                    $daysLeft = $now->diffInDays($endDate, false);
                    $hoursLeft = $now->diffInHours($endDate, false);
                  @endphp
                  @if($daysLeft > 0)
                    <span class="badge bg-success text-white">
                      <i class="fa-solid fa-clock me-1"></i>{{ $daysLeft }} days left
                    </span>
                  @elseif($hoursLeft > 0)
                    <span class="badge bg-warning text-dark">
                      <i class="fa-solid fa-clock me-1"></i>{{ $hoursLeft }} hours left
                    </span>
                  @else
                    <span class="badge bg-danger text-white">
                      <i class="fa-solid fa-exclamation-triangle me-1"></i>Expired
                    </span>
                  @endif
                @endif
              </div>
            </div>
          </div>
        @endif
      @endif

      <div class="row g-4 justify-content-center">
        @forelse($plans as $plan)
          @php
            $currentPlan = $currentSubscription['plan'] ?? null;
            $isCurrentPlan = $currentPlan && $plan->id === $currentPlan->id;
            $isUpgrade = $currentPlan && $plan->price > $currentPlan->price;
            $isDowngrade = $currentPlan && $plan->price < $currentPlan->price;
          @endphp
          
          <div class="col-md-4">
            <div class="plan-card {{ $isCurrentPlan ? 'current-plan' : '' }}">
              
              @if($isCurrentPlan)
                <div class="current-badge">
                  <i class="fa-solid fa-crown me-1"></i>
                  CURRENT PLAN
                </div>
              @else
                @if($loop->iteration === 1)
                  <span class="plan-badge silver">Silver</span>
                @elseif($loop->iteration === 2)
                  <span class="plan-badge gold">Gold</span>
                @elseif($loop->iteration === 3)
                  <span class="plan-badge platinum">Platinum</span>
                @endif
              @endif

              <div class="text-center p-4">
                <!-- Plan Name -->
                <h5 class="plan-name">{{ $plan->name }}</h5>
                
                <!-- Star Rating -->
                <div class="mb-3">
                  @for($i = 1; $i <= $loop->iteration; $i++)
                    <i class="fa-solid fa-star text-warning" style="font-size: 20px;"></i>
                  @endfor
                  @for($i = $loop->iteration + 1; $i <= 3; $i++)
                    <i class="fa-regular fa-star text-white" style="font-size: 20px;"></i>
                  @endfor
                </div>
                
                <!-- Plan Price -->
                <div class="plan-price">${{ number_format($plan->price, 2) }}</div>
                <div class="plan-duration">for {{ $plan->duration_days }} days</div>
                <div class="plan-duration">≈ ${{ number_format(($plan->price / max(1, round($plan->duration_days / 30))), 2) }} / month</div>

                <!-- Plan Features -->
                @if(!empty($plan->selected_features))
                  <ul class="plan-features text-start">
                    @foreach($plan->selected_features as $featureName)
                      <li><i class="fa-solid fa-check"></i>{{ $featureName }}</li>
                    @endforeach
                  </ul>
                @else
                  <ul class="plan-features text-start">
                    <li><i class="fa-solid fa-check"></i>Access to premium content</li>
                    <li><i class="fa-solid fa-check"></i>Priority support</li>
                    <li><i class="fa-solid fa-check"></i>Duration: {{ $plan->duration_days }} days</li>
                  </ul>
                @endif

                <!-- Action Button -->
                @php
                  $showSubscriptionButtons = Auth::guard('fan')->check() && $currentSubscription['status'] !== 'none' && $currentSubscription['status'] === 'paid';
                  $isCurrentPlan = $currentPlan && $plan->id === $currentPlan->id;
                  $isUpgrade = $currentPlan && $plan->price > $currentPlan->price;
                  $isDowngrade = $currentPlan && $plan->price < $currentPlan->price;
                @endphp

                {{-- Debug: Show all variables --}}
                <div style="background: {{ $showSubscriptionButtons ? 'rgba(255,0,0,0.2)' : 'rgba(0,255,0,0.2)' }}; padding: 3px; margin-bottom: 8px; font-size: 9px; color: #000;">
                  <strong>DEBUG:</strong>
                  Fan: {{ Auth::guard('fan')->check() ? 'YES' : 'NO' }} |
                  Status: {{ $currentSubscription['status'] ?? 'none' }} |
                  Current: {{ $currentPlan ? $currentPlan->name : 'NONE' }} |
                  Selected: {{ $plan->name }} |
                  isCurrent: {{ $isCurrentPlan ? 'YES' : 'NO' }} |
                  isUpgrade: {{ $isUpgrade ? 'YES' : 'NO' }} |
                  isDowngrade: {{ $isDowngrade ? 'YES' : 'NO' }}
                </div>

                @if($showSubscriptionButtons)
                  @if($isCurrentPlan)
                    <button class="btn-premium" disabled style="background: #28a745 !important; color: white !important;">
                      <i class="fa-solid fa-check me-2"></i>
                      ACTIVE PLAN
                    </button>
                  @elseif($isDowngrade)
                    {{-- DOWNGRADE: Directly activate this plan --}}
                    <form method="POST" action="{{ route('subscription.downgrade') }}" style="display: inline;">
                      @csrf
                      <input type="hidden" name="new_plan_id" value="{{ $plan->id }}">
                      <input type="hidden" name="current_plan_id" value="{{ $currentPlan->id }}">
                      <button type="submit" class="btn-premium" style="background: #dc3545 !important; color: white !important;"
                              onclick="return confirm('Are you sure you want to downgrade to {{ $plan->name }}? This will activate immediately.')">
                        <i class="fa-solid fa-arrow-down me-2"></i>
                        DOWNGRADE TO {{ strtoupper($plan->name) }}
                      </button>
                    </form>
                  @elseif($isUpgrade)
                    {{-- UPGRADE: Redirect to choose-plan page --}}
                    <a href="{{ route('choose.plan', $escort->id) }}" class="btn-premium" style="background: #ffc107 !important; color: #000 !important; text-decoration: none;">
                      <i class="fa-solid fa-arrow-up me-2"></i>
                      UPGRADE TO {{ strtoupper($plan->name) }}
                    </a>
                  @else
                    {{-- This should never happen if logic is correct --}}
                    <button class="btn-premium" style="background: #6c757d !important; color: white !important;" disabled>
                      <i class="fa-solid fa-question me-2"></i>
                      INVALID PLAN
                    </button>
                  @endif
                @else
                  {{-- NO SUBSCRIPTION - Only show SELECT PLAN --}}
                  <button class="btn-premium" onclick="openSubscriptionModal({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->price }}, 'subscribe')">
                    <i class="fa-solid fa-shopping-cart me-2"></i>
                    SELECT PLAN
                  </button>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <p class="text-center">No plans configured.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Subscription Modal -->
  <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="subscriptionModalLabel">
            <i class="fa-solid fa-crown me-2"></i>
            Subscribe to <span id="modalPlanName"></span>
          </h5>
          <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <h4 class="text-warning">$<span id="modalPlanPrice"></span></h4>
            <p class="text-white">Complete your subscription details below</p>
          </div>
          
          <form method="POST" action="{{ route('billing') }}" id="subscriptionForm">
            @csrf
            <input type="hidden" name="escort_id" value="{{ $escort->id }}">
            <input type="hidden" name="plan_id" id="modalPlanId">
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" placeholder="Enter first name" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" placeholder="Enter last name" required>
              </div>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Address</label>
              <input type="text" class="form-control" name="address" placeholder="Enter your address" required>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">City</label>
                <input type="text" class="form-control" name="city" placeholder="Enter city" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">State</label>
                <input type="text" class="form-control" name="state" placeholder="Enter state" required>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Zip Code</label>
                <input type="text" class="form-control" name="zip_code" placeholder="Enter zip code" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Country</label>
                <input type="text" class="form-control" name="country" placeholder="Enter country" required>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
          <button type="submit" form="subscriptionForm" class="btn btn-warning">
            <i class="fa-solid fa-credit-card me-2"></i>
            Proceed to Payment
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Wait for document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM loaded, modal elements available');

      // Debug current subscription status
      @auth('fan')
        console.log('Fan is logged in');
        console.log('Current subscription status:', '{{ json_encode($currentSubscription) }}');
        console.log('Available plans:', '{{ $plans->map(function($plan) { return $plan->name . " ($" . $plan->price . ")"; })->join(", ") }}');

        // Debug: Check all billings for this user
        fetch('/debug-billing')
          .then(response => response.json())
          .then(data => {
            console.log('All billings for user:', data);
          })
          .catch(error => {
            console.error('Error fetching billings:', error);
            console.log('Debug route not found - checking manually...');
          });
      @else
        console.log('Fan is not logged in');
      @endauth
    });

    function openSubscriptionModal(planId, planName, planPrice, actionType) {
      try {
        console.log('Opening modal for plan:', planName, 'Price:', planPrice);
        
        // Set plan ID
        const modalPlanId = document.getElementById('modalPlanId');
        if (modalPlanId) {
          modalPlanId.value = planId;
          console.log('Plan ID set:', planId);
        } else {
          console.error('modalPlanId element not found');
        }
        
        // Set plan name
        const modalPlanName = document.getElementById('modalPlanName');
        if (modalPlanName) {
          modalPlanName.textContent = planName;
          console.log('Plan name set:', planName);
        } else {
          console.error('modalPlanName element not found');
        }
        
        // Set plan price
        const modalPlanPrice = document.getElementById('modalPlanPrice');
        if (modalPlanPrice) {
          modalPlanPrice.textContent = planPrice.toFixed(2);
          console.log('Plan price set:', planPrice.toFixed(2));
        } else {
          console.error('modalPlanPrice element not found');
        }
        
        // Update modal title based on action
        const modalTitle = document.getElementById('subscriptionModalLabel');
        if (modalTitle) {
          if (actionType === 'upgrade') {
            modalTitle.innerHTML = '<i class="fa-solid fa-arrow-up me-2"></i>Upgrade to ' + planName;
          } else if (actionType === 'downgrade') {
            modalTitle.innerHTML = '<i class="fa-solid fa-arrow-down me-2"></i>Downgrade to ' + planName;
          } else {
            modalTitle.innerHTML = '<i class="fa-solid fa-crown me-2"></i>Subscribe to ' + planName;
          }
          console.log('Modal title updated for action:', actionType);
        } else {
          console.error('subscriptionModalLabel element not found');
        }
        
        // Show modal - Try multiple methods
        const modalElement = document.getElementById('subscriptionModal');
        if (modalElement) {
          // Method 1: jQuery Bootstrap (most compatible)
          if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modalElement).modal('show');
            console.log('Modal shown using jQuery');
          }
          // Method 2: Bootstrap 5 (with error handling)
          else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
              const modal = new bootstrap.Modal(modalElement);
              modal.show();
              console.log('Modal shown using Bootstrap 5');
            } catch (bootstrapError) {
              console.log('Bootstrap method failed, using manual show');
              manualShowModal(modalElement);
            }
          }
          // Method 3: Manual show
          else {
            console.log('Using manual show method');
            manualShowModal(modalElement);
          }
        } else {
          console.error('subscriptionModal element not found');
          alert('Error: Modal not found. Please refresh the page.');
        }
        
      } catch (error) {
        console.error('Error in openSubscriptionModal:', error);
        alert('Error opening subscription form. Please try again.');
      }
    }

    // Close modal function
    function closeModal() {
      try {
        const modalElement = document.getElementById('subscriptionModal');
        if (modalElement) {
          console.log('Attempting to close modal...');
          
          // Method 1: jQuery Bootstrap (most compatible)
          if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modalElement).modal('hide');
            console.log('Modal closed using jQuery');
          }
          // Method 2: Bootstrap 5 (with error handling)
          else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
              // Try getInstance first (Bootstrap 5.1+)
              if (bootstrap.Modal.getInstance) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                  modal.hide();
                } else {
                  const newModal = new bootstrap.Modal(modalElement);
                  newModal.hide();
                }
              } else {
                // Fallback for older Bootstrap 5 versions
                const newModal = new bootstrap.Modal(modalElement);
                newModal.hide();
              }
              console.log('Modal closed using Bootstrap 5');
            } catch (bootstrapError) {
              console.log('Bootstrap method failed, using manual close');
              manualCloseModal(modalElement);
            }
          }
          // Method 3: Manual close
          else {
            console.log('Using manual close method');
            manualCloseModal(modalElement);
          }
          
          // Clear form data
          const form = document.getElementById('subscriptionForm');
          if (form) {
            form.reset();
            console.log('Form reset');
          }
        }
      } catch (error) {
        console.error('Error closing modal:', error);
        // Force manual close as last resort
        manualCloseModal(document.getElementById('subscriptionModal'));
      }
    }

    // Manual show function
    function manualShowModal(modalElement) {
      if (modalElement) {
        // Create backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
        
        // Show modal
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        modalElement.setAttribute('aria-hidden', 'false');
        modalElement.setAttribute('aria-modal', 'true');
        
        // Add body classes
        document.body.classList.add('modal-open');
        
        console.log('Modal shown manually');
      }
    }

    // Manual close function
    function manualCloseModal(modalElement) {
      if (modalElement) {
        modalElement.style.display = 'none';
        modalElement.classList.remove('show', 'fade');
        modalElement.setAttribute('aria-hidden', 'true');
        modalElement.removeAttribute('aria-modal');
        
        // Remove backdrop
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Remove body classes
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        console.log('Modal closed manually');
      }
    }

    // Add click handlers for backdrop close
    document.addEventListener('click', function(e) {
      const modalElement = document.getElementById('subscriptionModal');
      if (e.target === modalElement) {
        closeModal();
      }
    });

    // Add ESC key handler
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
      }
    });
  </script>
@endsection