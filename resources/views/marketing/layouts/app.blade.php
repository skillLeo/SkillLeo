<!DOCTYPE html>
<html lang="en" class="{{ $htmlClass ?? '' }}">

<head>
    <!-- Stylesheet, Meta Tag, Title -->
    @include('marketing.partials.head')
    @yield('css')
</head>

<body class="{{ $bodyClass ?? '' }}">
    <!-- Start Contenet Area-->
    @yield('content')
    <!-- End Contenet area -->

    <!-- Start Script area -->
    @include('marketing.partials.scripts')
    @yield('script')
    <!-- End Script area -->
</body>

</html>
