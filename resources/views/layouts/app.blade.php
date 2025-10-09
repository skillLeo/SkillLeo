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

 
<body  >
    @yield('content')
    
 
    <script src="{{ asset('js/profile.js') }}"></script>
    @stack('scripts')
    @section('scripts')

    <x-modals.edit-profile :user="$user" />
    <x-modals.edit-experience />
    <x-modals.edit-education />
    <x-modals.edit-skills />
    <x-modals.edit-portfolio />
    <x-modals.edit-languages />
    
    {{-- Update edit buttons to trigger modals --}}
    <script>
    document.querySelectorAll('.edit-card').forEach(button => {
        // Skip if button already has onclick attribute (hero section buttons)
        if (button.hasAttribute('onclick')) {
            return;
        }
        
        button.addEventListener('click', function() {
            const section = this.closest('section');
            
            if (section.classList.contains('hero-merged')) {
                openModal('editProfileModal');
            } else if (section.classList.contains('experience-section')) {
                openModal('editExperienceModal');
            } else if (section.classList.contains('portfolios-section')) {
                openModal('editPortfolioModal');
            } else if (section.classList.contains('skills-showcase')) {
                openModal('editSkillsModal');
            }
            // Add more conditions for other sections
        });
    });
    </script>
</body>
</html>