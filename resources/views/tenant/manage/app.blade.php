{{-- resources/views/tenant/manage/app.blade.php --}}
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/manage.css') }}">
@endpush

@section('content')
    @include('components.navigation.top-nav')

    <div class="app-sidebar-overlay" id="appSidebarOverlay"></div>

    <button class="app-sidebar-toggle" id="appSidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="app-sidebar" id="appSidebar">
            @include('tenant.manage.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content">
                @yield('main')
            </div>
        </main>
    </div>
    
    @stack('modals')
    


    <script>
        function openTaskDrawer(taskId) {
            // fetch drawer HTML and append to body
            fetch(window.location.pathname.replace(/\/tasks\/.*/, '') + '/tasks/' + taskId + '/drawer', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                const existing = document.querySelector('.task-drawer');
                if (existing) existing.remove();
                document.body.insertAdjacentHTML('beforeend', html);
            })
            .catch(err => console.error(err));
        }
        
        // These just open modals you will build:
        // Submit Work / Postpone / Block / Request Changes / Reminder.
        function openSubmitWorkModal(taskId){ console.log('openSubmitWorkModal', taskId); }
        function openPostponeModal(taskId){ console.log('openPostponeModal', taskId); }
        function openBlockedModal(taskId){ console.log('openBlockedModal', taskId); }
        function openRequestChangesModal(taskId){ console.log('openRequestChangesModal', taskId); }
        function openReminderModal(taskId){ console.log('openReminderModal', taskId); }
        
        function openTaskActions(taskId){ console.log('openTaskActions menu', taskId); }
        </script>
        
    <script>
        'use strict';

        class AppController {
            constructor() {
                this.sidebar = document.getElementById('appSidebar');
                this.toggle = document.getElementById('appSidebarToggle');
                this.overlay = document.getElementById('appSidebarOverlay');
                this.init();
            }

            init() {
                this.setupToggle();
                this.setupOverlay();
                this.setupClickOutside();
                this.setupEscape();
                this.setupResize();
            }

            setupToggle() {
                this.toggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleSidebar();
                });
            }

            setupOverlay() {
                this.overlay?.addEventListener('click', () => {
                    this.closeSidebar();
                });
            }

            setupClickOutside() {
                document.addEventListener('click', (e) => {
                    if (window.innerWidth <= 1024) {
                        const clickedOutside = !this.sidebar?.contains(e.target) &&
                                             !this.toggle?.contains(e.target);
                        
                        if (clickedOutside && this.sidebar?.classList.contains('active')) {
                            this.closeSidebar();
                        }
                    }
                });

                this.sidebar?.addEventListener('click', (e) => e.stopPropagation());
            }

            setupEscape() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.sidebar?.classList.contains('active')) {
                        this.closeSidebar();
                    }
                });
            }

            setupResize() {
                let resizeTimer;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        if (window.innerWidth > 1024) {
                            this.closeSidebar();
                        }
                    }, 250);
                });
            }

            toggleSidebar() {
                const isActive = this.sidebar?.classList.toggle('active');
                this.overlay?.classList.toggle('active', isActive);
                document.body.classList.toggle('no-scroll', isActive);
            }

            closeSidebar() {
                this.sidebar?.classList.remove('active');
                this.overlay?.classList.remove('active');
                document.body.classList.remove('no-scroll');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new AppController();
            console.log('✅ Professional App Initialized');
        });
    </script>
@endsection

