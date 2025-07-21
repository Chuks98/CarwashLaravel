(function ($) {
    "use strict";

    $(function () {
        // Loader
        setTimeout(() => {
            $('#loader').removeClass('show');
        }, 1);

        // Back to Top Button
        $(window).on('scroll', function () {
            if ($(this).scrollTop() > 200) {
                $('.back-to-top').fadeIn('slow');
            } else {
                $('.back-to-top').fadeOut('slow');
            }

            // Sticky Navbar
            if ($(this).scrollTop() > 90) {
                $('.nav-bar').addClass('nav-sticky');
                $('.carousel, .page-header').css('margin-top', '73px');
            } else {
                $('.nav-bar').removeClass('nav-sticky');
                $('.carousel, .page-header').css('margin-top', '0');
            }
        });

        $('.back-to-top').on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        });

        // Dropdown on hover (desktop only)
        const toggleNavbarMethod = () => {
            $('.navbar .dropdown').off('mouseenter mouseleave');
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseenter', function () {
                    $(this).addClass('show');
                    $(this).find('.dropdown-menu').addClass('show');
                }).on('mouseleave', function () {
                    $(this).removeClass('show');
                    $(this).find('.dropdown-menu').removeClass('show');
                });
            }
        };

        toggleNavbarMethod();
        $(window).on('resize', toggleNavbarMethod);

        // Add active class to current nav link
        const currentPath = window.location.pathname;
        $('.navbar-nav .nav-link').each(function () {
            const linkPath = $(this).attr('href');

            if (linkPath === currentPath) {
                $('.navbar-nav .nav-link').removeClass('active');
                $(this).addClass('active');
            }

            // Handle dropdown children
            if ($(this).hasClass('dropdown-toggle')) {
                const dropdown = $(this).next('.dropdown-menu');
                dropdown.find('a').each(function () {
                    if ($(this).attr('href') === currentPath) {
                        $('.navbar-nav .nav-link').removeClass('active');
                        $(this).addClass('active');
                        $(this).closest('.nav-item.dropdown').find('.nav-link.dropdown-toggle').addClass('active');
                    }
                });
            }
        });

        // Main Carousel
        $(".carousel .owl-carousel").owlCarousel({
            autoplay: true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            items: 1,
            smartSpeed: 300,
            dots: false,
            loop: true,
            nav: true,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ]
        });

        // Counter Up
        $('[data-toggle="counter-up"]').counterUp({
            delay: 10,
            time: 2000
        });

        // Testimonials Carousel
        $(".testimonials-carousel").owlCarousel({
            center: true,
            autoplay: true,
            smartSpeed: 2000,
            dots: true,
            loop: true,
            responsive: {
                0: { items: 1 },
                576: { items: 1 },
                768: { items: 2 },
                992: { items: 3 }
            }
        });

        // Related Posts Carousel
        $(".related-slider").owlCarousel({
            autoplay: true,
            dots: false,
            loop: true,
            nav: true,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive: {
                0: { items: 1 },
                576: { items: 1 },
                768: { items: 2 }
            }
        });
    });

    // Logout JavaScript codes
    $('#logout-btn-desktop').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of your account.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout',
        cancelButtonText: 'No',
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            type: 'GET',
            url: '/logout',
            success: function (response) {
                Swal.fire({
                icon: 'success',
                title: 'Logged out',
                text: response.message,
                timer: 1000,
                showConfirmButton: false
                }).then(() => {
                window.location.href = '/login';
                });
            },
            error: function (xhr) {
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'An error occured while logging out.'
                });
            }
            });
        }
        });
    });
})(jQuery);
