@php
  // ✅ Get user & role directly from session
  $role = session('role');          // 'admin' or 'user'
  $user = session('user');          // Contains full logged-in object (Admin or User)
@endphp

<!-- For activating auto billing -->
<div class="mb-4">
  <div class="card shadow">
    <div class="card-body d-flex align-items-center justify-content-between">
      <h4 class="card-title mb-0">Activate Auto Billing</h4>

      <!-- Toggle Switch -->
      <label class="small-switch">
        <input type="checkbox" id="autoBillingToggle" {{ $user['autoBilling'] ? 'checked' : '' }}>
        <span class="slider"></span>
      </label>
    </div>
  </div>
</div>

<div class="row">
  <!-- Current Subscription Info -->
  <div class="col-lg-6 col-12 mb-4">
    <div class="card shadow h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <h4 class="card-title mb-0">My Subscription</h4>
        </div>
        <form id="cancelSubscriptionForm" class="mt-4">
          <div class="mb-3">
            <label class="form-label">Current Plan</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="ti ti-crown text-black"></i>
              </span>
              <input type="text" class="form-control text-black" id="currentPlan" placeholder="Loading..." readonly>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Next Billing Date</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="ti ti-calendar text-black"></i>
              </span>
              <input type="text" class="form-control text-black" id="nextBilling" placeholder="Loading..." readonly>
            </div>
          </div>

          <button type="submit" class="btn btn-danger w-100">Cancel Subscription</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Available Plans -->
  <div class="col-lg-6 col-12 mb-4">
    <div class="card shadow h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <h4 class="card-title mb-0">Choose a Plan</h4>
        </div>

        <form id="subscribe" class="mt-4">
          <div class="mb-3">
            <label for="planSelect" class="form-label">Select Plan</label>
            <select id="planSelect" class="planSelect form-select text-black" name="plan" required>
              <option value="">-- Select a Plan --</option>
              <option value="basic">Basic - ₦1,000/mo</option>
              <option value="premium">Premium - ₦2,000/mo</option>
              <option value="complex">Complex - ₦3,000/mo</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">New Subscription</button>
        </form>
      </div>
    </div>
  </div>
</div>





<!-- Get user role -->
<div id="user-role" data-role="{{ $role }}"></div>

<!-- 🔍 Search Input -->
<div class="mb-3 mt-4 col-6">
  <input type="text" id="transactionSearch" class="form-control" style="margin-bottom: 10px; background-color: #fff;" placeholder="Search your plan, status, or date..." />
</div>

<!-- Subscription History -->
<div class="col-12 mb-4">
  <div class="card shadow h-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0">Subscription History</h4>
      </div>

      <!-- Table -->
      <div class="table-responsive mt-4">
        <table class="table table-hover align-middle text-nowrap fs-3" id="subscriptionHistoryTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th> <!-- 👈 Name column -->
              <th>Plan</th>
              <th>Price (₦)</th>
              <th>Status</th>
              <th>Start Date</th>
              <th>Next Billing</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dynamic rows from JS -->
          </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="mt-3 text-center" id="paginationControls">
          <!-- Dynamic pagination buttons will be injected here -->
        </div>
      </div>
    </div>
  </div>
</div>

