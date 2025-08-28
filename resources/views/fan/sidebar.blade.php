 <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('fan.dashboard') }}">
              <i class="typcn typcn-device-desktop menu-icon"></i>
              <span class="menu-title">Dashboard
              </span>
              <div class="badge badge-danger">new</div>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('fan.profile.settings') }}">
              <i class="typcn typcn-user-outline menu-icon"></i>
              <span class="menu-title">Profile Settings</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('fan.payment.history') }}">
              <i class="typcn typcn-credit-card menu-icon"></i>
              <span class="menu-title">Payment History</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('fan.subscriptions') }}">
              <i class="typcn typcn-bookmark menu-icon"></i>
              <span class="menu-title">My Subscriptions</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('fan.cards') }}">
              <i class="typcn typcn-tabs-outline menu-icon"></i>
              <span class="menu-title">My Cards</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('fan.referrals') }}">
              <i class="typcn typcn-group-outline menu-icon"></i>
              <span class="menu-title">Referrals</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('fan.logout') }}">
              <i class="typcn typcn-power-outline menu-icon"></i>
              <span class="menu-title">Logout</span>
            </a>
          </li>



          {{-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="typcn typcn-document-text menu-icon"></i>
              <span class="menu-title">UI Elements</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
              </ul>
            </div>
          </li> --}}

        </ul>
      </nav>