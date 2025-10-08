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
        </ul>
      </div>
    </li>

<<<<<<< HEAD
    <!-- For Membership -->
=======
    <!-- for Countries Flag -->

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#countriesFlag" aria-expanded="false"
        aria-controls="countriesFlag">
        <i class="typcn typcn-world menu-icon"></i> <!-- Icon for Countries Flag -->
        <span class="menu-title">Countries Flag</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="countriesFlag">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.countries.create') }}">Add Country</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.countries.manage') }}">Manage Countries</a>
          </li>
        </ul>
      </div>

      <!-- For Membership -->
>>>>>>> 23c30d7 (Escort project)
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#membership" aria-expanded="false" aria-controls="membership">
        <i class="typcn typcn-credit-card menu-icon"></i>
        <span class="menu-title">Membership</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="membership">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.membership.features') }}">Features </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.membership') }}">Membership Plans</a>
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
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.paymentGateway') }}">Payment Gateways</a>
          </li>
        </ul>
      </div>
    </li>

    <!--For Ads Space-->
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#adsSpace" aria-expanded="false" aria-controls="adsSpace">
        <i class="typcn typcn-chart-bar-outline menu-icon"></i>
        <span class="menu-title">Ads Space</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="adsSpace">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.ads') }}">Ads</a>
          </li>
        </ul>
      </div>
    </li>

    <!-- For Support -->
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#support" aria-expanded="false" aria-controls="support">
        <i class="typcn typcn-headphones menu-icon"></i>
        <span class="menu-title">Support</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="support">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.faqs') }}">Faqs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.tickets') }}">Tickets</a>
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