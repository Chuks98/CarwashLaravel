<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Register - Voeautocare</title>
  <!-- Favicon -->
  <link href="{{ asset('assets/img/fav.png') }}" rel="icon">
  <link rel="stylesheet" href="{{ asset('dashboard-assets/css/styles.min.css') }}" />
  <style>
    .form-check-label {
      cursor: pointer;
    }

    #userFields {
      display: none;
    }
  </style>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex flex-column justify-content-center align-items-center">
      <!-- Registration Page Image -->
      <div class="text-center mt-5">
        <a href="{{ url('/') }}">
          <img src="{{ asset('assets/img/logo.png') }}" alt="Register Illustration" class="img-fluid" style="max-height: 200px;">
        </a>
      </div>
      <!-- Registration Form -->
      <div class="d-flex align-items-center justify-content-center w-100 flex-grow-1 mt-5 mb-5">
        <div class="row justify-content-center w-100">
          <div class="col-md-10 col-lg-8 col-xxl-6">
            <div class="card shadow mb-0">
              <div class="card-body">
                <h3 class="text-center fw-semibold mb-4">REGISTER</h3>
                <form id="registerForm" method="POST" action="{{ route('register') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="firstname" class="form-label">First Name</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="ti ti-user text-black"></i></span>
                        <input type="text" class="form-control text-black" id="firstname" name="firstname" placeholder="Enter your firstname" required>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="lastname" class="form-label">Last Name</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="ti ti-user text-black"></i></span>
                        <input type="text" class="form-control text-black" id="lastname" name="lastname" placeholder="Enter your lastname" required>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="ti ti-phone text-black"></i></span>
                      <input type="tel" class="form-control text-black" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light"><i class="ti ti-mail text-black"></i></span>
                      <input type="email" class="form-control text-black" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="password" class="form-label">Password</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="ti ti-lock text-black"></i></span>
                        <input type="password" class="form-control text-black" id="password" name="password" placeholder="Password" required>
                        <span class="input-group-text bg-light toggle-password" data-target="#password">
                          <i class="ti ti-eye text-black"></i>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="confirmPassword" class="form-label">Confirm Password</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="ti ti-lock text-black"></i></span>
                        <input type="password" class="form-control text-black" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required><br/>
                        <span class="input-group-text bg-light toggle-password" data-target="#confirmPassword">
                          <i class="ti ti-eye text-black"></i>
                        </span>
                      </div>
                      <small id="passwordMatchMsg" class="ms-2"></small>
                    </div>
                  </div>

                  <!-- Role Selection -->
                  <div class="mb-3" id="roleGroup">
                    <label class="form-label">Register As:</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="adminCheck" name="role" value="admin">
                      <label class="form-check-label" for="adminCheck">Admin</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="userCheck" name="role" value="user">
                      <label class="form-check-label" for="userCheck">User</label>
                    </div>
                    <div class="text-danger mt-1 d-none" id="roleError">Please select at least one role.</div>
                  </div>

                  <!-- User Additional Fields -->
                  <div id="userFields">
                    <div class="mb-3">
                      <label for="address" class="form-label">Residential Address</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light"><i class="ti ti-map-pin text-black"></i></span>
                        <input type="text" class="form-control text-black" id="address" name="address" placeholder="Enter your address">
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Car Details</label>
                      <div class="row">
                        <div class="col-md-4 mb-2">
                          <div class="input-group">
                            <span class="input-group-text bg-light">
                              <i class="ti ti-car text-black"></i>
                            </span>
                            <input type="text" class="form-control text-black" name="carName" placeholder="Car Name">
                          </div>
                        </div>
                        <div class="col-md-4 mb-2">
                          <div class="input-group">
                            <span class="input-group-text bg-light">
                              <i class="ti ti-dashboard text-black"></i>
                            </span>
                            <input type="text" class="form-control text-black" name="carModel" placeholder="Model">
                          </div>
                        </div>
                        <div class="col-md-4 mb-2">
                          <div class="input-group">
                            <span class="input-group-text bg-light">
                              <i class="ti ti-hash text-black"></i>
                            </span>
                            <input type="text" class="form-control text-black" name="plateNumber" placeholder="Plate Number">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Register</button>
                </form>

                <!-- Already Registered -->
                <div class="text-center">
                  <p class="mb-0">
                    Already registered?
                    <a href="{{ url('/login') }}" class="text-primary fw-semibold">Login</a>
                  </p>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="text-center py-3">
        <p class="mb-1"><strong>Voeautocare©</strong> All Rights Reserved</p>
        <p class="mb-0">
          Powered by
          <a href="https://chukwuma-onyedika9.web.app" target="_blank" class="text-primary fw-semibold">Mega-clouds</a>
        </p>
      </footer>
    </div>
  </div>

  <script src="{{ asset('dashboard-assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('dashboard-assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('dashboard-assets/js/app.min.js') }}"></script>

</body>
</html>
