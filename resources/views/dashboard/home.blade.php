<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Voeautocare Dashboard</title>
  <link rel="shortcut icon" type="image/png" href="/dashboard-assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="/dashboard-assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Include Sidebar -->
    @include('dashboard.partials.sidebar')

    <!--  Main wrapper -->
    <div class="body-wrapper">
      
      <!-- Include Header -->
      @include('dashboard.partials.header')

      <div class="body-wrapper-inner">
        <div class="container-fluid">
          <!-- Dynamic Body Content -->
          @include($page)
        </div>
      </div>
      
      <!-- Include Footer -->
      @include('dashboard.partials.footer')

    </div>
  </div>
  
  <script src="/dashboard-assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="/dashboard-assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/dashboard-assets/js/app.min.js"></script>
  <script src="/dashboard-assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="/dashboard-assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

</body>

</html>
