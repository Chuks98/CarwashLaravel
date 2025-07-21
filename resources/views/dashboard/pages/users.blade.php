@php
  // ✅ Get user & role directly from session
  $role = session('role');          // 'admin' or 'user'
  $user = session('user');          // Contains full logged-in object (Admin or User)
@endphp

<!-- Get user role -->
<div id="user-role" data-role="{{ $role }}"></div>

<!-- All Users Table -->
<div class="col-12 mb-4">
  <div class="card shadow h-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0">All Registered Users</h4>
      </div>
      <div class="table-responsive mt-4">
        <div class="row mb-3">
            <div class="col-md-4 mb-5">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email or phone" />
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button id="resetFilters" class="btn btn-primary w-100">Reset Filters</button>
            </div>
            </div>

        <table class="table table-hover align-middle text-nowrap fs-3" id="userTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Car</th>
              <th>Plate</th>
              <th>Status</th>
              <th>Joined</th>
            </tr>
          </thead>
          <tbody>
            <!-- jQuery will populate this -->
          </tbody>
        </table>
        <div class="mt-3 text-center" id="userPaginationControls">
          <!-- Optional: Pagination controls -->
        </div>
      </div>
    </div>
  </div>
</div>
