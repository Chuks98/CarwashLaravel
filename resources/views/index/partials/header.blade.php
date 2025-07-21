<!-- Top Bar Start -->
<div class="top-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-12">
                <div class="logo">
                    <a href="/">
                        <h3>VOE<span>AUTO</span>CARE</h3>
                        <!-- <img src="assets/img/logo.jpg" alt="Logo"> -->
                    </a>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 d-none d-lg-block">
                <div class="row">
                    <div class="col-4">
                        <div class="top-bar-item">
                            <div class="top-bar-icon">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="top-bar-text">
                                <h3>Opening Hour</h3>
                                <p>Mon - Fri, 8:00 - 9:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="top-bar-item">
                            <div class="top-bar-icon">
                                <i class="fa fa-phone-alt"></i>
                            </div>
                            <div class="top-bar-text">
                                <h3>Call Us</h3>
                                <p>+012 345 6789</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="top-bar-item">
                            <div class="top-bar-icon">
                                <i class="far fa-envelope"></i>
                            </div>
                            <div class="top-bar-text">
                                <h3>Email Us</h3>
                                <p>info@example.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Top Bar End -->


<!-- Nav Bar Start -->
<div class="nav-bar">
  <div class="container">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
      <a href="#" class="navbar-brand">MENU</a>
      <button
        type="button"
        class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#navbarCollapse"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      @php
        $sessionUser = session('user'); // logged in user data (array)
        $sessionRole = session('role'); // 'admin' or 'user'
      @endphp

      <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
        <div class="navbar-nav">
          <a href="/" class="nav-item nav-link">Home</a>
          <a href="/about" class="nav-item nav-link">About</a>
          <a href="/service" class="nav-item nav-link">Service</a>
          <a href="/price" class="nav-item nav-link">Price</a>
          <a href="/location" class="nav-item nav-link">Washing Points</a>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
            <div class="dropdown-menu">
              <a href="/blogs" class="dropdown-item">Blog Grid</a>
              <a href="/single" class="dropdown-item">Detail Page</a>
              <a href="/team" class="dropdown-item">Team Member</a>
              <a href="/booking" class="dropdown-item">Schedule Booking</a>
            </div>
          </div>
          <a href="/contact" class="nav-item nav-link">Contact</a>

          <!-- ✅ Mobile view: Dashboard/Login/Register -->
          @if($sessionUser)
            <div class="nav-item dropdown d-lg-none">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src="/dashboard-assets/images/profile/user-1.jpg" class="rounded-circle" width="24" height="24" alt="User Avatar">
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="/dashboard">Dashboard</a>
                <a class="dropdown-item logout-btn" href="{{ route('logout') }}">Logout</a>
              </div>
            </div>
          @else
            <a href="/login" class="nav-item nav-link d-lg-none">Login</a>
            <a href="/register" class="nav-item nav-link d-lg-none">Register</a>
          @endif
        </div>

        <!-- ✅ Desktop view: Right side buttons -->
        <div class="d-none d-lg-flex ms-auto gap-2">
          @if($sessionUser)
            <div class="nav-item dropdown">
              <a class="nav-link d-flex align-items-center gap-2" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="d-none d-md-inline text-white">
                  Welcome, <strong>{{ $sessionUser['firstname'] ?? 'User' }}</strong>
                </span>
                <img src="/dashboard-assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="/dashboard">Dashboard</a>
                <div class="dropdown-divider"></div>
                <a id="logout-btn-desktop" class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a>
              </div>
            </div>
          @else
            <a class="btn btn-custom btn-sm me-2" href="/login">Login</a>
            <a class="btn btn-custom btn-sm" href="/register">Register</a>
          @endif
        </div>
      </div>
    </nav>
  </div>
</div>
<!-- Nav Bar End -->
