@php
  // ✅ Get user & role directly from session
  $role = session('role');          // 'admin' or 'user'
  $user = session('user');          // Contains full logged-in object (Admin or User)
@endphp

<!-- Get user role -->
<div id="user-role" data-role="{{ $role }}"></div>

<!-- Only visible to Admin -->
<div id="addWashWrapper" class="mb-3 d-none">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWashModal">
    + Add Wash Record
  </button>
</div>

<!-- Search area -->
 <div class="col-6">
  <input type="text" id="washSearchInput" class="form-control" style="margin-bottom: 10px; background-color: #fff;" placeholder="Search by firstname, email, car name,, car model etc.">
</div>

<!-- Wash History -->
<div class="col-12 mb-4">
  <div class="card shadow h-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0">Car Wash History</h4>
      </div>
      <div class="table-responsive mt-4">
        <table class="table table-hover align-middle text-nowrap fs-3" id="washHistoryTable">
          <thead>
            <tr>
              <th>#</th>
              <th class="equal-width-cell2">Firstname</th>
              <th class="equal-width-cell2">Email</th>
              <th class="equal-width-cell2">Car Name</th>
              <th class="equal-width-cell2">Car Model</th>
              <th class="equal-width-cell2">Washed By</th>
              <th class="equal-width-cell2">Notes</th>
              <th class="equal-width-cell2">Date</th>
              @if($role === 'admin')
                <th class="equal-width-cell2">Actions</th>
              @endif
            </tr>
          </thead>
          <tbody>
            <!-- JS will populate this -->
          </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3" id="washPaginationControls">
          <!-- Optional: Pagination controls -->
        </div>
      </div>
    </div>
  </div>
</div>





<!-- Add Wash Modal -->
<div class="modal fade" id="addWashModal" tabindex="-1" aria-labelledby="addWashModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <form id="addWashForm">
        <div class="modal-header bg-light text-white rounded-top-4">
          <h5 class="modal-title fw-semibold" id="addWashModalLabel">
            <i class="ti ti-car-wash me-2"></i> Add Car Wash Record for Completed Washes
          </h5>
          <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body px-4 py-3">
          <div class="row g-3">

            <!-- User Info -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Customer First Name</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-user"></i></span>
                <input type="text" name="firstname" class="form-control text-black" placeholder="Enter Cusomer firstname" />
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Customer Email</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" class="form-control text-black" required placeholder="Enter Cusomer email" />
              </div>
            </div>

            <!-- Vehicle Info -->
            <div class="col-12">
              <hr class="my-2">
              <h6 class="fw-bold text-muted">Vehicle Information</h6>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Car Name</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-steering-wheel"></i></span>
                <input type="text" name="carName" class="form-control text-black" required placeholder="Enter car name" />
              </div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Car Model</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-car"></i></span>
                <input type="text" name="carModel" class="form-control text-black" required placeholder="Enter car model" />
              </div>
            </div>

            <!-- Wash Info -->
            <div class="col-12">
              <hr class="my-2">
              <h6 class="fw-bold text-muted">Wash Details</h6>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Washed By</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-user-check"></i></span>
                <input type="text" name="washedBy" class="form-control text-black" placeholder="Enter staff name" required />
              </div>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Notes <small class="text-muted">(optional)</small></label>
              <textarea name="notes" class="form-control form-control-sm text-black" rows="3"></textarea>
            </div>

          </div>
        </div>

        <div class="modal-footer bg-light rounded-bottom-4">
          <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="ti ti-device-floppy me-1"></i> Save Record
          </button>
        </div>
      </form>
    </div>
  </div>
</div>







<!-- Edit wash history popup modal -->
<div class="modal fade" id="editWashModal" tabindex="-1" aria-labelledby="editWashModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <form id="editWashForm">
        <div class="modal-header bg-light text-white rounded-top-4">
          <h5 class="modal-title fw-semibold" id="editWashModalLabel">
            <i class="ti ti-pencil me-2"></i> Edit Car Wash Record
          </h5>
          <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body px-4 py-3">
          <div class="row g-3">

            <!-- Hidden ID Field -->
            <input type="hidden" name="washId" id="editWashId" />

            <!-- User Info -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Customer First Name</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-user"></i></span>
                <input type="text" name="firstname" class="form-control text-black" />
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Customer Email</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" class="form-control text-black" required />
              </div>
            </div>

            <!-- Vehicle Info -->
            <div class="col-12">
              <hr class="my-2">
              <h6 class="fw-bold text-muted">Vehicle Information</h6>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Car Name</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-steering-wheel"></i></span>
                <input type="text" name="carName" class="form-control text-black" required />
              </div>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold">Car Model</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-car"></i></span>
                <input type="text" name="carModel" class="form-control text-black" required />
              </div>
            </div>

            <!-- Wash Info -->
            <div class="col-12">
              <hr class="my-2">
              <h6 class="fw-bold text-muted">Wash Details</h6>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Washed By</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ti ti-user-check"></i></span>
                <input type="text" name="washedBy" class="form-control text-black" required />
              </div>
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Notes <small class="text-muted">(optional)</small></label>
              <textarea name="notes" class="form-control form-control-sm text-black" rows="3"></textarea>
            </div>

          </div>
        </div>

        <div class="modal-footer bg-light rounded-bottom-4">
          <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="ti ti-device-floppy me-1"></i> Update Record
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


