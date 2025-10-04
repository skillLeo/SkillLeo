<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Professional Portfolio')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components.css') }}" rel="stylesheet">
<link href="{{ asset('css/responsive.css') }}" rel="stylesheet">



    @section('styles')

    @stack('styles')


    {{-- <style>
           .card{margin-bottom: 0px !important;}
    </style> --}}
</head>
<body  >
    @yield('content')
    
    <!-- Scripts -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('js/profile.js') }}"></script>
    @stack('scripts')
    @section('scripts')


    {{-- <script>
        // Disable right-click context menu
document.addEventListener("contextmenu", (e) => e.preventDefault());

        // Disable F12, Ctrl+Shift+I, Ctrl+U etc.
document.addEventListener("keydown", (e) => {
  if (
    e.key === "F12" || 
    (e.ctrlKey && e.shiftKey && e.key === "I") ||
    (e.ctrlKey && e.key === "u") ||
    (e.ctrlKey && e.shiftKey && e.key === "J")
  ) {
    e.preventDefault();
  }
});

    </script> --}}
</body>
</html>