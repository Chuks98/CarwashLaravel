<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - Voeautocare</title>
  <!-- Favicon -->
  <link href="{{ asset('assets/img/fav.png') }}" rel="icon">
  <link rel="stylesheet" href="{{ asset('dashboard-assets/css/styles.min.css') }}" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex flex-column justify-content-center align-items-center">
      <!-- Login Page Image -->
      <div class="text-center mt-5">
        <a href="{{ url('/') }}">
          <img src="{{ asset('assets/img/logo.png') }}" alt="Login Illustration" class="img-fluid"
            style="max-height: 200px;" />
        </a>
      </div>
      <!-- Login Form -->
      <div class="d-flex align-items-center justify-content-center w-100 flex-grow-1 mt-5 mb-5">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card shadow mb-0">
              <div class="card-body">
                <h3 class="text-center fw-semibold mb-4">LOGIN</h3>
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                  @csrf
                  <!-- Email Input -->
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="ti ti-user text-black"></i></span>
                      <input type="email" class="form-control text-black" id="email" name="email" placeholder="Enter your email"
                        value="{{ old('email') }}" required autofocus />
                    </div>
                    @error('email')
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>

                  <!-- Password Input -->
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="ti ti-lock text-black"></i></span>
                      <input type="password" class="form-control text-black" id="password" name="password"
                        placeholder="Enter your password" required />
                      <span class="input-group-text bg-light toggle-password" data-target="#password">
                        <i class="ti ti-eye text-black"></i>
                      </span>
                    </div>
                    @error('password')
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>

                  <!-- Submit Button -->
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Login</button>
                </form>

                <!-- Already Registered -->
                <div class="text-center">
                  <p class="mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary fw-semibold">Register</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="text-center py-3">
        <p class="mb-1">
          <strong class="px-1">Voeautocare©</strong>
          All Rights Reserved
        </p>
        <p class="mb-0"> Powered by
          <a href="https://chukwuma-onyedika9.web.app" target="_blank" class="text-primary fw-semibold">Mega-clouds</a>
        </p>
      </footer>
    </div>
  </div>

  <script src="{{ asset('dashboard-assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('dashboard-assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('dashboard-assets/js/app.min.js') }}"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
