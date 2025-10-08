 <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.dashboard') }}">
              <i class="typcn typcn-device-desktop menu-icon"></i>
              <span class="menu-title">Dashboard
              </span>
              <div class="badge badge-danger">new</div>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.profile.settings') }}">
              <i class="typcn typcn-user-outline menu-icon"></i>
              <span class="menu-title">Profile Settings</span>
            </a>
          </li>

<<<<<<< HEAD
=======
          <!-- Escort Menu -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#album" aria-expanded="false" aria-controls="album">
              <i class="typcn typcn-group menu-icon"></i> <!-- Icon for Escort -->
              <span class="menu-title">Album</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="album">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('escort.photos') }}">Photos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('escort.videos') }}">Videos</a>
                </li>
              </ul>
            </div>
          </li>

>>>>>>> 23c30d7 (Escort project)
          <li class="nav-item">
              <a class="nav-link" href="{{ route('escort.messages') }}">
                  <i class="typcn typcn-messages menu-icon"></i>
                  <span class="menu-title">Messages</span>
              </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.account.settings') }}">
              <i class="typcn typcn-user-add-outline menu-icon"></i>
              <span class="menu-title">Account Settings</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.verification') }}">
              <i class="typcn typcn-lock-closed-outline menu-icon"></i>
              <span class="menu-title">Verification</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.payouts') }}">
              <i class="typcn typcn-export-outline menu-icon"></i>
              <span class="menu-title">Payout Details</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.earnings') }}">
              <i class="typcn typcn-chart-bar-outline menu-icon"></i>
              <span class="menu-title">Your Earnings</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.subscribers') }}">
              <i class="typcn typcn-user-add-outline menu-icon"></i>
              <span class="menu-title">My Subscribers</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.referrals') }}">
              <i class="typcn typcn-arrow-forward-outline menu-icon"></i>
              <span class="menu-title">Referrals</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('escort.logout') }}">
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