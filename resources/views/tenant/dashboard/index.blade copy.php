{{-- resources/views/tenant/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', $user->name . ' | SkillLeo')
@push('styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush
@section('content')



    <x-navigation.top-nav :user="$user" :username="$username" />

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Toggle Buttons -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <button class="right-sidebar-toggle" id="rightSidebarToggle">
        <i class="fas fa-sliders-h"></i>
    </button>





    <!-- Main Container -->
    <div class="main-container">
        <!-- Left Sidebar -->









        <x-dashboard.left-sidebar />

















        <!-- Main Content Area -->
        <main class="content-area">
            <h1 class="page-title">Dashboard</h1>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Visitors</div>
                            <div class="stat-value">12,450</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i>
                        <span class="positive">9.2%</span>
                        <span>(Up from last 7 days)</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Impressions</div>
                            <div class="stat-value">28,960</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i>
                        <span class="positive">5.7%</span>
                        <span>(Up from last 30 days)</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">CTA Clicks</div>
                            <div class="stat-value">1,280</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                    </div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-down"></i>
                        <span class="negative">3.4%</span>
                        <span>(Down from yesterday)</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Active Orders</div>
                            <div class="stat-value">16</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="stat-change">
                        <span>Currently active</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Revenue</div>
                            <div class="stat-value">$24,600</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i>
                        <span class="positive">11.5%</span>
                        <span>(Up from last 30 days)</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Overdue</div>
                            <div class="stat-value">3</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="stat-change">
                        <span>3 invoices overdue</span>
                    </div>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="chart-container">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Trend Overview</div>
                        <div class="chart-subtitle">Visitors vs Impressions</div>
                    </div>
                    <div class="time-filters">
                        <div class="time-filter">7d</div>
                        <div class="time-filter active">30d</div>
                        <div class="time-filter">90d</div>
                    </div>
                </div>
                <div class="chart-area">
                    <svg width="100%" height="100%" viewBox="0 0 800 200" preserveAspectRatio="none">
                        <line x1="0" y1="50" x2="800" y2="50" stroke="#e0e0e0"
                            stroke-width="1" />
                        <line x1="0" y1="100" x2="800" y2="100" stroke="#e0e0e0"
                            stroke-width="1" />
                        <line x1="0" y1="150" x2="800" y2="150" stroke="#e0e0e0"
                            stroke-width="1" />
                        <polyline fill="none" stroke="#1351d8" stroke-width="3"
                            points="0,120 100,80 200,100 300,60 400,90 500,70 600,85 700,65 800,75" />
                        <polyline fill="none" stroke="#9ca3af" stroke-width="3"
                            points="0,150 100,130 200,145 300,110 400,125 500,115 600,120 700,105 800,110" />
                    </svg>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #1351d8;"></span>
                        <span>Impressions</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background: #9ca3af;"></span>
                        <span>Visitors</span>
                    </div>
                </div>
            </div>

            <!-- Row Cards -->
            <div class="row-cards">
                <!-- Conversion Funnel -->
                <div class="funnel-card">
                    <div class="card-title">Conversion Funnel</div>
                    <div class="card-subtitle">Profile views → Paid Orders</div>
                    <div style="margin: 20px 0;">
                        <div class="funnel-item">
                            <div class="funnel-label">Profile views</div>
                            <div class="funnel-bar-container">
                                <div class="funnel-bar" style="width: 100%;"></div>
                                <span class="funnel-percent">100%</span>
                            </div>
                        </div>
                        <div class="funnel-item">
                            <div class="funnel-label">Project clicks</div>
                            <div class="funnel-bar-container">
                                <div class="funnel-bar" style="width: 62%;"></div>
                                <span class="funnel-percent">62%</span>
                            </div>
                        </div>
                        <div class="funnel-item">
                            <div class="funnel-label">Contact requests</div>
                            <div class="funnel-bar-container">
                                <div class="funnel-bar" style="width: 28%;"></div>
                                <span class="funnel-percent">28%</span>
                            </div>
                        </div>
                        <div class="funnel-item">
                            <div class="funnel-label">Orders created</div>
                            <div class="funnel-bar-container">
                                <div class="funnel-bar" style="width: 12%;"></div>
                                <span class="funnel-percent">12%</span>
                            </div>
                        </div>
                        <div class="funnel-item">
                            <div class="funnel-label">Paid orders</div>
                            <div class="funnel-bar-container">
                                <div class="funnel-bar" style="width: 9%;"></div>
                                <span class="funnel-percent">9%</span>
                            </div>
                        </div>
                    </div>
                    <div class="funnel-note">Track lead drop-off at each stage</div>
                </div>

                <!-- Orders by Status -->
                <div class="orders-card">
                    <div class="card-title">Orders by Status</div>
                    <div class="pie-chart-container">
                        <div class="pie-chart">
                            <div class="pie-chart-inner">
                                <div class="pie-chart-value">120</div>
                                <div class="pie-chart-label">Orders</div>
                            </div>
                        </div>
                        <div class="pie-legend">
                            <div class="pie-legend-item">
                                <span class="pie-legend-dot" style="background: #1351d8;"></span>
                                <div class="pie-legend-text">
                                    New
                                    <span class="pie-legend-count">25 (20%)</span>
                                </div>
                            </div>
                            <div class="pie-legend-item">
                                <span class="pie-legend-dot" style="background: #4a90e2;"></span>
                                <div class="pie-legend-text">
                                    In progress
                                    <span class="pie-legend-count">40 (33%)</span>
                                </div>
                            </div>
                            <div class="pie-legend-item">
                                <span class="pie-legend-dot" style="background: #7eb8f5;"></span>
                                <div class="pie-legend-text">
                                    Waiting client
                                    <span class="pie-legend-count">15 (12%)</span>
                                </div>
                            </div>
                            <div class="pie-legend-item">
                                <span class="pie-legend-dot" style="background: #b3d9ff;"></span>
                                <div class="pie-legend-text">
                                    Completed
                                    <span class="pie-legend-count">40 (35%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Soon Section -->
            <div class="due-soon-section">
                <h3 class="due-soon-title">Due Soon</h3>
                <p class="due-soon-subtitle">Upcoming milestones, tasks, and invoices sorted by due date.</p>

                <div class="due-item">
                    <div class="due-item-content">
                        <h6>Milestone "Backend API" - Acme Website</h6>
                        <div class="due-item-meta">Due in 2 days • $1,200</div>
                    </div>
                    <div class="due-item-action">Open →</div>
                </div>

                <div class="due-item">
                    <div class="due-item-content">
                        <h6>Invoice #1045 – Globex</h6>
                        <div class="due-item-meta"><span class="overdue">Overdue 5d</span> • $900</div>
                    </div>
                    <div class="due-item-action">Send Reminder</div>
                </div>

                <div class="due-item">
                    <div class="due-item-content">
                        <h6>Task "UI polish" – RAG Assistant</h6>
                        <div class="due-item-meta">Due today • Assignee: Hassan</div>
                    </div>
                    <div class="due-item-action">Mark Done</div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="activity-section">
                <h3 class="activity-title">Recent Activity</h3>
                <div class="activity-grid">
                    <div class="activity-item">
                        <img src="https://ui-avatars.com/api/?name=Hassan+Mehmood&background=0072d2&color=fff"
                            alt="Hassan" class="activity-avatar">
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-name">Hassan Mehmood</div>
                                <div class="activity-time">10m ago</div>
                            </div>
                            <div class="activity-desc">approved Order #1021</div>
                            <div class="activity-meta">Milestone: Backend API</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <img src="https://ui-avatars.com/api/?name=Ali+Khan&background=28a745&color=fff" alt="Ali"
                            class="activity-avatar">
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-name">Ali Khan</div>
                                <div class="activity-time">2h ago</div>
                            </div>
                            <div class="activity-desc">uploaded Invoice #1045</div>
                            <div class="activity-meta">Globex Project</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <img src="https://ui-avatars.com/api/?name=Sana+Riaz&background=dc3545&color=fff" alt="Sana"
                            class="activity-avatar">
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-name">Sana Riaz</div>
                                <div class="activity-time">5h ago</div>
                            </div>
                            <div class="activity-desc">left a comment on Order #1021</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <img src="https://ui-avatars.com/api/?name=Hamza+Malik&background=ffc107&color=000" alt="Hamza"
                            class="activity-avatar">
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-name">Hamza Malik</div>
                                <div class="activity-time">yesterday</div>
                            </div>
                            <div class="activity-desc">created new Order #1030</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <img src="https://ui-avatars.com/api/?name=Zara+Ahmed&background=17a2b8&color=fff" alt="Zara"
                            class="activity-avatar">
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-name">Zara Ahmed</div>
                                <div class="activity-time">2d ago</div>
                            </div>
                            <div class="activity-desc">completed Milestone: UI Mockups</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <img src="https://ui-avatars.com/api/?name=Asad+Khan&background=6f42c1&color=fff" alt="Asad"
                            class="activity-avatar">
                        <div class="activity-content">
                            <div class="activity-header">
                                <div class="activity-name">Asad Khan</div>
                                <div class="activity-time">3d ago</div>
                            </div>
                            <div class="activity-desc">added New Client Proposal</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Right Sidebar -->
        <aside class="right-sidebar" id="rightSidebar">
            <div class="right-sidebar-content" id="rightSidebarContent">

                <!-- Placeholder (hidden by default, shown when card is fixed) -->
                <div class="promo-card-placeholder" id="promoCardPlaceholder"></div>

                <!-- Sticky Promo Card -->


                <!-- Scrollable Content Below -->

                <!-- Tutorial Video -->
                <div class="right-card">
                    <h6>Tutorial Video</h6>
                    <div class="tutorial-placeholder">
                        <i class="fas fa-play-circle" style="font-size: 48px;"></i>
                    </div>
                </div>

                <!-- AI Profile Creator -->
                <section class="ai-profile-creator">
                    <div class="ai-creator-header">
                        <h3 class="ai-creator-title">AI Profile Creator</h3>
                        <p class="ai-creator-desc">
                            Upload your CV or describe yourself — AI will build your profile.
                        </p>
                    </div>

                    <div class="ai-creator-content">
                        <div class="upload-section">
                            <label for="cv-upload" class="upload-box">
                                <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                <span class="upload-text">Upload your CV</span>
                            </label>
                            <input type="file" id="cv-upload" accept=".pdf,.doc,.docx" hidden>
                        </div>

                        <div class="or-divider">
                            <span class="or-line"></span>
                            <span class="or-text">OR</span>
                            <span class="or-line"></span>
                        </div>

                        <div class="textarea-section">
                            <div class="textarea-wrapper">
                                <textarea class="describe-textarea" placeholder="Describe yourself here...." rows="3"></textarea>
                            </div>
                        </div>

                        <button class="generate-profile-btn">Generate Profile</button>
                    </div>
                </section>

                <!-- AI Message Refiner -->
                <div class="right-card refiner-card">
                    <h6>AI Message Refiner</h6>
                    <button class="refine-button">
                        Refine <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- Help & Learn -->
                <div class="right-card">
                    <div class="pinned-section pinned-links">
                        <span class="pinned-title">Help & learn</span>
                        <i class="fas fa-pen pinned-edit"></i>
                        <ul>
                            <li>How orders work</li>
                            <li>Connect Stripe</li>
                            <li>About milestones</li>
                            <li>Keyboard shortcuts</li>
                        </ul>
                    </div>
                </div>






















                <!-- Upgrade (sticks at 20px when it reaches the top) -->
                <div class="upgrade-card sticky-on-scroll" >
                    <h6>Go Pro — Unlock Everything</h6>
                    <div class="icon"><i class="fas fa-rocket"></i></div>
                    <p style="margin:10px 0 16px">
                        Advanced analytics, AI helpers & priority support.
                    </p>
                    <button class="btn-upgrade" type="button">Upgrade Now</button>
                </div>
































            </div>
        </aside>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>









@endsection
