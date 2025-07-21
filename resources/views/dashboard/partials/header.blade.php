@php
  // ✅ Get session data
  $user = session('user');        // Logged-in Admin/User data
  $role = session('role');        // 'admin' or 'user'
@endphp

<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <!-- Sidebar toggle (for mobile) -->
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>

      <!-- Home button -->
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}" aria-expanded="false">
          <i class="ti ti-home"></i>
        </a>
      </li>

      <!-- Notifications -->
      <li class="nav-item dropdown">
        <a class="nav-link" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="ti ti-bell"></i>
          <div class="notification bg-primary rounded-circle"></div>
        </a>
        <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
          <div class="message-body">
            <a href="javascript:void(0)" class="dropdown-item">Notifications</a>
          </div>
        </div>
      </li>
    </ul>

    <!-- Right side menu -->
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        
        <!-- Profile dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link d-flex align-items-center gap-2" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            
            <!-- ✅ Show dynamic firstname OR Guest -->
            <span class="d-none d-md-inline">
              Welcome, 
              <strong id="firstnameInHeader">
                {{ $user['firstname'] ?? 'Guest' }}
              </strong>
            </span>
            
            <!-- Profile image (static placeholder for now) -->
            <img src="{{ asset('dashboard-assets/images/profile/user-1.jpg') }}" alt="Profile" width="35" height="35" class="rounded-circle">
          </a>

          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
            <div class="message-body">

              <!-- Home link -->
              <div class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-home fs-6"></i>
                <a href="{{ url('/') }}" class="mb-0 fs-3 text-decoration-none text-dark">Home</a>
              </div>

              <!-- ✅ Show logged-in role -->
              <div class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-user-check fs-6"></i>
                <p class="mb-0 fs-3 text-capitalize">
                  {{ ucfirst($role ?? 'guest') }}
                </p>
              </div>

              <!-- Profile link -->
              @if($user)
                <div class="d-flex align-items-center gap-2 dropdown-item">
                  <i class="ti ti-user fs-6"></i>
                  <a href="{{ url('/dashboard/profile') }}" class="mb-0 fs-3 text-decoration-none text-dark">My Profile</a>
                </div>
              @endif

              <!-- Logout -->
              @if($user)
                <form action="{{ route('logout') }}" method="POST" class="mx-3 mt-2">
                  @csrf
                  <button type="submit" class="btn btn-outline-primary d-block logout-btn w-100">
                    Logout
                  </button>
                </form>
              @else
                <!-- ✅ If guest, show Login -->
                <div class="d-flex align-items-center gap-2 dropdown-item">
                  <i class="ti ti-login fs-6"></i>
                  <a href="{{ url('/login') }}" class="mb-0 fs-3 text-decoration-none text-dark">Login</a>
                </div>
              @endif

            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
