@php
  // ✅ Get user & role directly from session
  $role = session('role');          // 'admin' or 'user'
  $user = session('user');          // Contains full logged-in object (Admin or User)
@endphp

<!-- Get user role -->
<div id="user-role" data-role="{{ $role }}"></div>

<!-- 🔍 Search Input -->
<div class="mb-3 mt-4 col-6">
  <input type="text" id="transactionSearch" class="form-control" style="margin-bottom: 10px; background-color: #fff;" placeholder="Search by name, plan, status, or date..." />
</div>

<!-- Subscription History -->
<div class="col-12 mb-4">
  <div class="card shadow h-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0">Transactions</h4>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-hover align-middle text-nowrap fs-3" id="subscriptionHistoryTable">
          <thead>
            <tr id="subscriptionTableHead">
              <th>#</th>
              <!-- These will be injected by JS if role is admin -->
               <th class="user-only">Name</th>
              <th class="admin-only customer-header d-none">Customer</th>
              <th class="admin-only email-header d-none">Email</th>
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
