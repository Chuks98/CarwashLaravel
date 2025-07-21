<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Voeautocare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="Voeautocare Car wash">
    <meta name="description" content="Voeautocare Car wash">

    <!-- Favicon -->
    <link href="{{ asset('assets/img/fav.png') }}" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="{{ asset('assets/lib/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/lib/flaticon/font/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

    <!-- The header -->
    @include('index.partials.header')

    <!-- Dynamic Pages -->
    @include($page)

    <!-- The footer -->
    @include('index.partials.footer')

    <!-- Back to top button -->
    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    <!-- Pre Loader -->
    <div id="loader" class="show">
        <div class="loader"></div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="{{ asset('assets/lib/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/lib/bootstrap/dist/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('assets/lib/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- Contact Javascript File -->
    <script src="{{ asset('assets/mail/jqBootstrapValidation.min.js') }}"></script>
    <script src="{{ asset('assets/mail/contact.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts') {{-- Optional: for page-specific scripts --}}
</body>
</html>
