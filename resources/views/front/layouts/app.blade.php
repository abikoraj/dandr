<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Stylesheets
 ============================================= -->
    <link
        href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/dark.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('assets/css/swiper.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/font-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ env('APP_NAME') }}</title>
</head>

<body>
    @include('front.layouts.header')

    @yield('content')

    @include('front.layouts.footer')


    <!-- JavaScripts
 ============================================= -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.min.js') }}"></script>
    <script src="https://maps.google.com/maps/api/js?key=YOUR-API-KEY"></script>

    <!-- Footer Scripts
 ============================================= -->
    <script src="{{ asset('assets/js/functions.js') }}"></script>

</body>

</html>
