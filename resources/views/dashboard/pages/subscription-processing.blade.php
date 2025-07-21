<div class="row justify-content-center" id="loadingSection">
  <div class="col-md-6 text-center">
    <div class="spinner-border text-primary mb-3" role="status"></div>
    <h5>Processing your subscription...</h5>
    <p class="text-muted">Please wait while we confirm your payment.</p>
  </div>
</div>

<!-- This section will be shown after confirmation -->
<div class="row justify-content-center d-none" id="successSection">
  <div class="col-md-8 mb-4">
    <div class="card shadow text-center">
      <div class="card-body">
        <h3 class="card-title text-success mb-3">🎉 Subscription Successful!</h3>
        <p class="fs-5 text-black">
          Congratulations! Your subscription has been activated successfully.
        </p>

        <div class="input-group mb-3">
            <span class="input-group-text bg-light"><i class="ti ti-box text-black"></i></span>
            <input type="text" id="planName" class="form-control text-black" readonly>
        </div>

        <div class="my-4">
          <div class="input-group mb-3">
            <span class="input-group-text bg-light"><i class="ti ti-calendar text-black"></i></span>
            <input type="text" class="form-control text-black" id="nextBilling" readonly>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-light"><i class="ti ti-check text-black"></i></span>
            <input type="text" class="form-control text-black" value="Status: ACTIVE" readonly>
          </div>
        </div>

        <a href="/dashboard" class="btn btn-primary w-100 py-2">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>



<!-- This section will be shown if confirmation fails -->
<div class="row justify-content-center d-none" id="failedSection">
  <div class="col-md-6">
    <div class="card shadow text-center">
      <div class="card-body">
        <h3 class="card-title text-danger mb-3">⚠️ Subscription Pending</h3>
        <p class="fs-5 text-black">
          We couldn’t confirm your payment. Please refresh the page or contact support.
        </p>
        <a href="/dashboard" class="btn btn-primary w-100 py-2">Back to Dashboard</a>
      </div>
    </div>
  </div>
</div>