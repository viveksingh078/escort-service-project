<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('main.dashboard') }}">
        <i class="typcn typcn-device-desktop menu-icon"></i>
        <span class="menu-title">Dashboard
        </span>
        <div class="badge badge-danger">new</div>
      </a>
    </li>

    <!-- Escort Menu -->
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#escortMenu" aria-expanded="false" aria-controls="escortMenu">
        <i class="typcn typcn-group menu-icon"></i> <!-- Icon for Escort -->
        <span class="menu-title">Escort</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="escortMenu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.escort.category.create') }}">Add Category</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.escort.create') }}">Add Escort</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.escort.manage') }}">Manage Escort</a>
          </li>
        </ul>
      </div>
    </li>

    <!-- Fan Menu -->
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#fanMenu" aria-expanded="false" aria-controls="fanMenu">
        <i class="typcn typcn-star menu-icon"></i> <!-- Icon for Fan -->
        <span class="menu-title">Fan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="fanMenu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.fan.category.create') }}">Add Category</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.fan.create') }}">Add Fan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.fan.manage') }}">Manage Fan</a>
          </li>
        </ul>
      </div>
    </li>


    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
        <i class="typcn typcn-document-text menu-icon"></i>
        <span class="menu-title">Settings</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="settings">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.profile.settings') }}">Profile Settings</a>
          </li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.settings') }}">Web Settings</a>
          </li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.cities') }}">Cities</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.states') }}">States</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.countries') }}">Countries</a></li>
        </ul>
      </div>
    </li>


    <!-- For Membership -->

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#membership" aria-expanded="false" aria-controls="membership">
        <i class="typcn typcn-credit-card menu-icon"></i>
        <span class="menu-title">Membership</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="membership">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.subscription.index') }}">Features </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.subscription.create') }}">Add New Plan</a>
          </li>
        </ul>
      </div>
    </li>

    <!-- For Checkout -->
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#checkout" aria-expanded="false" aria-controls="checkout">
        <i class="typcn typcn-shopping-cart menu-icon"></i>
        <span class="menu-title">Checkout</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="checkout">
        <ul class="nav flex-column sub-menu">
          <!-- Payment Gateway Overview -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.paymentgateway') }}">
              <i class="fas fa-credit-card"></i>
              <span>Payment Gateway</span>
            </a>
          </li>

          <!-- BTC Pay -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.payment.btcpay.page') }}">
              <i class="fas fa-coins"></i>
              <span>BTC Pay</span>
            </a>
          </li>



        </ul>
      </div>
    </li>



    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.logout') }}">
        <i class="typcn typcn-power-outline menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>


  </ul>
</nav>