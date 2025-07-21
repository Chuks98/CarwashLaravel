@php
  // ✅ Get user & role directly from session
  $role = session('role');          // 'admin' or 'user'
  $user = session('user');          // Contains full logged-in object (Admin or User)
@endphp

<div class="row align-items-stretch">
  <!-- ✅ Profile Form -->
  <div class="col-md-6 mb-4 d-flex flex-column">
    <div class="card shadow h-100">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">My Profile</h3>

        <form id="profileForm">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="firstname" class="form-label">First Name</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="ti ti-user text-black"></i></span>
                <input 
                  type="text" 
                  class="form-control text-black" 
                  id="firstname" 
                  name="firstname" 
                  value="{{ $user['firstname'] ?? '' }}" 
                  required
                >
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <label for="lastname" class="form-label">Last Name</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="ti ti-user text-black"></i></span>
                <input 
                  type="text" 
                  class="form-control text-black" 
                  id="lastname" 
                  name="lastname" 
                  value="{{ $user['lastname'] ?? '' }}" 
                  required
                >
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="ti ti-phone text-black"></i></span>
              <input 
                type="tel" 
                class="form-control text-black" 
                id="phone" 
                name="phone" 
                value="{{ $user['phone'] ?? '' }}" 
                required
              >
            </div>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="ti ti-mail text-black"></i></span>
              <input 
                type="email" 
                class="form-control text-black" 
                id="email" 
                name="email" 
                value="{{ $user['email'] ?? '' }}" 
                required
              >
            </div>
          </div>

          {{-- ✅ Only show these fields for USERS --}}
          @if($role === 'user')
            <div class="mb-3">
              <label for="address" class="form-label">Residential Address</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="ti ti-map-pin text-black"></i></span>
                <input 
                  type="text" 
                  class="form-control text-black" 
                  id="address" 
                  name="address" 
                  value="{{ $user['address'] ?? '' }}"
                >
              </div>
            </div>

            <label class="form-label">Car Details</label>
            <div class="row">
              <div class="col-md-4 mb-2">
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="ti ti-car text-black"></i></span>
                  <input 
                    type="text" 
                    class="form-control text-black" 
                    id="carName" 
                    name="carName" 
                    placeholder="Car Name" 
                    value="{{ $user['carName'] ?? '' }}"
                  >
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="ti ti-dashboard text-black"></i></span>
                  <input 
                    type="text" 
                    class="form-control text-black"  
                    id="carModel" 
                    name="carModel" 
                    placeholder="Model" 
                    value="{{ $user['carModel'] ?? '' }}">
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="ti ti-hash text-black"></i></span>
                  <input 
                    type="text" 
                    class="form-control text-black" 
                    id="plateNumber" 
                    name="plateNumber" 
                    placeholder="Plate Number" 
                    value="{{ $user['plateNumber'] ?? '' }}"
                  >
                </div>
              </div>
            </div>
          @endif

          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="ti ti-id-badge text-black"></i></span>
              <input 
                type="text" 
                class="form-control text-black" 
                id="role" 
                name="role" 
                value="{{ ucfirst($role) }}" 
                readonly
              >
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 mt-3">Update Profile</button>
        </form>
      </div>
    </div>
  </div>

  <!-- ✅ Profile Summary Display -->
  <div class="col-md-6 mb-4 d-flex flex-column">
    <div class="card shadow bg-light h-100">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">My Info</h3>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><strong>First Name:</strong> <span id="displayFirstname" class="text-black">{{ $user['firstname'] ?? '' }}</span></li>
          <li class="list-group-item"><strong>Last Name:</strong> <span id="displayLastname" class="text-black">{{ $user['lastname'] ?? '' }}</span></li>
          <li class="list-group-item"><strong>Phone:</strong> <span id="displayPhone" class="text-black">{{ $user['phone'] ?? '' }}</span></li>
          <li class="list-group-item"><strong>Email:</strong> <span id="displayEmail" class="text-black">{{ $user['email'] ?? '' }}</span></li>

          {{-- ✅ Only show extra info for USERS --}}
          @if($role === 'user')
            <li class="list-group-item"><strong>Address:</strong> <span id="displayAddress" class="text-black">{{ $user['address'] ?? 'N/A' }}</span></li>
            <li class="list-group-item"><strong>Car Name:</strong> <span id="displayCarName" class="text-black">{{ $user['carName'] ?? 'N/A' }}</span></li>
            <li class="list-group-item"><strong>Car Model:</strong> <span id="displayCarModel" class="text-black">{{ $user['carModel'] ?? 'N/A' }}</span></li>
            <li class="list-group-item"><strong>Plate Number:</strong> <span id="displayPlateNumber" class="text-black">{{ $user['plateNumber'] ?? 'N/A' }}</span></li>
            <li class="list-group-item">
              <strong>Status:</strong>
              @if(($user['status'] ?? '') === 'active')
                <span class="badge bg-success text-uppercase">Active</span>
              @else
                <span class="badge bg-danger text-uppercase">Inactive</span>
              @endif
            </li>
          @endif

          <li class="list-group-item"><strong>Role:</strong> <span id="displayRole">{{ ucfirst($role) }}</span></li>
        </ul>
      </div>
    </div>
  </div>
</div>
