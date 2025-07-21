@php
  // ✅ Get logged-in role & user from session
  $role = session('role');     // 'admin' or 'user'
  $user = session('user');     // Full user data (Admin/User)
@endphp

<!-- Sidebar Start -->
<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <div class="logo">
        <a href="/">
          <h3>VOE<span>AUTO</span>CARE</h3>
        </a>
      </div>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-6"></i>
      </div>
    </div>

    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">

        <!-- ✅ Section: Functional Links (All Users) -->
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Functions</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="/dashboard/profile">
            <i class="ti ti-user"></i>
            <span class="hide-menu">Profile</span>
          </a>
        </li>

        {{-- ✅ Only show for normal USER --}}
        @if($role === 'user')
          <li class="sidebar-item">
            <a class="sidebar-link" href="/dashboard/subscriptions">
              <i class="ti ti-credit-card"></i>
              <span class="hide-menu">Subscriptions</span>
            </a>
          </li>
        @endif

        <li class="sidebar-item">
          <a class="sidebar-link" href="/dashboard/wash-history">
            <i class="ti ti-history"></i>
            <span class="hide-menu">Wash History</span>
          </a>
        </li>

        <!-- ✅ Section: Subscription & Payments (All Users) -->
        <li class="nav-small-cap mt-3">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Subscription</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="/dashboard/plans">
            <i class="ti ti-coin"></i>
            <span class="hide-menu">Plans</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="/dashboard/transactions">
            <i class="ti ti-report-money"></i>
            <span class="hide-menu">Subscription history</span>
          </a>
        </li>

        <!-- ✅ Section: Admin Management -->
        @if($role === 'admin')
          <li class="nav-small-cap mt-3">
            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
            <span class="hide-menu">Management</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/dashboard/users">
              <i class="ti ti-users"></i>
              <span class="hide-menu">Users</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/dashboard/blog">
              <i class="ti ti-news"></i>
              <span class="hide-menu">Blog</span>
            </a>
          </li>
        @endif

        <!-- ✅ Section: Account -->
        <li>
          <span class="sidebar-divider lg"></span>
        </li>

        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Account</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="/dashboard/change-password">
            <i class="ti ti-key"></i>
            <span class="hide-menu">Change Password</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link logout-btn" href="{{ route('logout') }}">
            <i class="ti ti-logout"></i>
            <span class="hide-menu">Logout</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<!-- Sidebar End -->
