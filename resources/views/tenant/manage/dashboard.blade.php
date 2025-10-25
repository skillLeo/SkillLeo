@extends('tenant.manage.app')

@section('title', $user->name . ' | Dashboard')

@push('styles')
<style>
:root {
    --bg: #f3f2ee;
    --card: #fff;
    --ink: #1a1a1a;
    --muted: #000000af;
    --muted2: #999;
    --border: #e5e5e5;
    --accent: #1351d8;
    --accent-dark: #0d3393;
    --accent-light: #1351d818;
    --radius: 10px;

    --apc-g1: #a855f7;
    --apc-g2: #2dd4ea;
    --apc-bg: #f5f6f8;

    --fw-regular: 400;
    --fw-medium: 500;
    --fw-semibold: 600;
    --fw-bold: 700;
    --fw-extrabold: 800;

    --lh-compact: 1.2;
    --lh-tight: 1.3;
    --lh-normal: 1.5;
    --lh-relaxed: 1.65;

    --fs-display: clamp(1.75rem, 1.2vw + 1rem, 2.25rem);
    --fs-h1: clamp(1.375rem, 0.8vw + 1rem, 1.75rem);
    --fs-h2: 1.25rem;
    --fs-h3: 1.125rem;
    --fs-title: 1rem;
    --fs-body: 0.875rem;
    --fs-subtle: 0.8125rem;
    --fs-micro: 0.75rem;

    --ic-xxs: 0.625rem;
    --ic-xs: 0.75rem;
    --ic-sm: 0.875rem;
    --ic-md: 1rem;
    --ic-lg: 1.125rem;

    --mb-sections: 9px;
    --sticky-offset: 72px;

    --gradient-border: linear-gradient(
        135deg,
        #667eea 0%,
        #764ba2 35%,
        #f093fb 70%,
        #4facfe 100%
    );
    --gradient-button: linear-gradient(
        90deg,
        #5b86e5 0%,
        #36d1dc 100%
    );

    /* Text Colors Light Mode */
    --text-primary: #1a1a1a;
    --text-heading: #1a1a1a;
    --text-body: #000000af;
    --text-muted: #667085;
    --text-subtle: #98a2b3;
    --text-disabled: #c0c0c0;
    --text-link: #0b63ff;
    --text-accent: #1351d8;

    /* Nav */
    --nav-bg: #fff;
    --nav-border: #e6e8eb;
    --nav-text: #000000af;
    --nav-icon: #444;

    /* Input/Form */
    --input-bg: #fff;
    --input-border: #e6e8eb;
    --input-text: #000000af;
    --input-placeholder: #9aa1a9;

    /* Card-specific */
    --card-title: #1a1a1a;
    --card-subtitle: #667085;
    --card-desc: #475569;
    --card-meta: #98a2b3;

    /* Button text */
    --btn-text-primary: #fff;
    --btn-text-secondary: #000000af;

    /* Tags / badges */
    --tag-text: #000000af;
    --tag-bg: #fff;
    --tag-border: #1a1a1a;
    --badge-bg: #111;
    --badge-text: #fff;

    /* Section headers */
    --section-title: #1e293b;
    --section-text: #323130;

    /* Avatar placeholders */
    --photo-placeholder-bg: #f2f2f2;
    --photo-placeholder-text: #b9b9b9;
    --photo-circle-bg: #f6f6f6;
    --photo-circle-text: #9b9b9b;

    /* Skill chips */
    --skill-text: #283548;
    --skill-divider: #f0f0f0;

    /* Review card */
    --review-card-bg: #fff;
    --review-card-border: #eceff4;
    --review-name: #000000af;
    --review-location: #667085;
    --review-text: #475569;
    --quote-icon: #e2e8f0;

    /* Education */
    --edu-text: #666;
    --edu-date: #999;

    /* About */
    --about-text: #555;

    /* Upload box */
    --upload-text: #334155;
    --upload-placeholder: #94a3b8;
    --or-text: #94a3b8;
    --or-line: #e2e8f0;

    /* AI Creator */
    --ai-title: #0f172a;
    --ai-desc: #64748b;

    /* Social icons */
    --social-icon: #666;

    --nav-height-mobile: 107px;
    --nav-height-desktop: 64px;

    --text-white: #ffffff;
}

[data-theme="dark"] {
    --bg: #000000;
    --card: #1b1f23;
    --ink: #ffffff;

    --muted: #c0c0c0;
    --muted2: #888888;

    --border: #2d3135;

    --accent: #4a8fff;
    --accent-dark: #2e6fd9;
    --accent-light: #4a8fff25;

    --apc-bg: #1b1f23;

    --text-primary: #ffffff;
    --text-heading: #ffffff;
    --text-body: #c0c0c0;
    --text-muted: #9ca3af;
    --text-subtle: #6b7280;
    --text-disabled: #4b5563;
    --text-link: #60a5fa;
    --text-accent: #4a8fff;

    --nav-bg: #1b1f23;
    --nav-border: #2d3135;
    --nav-text: #c0c0c0;
    --nav-icon: #c0c0c0;

    --input-bg: #1b1f23;
    --input-border: #2d3135;
    --input-text: #ffffff;
    --input-placeholder: #6b7280;

    --card-title: #ffffff;
    --card-subtitle: #9ca3af;
    --card-desc: #c0c0c0;
    --card-meta: #6b7280;

    --btn-text-primary: #ffffff;
    --btn-text-secondary: #c0c0c0;

    --tag-text: #c0c0c0;
    --tag-bg: #1b1f23;
    --tag-border: #4a8fff;
    --badge-bg: #4a8fff;
    --badge-text: #ffffff;

    --section-title: #ffffff;
    --section-text: #c0c0c0;

    --photo-placeholder-bg: #2d3135;
    --photo-placeholder-text: #6b7280;
    --photo-circle-bg: #2d3135;
    --photo-circle-text: #6b7280;

    --skill-text: #c0c0c0;
    --skill-divider: #2d3135;

    --review-card-bg: #1b1f23;
    --review-card-border: #2d3135;
    --review-name: #ffffff;
    --review-location: #9ca3af;
    --review-text: #c0c0c0;
    --quote-icon: #374151;

    --edu-text: #9ca3af;
    --edu-date: #6b7280;

    --about-text: #c0c0c0;

    --upload-text: #ffffff;
    --upload-placeholder: #6b7280;
    --or-text: #6b7280;
    --or-line: #2d3135;

    --ai-title: #ffffff;
    --ai-desc: #9ca3af;

    --social-icon: #9ca3af;
}

/* Layout base */
body{
    background: var(--bg);
    color: var(--text-primary);
    font-family: -apple-system,BlinkMacSystemFont,"Inter","SF Pro Text",Roboto,"Helvetica Neue",Arial,sans-serif;
    line-height: var(--lh-normal);
}

/* Page header */
.page-title{
    font-size: var(--fs-h1);
    font-weight: var(--fw-bold);
    margin: 8px 0 18px 0;
    color: var(--text-heading);
    display:flex;
    align-items:center;
    gap:10px;
}
.page-title-badge{
    background: var(--accent-light);
    border:1px solid var(--accent-dark);
    border-radius: var(--radius);
    font-size: var(--fs-subtle);
    color: var(--text-accent);
    padding:4px 10px;
    font-weight: var(--fw-semibold);
    line-height: var(--lh-compact);
    display:flex;
    align-items:center;
    gap:6px;
}

/* meta row under title */
.dashboard-meta-row{
    display:flex;
    flex-wrap:wrap;
    gap:16px;
    font-size:var(--fs-body);
    color:var(--muted);
    margin-bottom:20px;
}
.dashboard-meta-row .meta-chip{
    display:flex;
    align-items:center;
    gap:6px;
    background: var(--accent-light);
    border:1px solid var(--accent-dark);
    border-radius: var(--radius);
    padding:4px 10px;
    color: var(--text-accent);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
}

/* ===== STATS GRID ===== */
.stats-grid{
    display:grid;
    grid-template-columns: repeat(12,1fr);
    gap:14px;
    margin-bottom:18px;
}
.stat-card{
    grid-column:span 4;
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:16px;
    transition: transform .15s ease, border-color .15s ease;
}
.stat-card:hover{
    transform: translateY(-2px);
    border-color: var(--accent-dark);
}
.stat-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.stat-label{
    font-size:var(--fs-body);
    color:var(--muted);
    margin-bottom:6px;
    line-height:var(--lh-compact);
}
.stat-value{
    font-size:var(--fs-h1);
    font-weight:var(--fw-bold);
    letter-spacing:.2px;
    line-height:var(--lh-tight);
    color:var(--text-heading);
}
.stat-icon{
    width:42px;
    height:42px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:var(--radius);
    background:var(--accent-light);
    color:var(--accent);
    border:1px solid var(--accent-dark);
    font-size:var(--ic-md);
}
.stat-change{
    margin-top:10px;
    font-size:var(--fs-subtle);
    color:var(--muted);
    display:flex;
    flex-wrap:wrap;
    gap:6px;
    align-items:center;
    line-height:var(--lh-compact);
}
.stat-change .positive{
    color:var(--accent);
    font-weight:var(--fw-semibold);
}
.stat-change .negative{
    color:var(--badge-bg);
    font-weight:var(--fw-semibold);
}

/* ===== MAIN CHART CARD ===== */
.trend-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:16px;
    margin:10px 0 18px;
}
.trend-header{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:10px;
}
.trend-title{
    font-weight:var(--fw-semibold);
    font-size:var(--fs-h2);
    color:var(--text-heading);
    display:flex;
    align-items:center;
    gap:8px;
    line-height:var(--lh-tight);
}
.trend-subtitle{
    font-size:var(--fs-body);
    color:var(--muted);
    line-height:var(--lh-normal);
}
.trend-meta-chip{
    display:flex;
    align-items:center;
    gap:8px;
    padding:6px 10px;
    border-radius: var(--radius);
    border:1px dashed var(--accent-dark);
    background: var(--accent-light);
    color: var(--text-accent);
    font-size:var(--fs-subtle);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
}
.trend-header-right{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    align-items:flex-start;
}
.time-filter-btn{
    border-radius:var(--radius);
    border:1px solid var(--border);
    background:var(--bg);
    color:var(--muted);
    font-size:var(--fs-subtle);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
    padding:7px 10px;
    cursor:pointer;
    transition:all .15s;
}
.time-filter-btn.active{
    background:var(--gradient-button);
    border-color:var(--accent-dark);
    color:var(--btn-text-primary);
}
.time-filter-btn:hover{
    filter:brightness(1.05);
}

.chart-body-wrapper{
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:12px;
}
.chart-row{
    display:grid;
    grid-template-columns:minmax(0,1fr);
}
#lineChartCanvas{
    width:100%;
    height:240px;
    max-height:240px;
}

.chart-legend{
    display:flex;
    gap:18px;
    flex-wrap:wrap;
    margin-top:10px;
}
.chart-legend-item{
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--muted);
    font-size:var(--fs-body);
    line-height:var(--lh-compact);
}
.chart-legend-dot{
    width:12px;
    height:12px;
    border-radius:var(--radius);
}
.chart-legend-dot.impressions{
    background: var(--accent);
}
.chart-legend-dot.visitors{
    background: var(--muted);
}

/* ===== TWO-COLUMN ROW (funnel + donut) ===== */
.row-cards{
    display:grid;
    grid-template-columns:1.1fr .9fr;
    gap:14px;
    margin-bottom:18px;
}
.card-block{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:16px;
}
.block-title{
    font-weight:var(--fw-semibold);
    font-size:var(--fs-h2);
    color:var(--text-heading);
    display:flex;
    align-items:center;
    gap:8px;
    line-height:var(--lh-tight);
}
.block-subtitle{
    color:var(--muted);
    font-size:var(--fs-body);
    line-height:var(--lh-normal);
    margin-top:4px;
}

/* Funnel */
.funnel-list{
    margin-top:18px;
    display:flex;
    flex-direction:column;
    gap:14px;
}
.funnel-row{
    display:grid;
    grid-template-columns:160px 1fr 60px;
    gap:10px;
    align-items:center;
}
.funnel-label{
    font-size:var(--fs-body);
    color:var(--text-primary);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
}
.funnel-bar-wrap{
    position:relative;
    height:12px;
    background: var(--accent-light);
    border:1px solid var(--accent-dark);
    border-radius:var(--radius);
    overflow:hidden;
}
.funnel-bar-fill{
    height:100%;
    background: var(--gradient-button);
}
.funnel-pct{
    text-align:right;
    font-size:var(--fs-subtle);
    color:var(--text-accent);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
}

/* Donut / Status distribution */
.donut-wrap{
    display:flex;
    gap:16px;
    flex-wrap:wrap;
    align-items:flex-start;
    margin-top:18px;
}
.donut-chart-box{
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:12px;
    width:180px;
    height:180px;
    display:flex;
    align-items:center;
    justify-content:center;
}
#donutChartCanvas{
    width:140px !important;
    height:140px !important;
}
.donut-legend{
    flex:1;
    display:flex;
    flex-direction:column;
    gap:10px;
    min-width:200px;
}
.donut-legend-item{
    display:flex;
    align-items:flex-start;
    gap:10px;
    font-size:var(--fs-body);
    line-height:var(--lh-normal);
}
.donut-dot{
    width:10px;
    height:10px;
    border-radius:var(--radius);
    flex-shrink:0;
}
.donut-label{
    color:var(--text-primary);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
}
.donut-desc{
    color:var(--muted);
    font-size:var(--fs-subtle);
    line-height:var(--lh-normal);
}

/* ===== DUE SOON ===== */
.due-soon-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:16px;
    margin-bottom:18px;
}
.section-heading{
    font-weight:var(--fw-semibold);
    font-size:var(--fs-h2);
    color:var(--text-heading);
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:4px;
    line-height:var(--lh-tight);
}
.section-sub{
    font-size:var(--fs-body);
    color:var(--muted);
    line-height:var(--lh-normal);
    margin-bottom:12px;
}
.due-item{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
    padding:12px;
    border:1px solid var(--border);
    border-radius:var(--radius);
    background:var(--bg);
    margin-bottom:10px;
    transition:border-color .15s ease, transform .15s ease;
}
.due-item:hover{
    border-color:var(--accent-dark);
    transform:translateX(2px);
}
.due-main h6{
    margin:0 0 6px 0;
    font-size:var(--fs-title);
    color:var(--text-primary);
    line-height:var(--lh-tight);
    font-weight:var(--fw-semibold);
}
.due-project-name{
    color:var(--muted);
    font-weight:var(--fw-regular);
}
.due-meta-line{
    font-size:var(--fs-subtle);
    color:var(--muted);
    line-height:var(--lh-normal);
}
.overdue-badge{
    color:var(--badge-text);
    background:var(--badge-bg);
    border:1px solid var(--border);
    padding:2px 8px;
    border-radius:var(--radius);
    font-size:var(--fs-subtle);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
}
.due-cta{
    color:var(--text-accent);
    font-size:var(--fs-body);
    font-weight:var(--fw-semibold);
    white-space:nowrap;
    line-height:var(--lh-tight);
}

/* ===== TODAY TIMELINE & ACTIVITY ===== */
.bottom-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
    margin-bottom:18px;
}
.timeline-card,
.activity-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:16px;
}
.timeline-list{
    display:flex;
    flex-direction:column;
    gap:16px;
}
.timeline-row{
    display:grid;
    grid-template-columns:80px 1fr;
    gap:12px;
}
.timeline-time-col{
    font-size:var(--fs-body);
    color:var(--muted);
    line-height:var(--lh-tight);
    text-align:right;
}
.timeline-time-extra{
    font-size:var(--fs-micro);
    color:var(--muted);
    line-height:var(--lh-tight);
}
.timeline-body{
    border-left:2px solid var(--border);
    padding-left:12px;
}
.timeline-card-inner{
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:12px;
}
.timeline-head{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    align-items:flex-start;
    gap:8px;
    margin-bottom:6px;
    font-size:var(--fs-body);
    color:var(--text-primary);
    font-weight:var(--fw-semibold);
    line-height:var(--lh-tight);
}
.timeline-duration{
    font-size:var(--fs-subtle);
    color:var(--accent);
    background:var(--accent-light);
    border:1px solid var(--accent-dark);
    border-radius:var(--radius);
    padding:2px 8px;
    font-weight:var(--fw-semibold);
    line-height:var(--lh-compact);
}
.timeline-meta{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    font-size:var(--fs-subtle);
    color:var(--muted);
    line-height:var(--lh-normal);
}
.timeline-meta span{
    display:flex;
    align-items:center;
    gap:6px;
}

/* ACTIVITY FEED */
.activity-feed{
    display:flex;
    flex-direction:column;
    gap:12px;
}
.activity-row{
    display:flex;
    gap:12px;
    align-items:flex-start;
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:12px;
}
.activity-avatar{
    width:40px;
    height:40px;
    border-radius:50%;
    border:2px solid var(--border);
    object-fit:cover;
    flex-shrink:0;
}
.activity-main{
    flex:1;
}
.activity-top{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:8px;
    font-size:var(--fs-body);
    line-height:var(--lh-tight);
}
.activity-name{
    color:var(--text-primary);
    font-weight:var(--fw-semibold);
}
.activity-time{
    color:var(--muted);
    font-size:var(--fs-subtle);
    line-height:var(--lh-compact);
}
.activity-desc{
    font-size:var(--fs-body);
    color:var(--text-primary);
    margin-top:4px;
    line-height:var(--lh-normal);
    font-weight:var(--fw-regular);
}
.activity-context{
    color:var(--muted);
    font-size:var(--fs-subtle);
    margin-top:4px;
    line-height:var(--lh-normal);
}

/* ===== RIGHT SIDEBAR ===== */
.right-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:14px;
    margin-bottom:14px;
}
.right-card h6{
    margin:0 0 10px 0;
    font-size:var(--fs-title);
    font-weight:var(--fw-semibold);
    color:var(--text-heading);
    line-height:var(--lh-tight);
}
.tutorial-placeholder{
    height:180px;
    border:1px dashed var(--border);
    border-radius:var(--radius);
    background:var(--bg);
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--accent);
    font-size:var(--fs-display);
}
.refiner-card .refine-button{
    width:100%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    background:var(--gradient-button);
    color:var(--btn-text-primary);
    border:none;
    border-radius:var(--radius);
    padding:10px 12px;
    font-weight:var(--fw-semibold);
    cursor:pointer;
    font-size:var(--fs-title);
    line-height:var(--lh-tight);
}
.refiner-card .refine-button:disabled{
    opacity:.6;
    cursor:default;
}
.refiner-card .refine-button:hover:not(:disabled){
    filter:brightness(1.05);
}
.pinned-section{
    display:flex;
    flex-direction:column;
    gap:10px;
}
.pinned-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.pinned-title{
    font-weight:var(--fw-semibold);
    color:var(--text-heading);
    font-size:var(--fs-title);
    line-height:var(--lh-tight);
}
.pinned-edit{
    color:var(--muted);
    font-size:var(--fs-subtle);
    line-height:var(--lh-compact);
}
.pinned-list{
    margin:0;
    padding-left:18px;
    color:var(--muted);
    font-size:var(--fs-body);
    line-height:var(--lh-normal);
}
.pinned-list li{margin-bottom:4px}

/* RESPONSIVE */
@media(max-width:1200px){
    .stats-grid{grid-template-columns: repeat(8,1fr);}
    .stat-card{grid-column:span 4;}
    .row-cards{grid-template-columns:1fr;}
    .bottom-grid{grid-template-columns:1fr;}
}
@media(max-width:768px){
    .stats-grid{grid-template-columns: repeat(4,1fr);}
    .stat-card{grid-column:span 4;}
    .timeline-row{grid-template-columns:70px 1fr;}
    .page-title{flex-direction:column;align-items:flex-start;}
    .dashboard-meta-row{flex-direction:column;align-items:flex-start;}
}
</style>
@endpush


@section('main')

{{-- ===== PAGE HEADER ===== --}}
<h1 class="page-title">
    <span>{{ $user->name }}'s Workspace</span>
    <span class="page-title-badge">
        <i class="fas fa-briefcase"></i>
        <span>{{ $metrics['project_count'] }} Projects • {{ $metrics['team_members'] }} Team</span>
    </span>
</h1>

<div class="dashboard-meta-row">
    <div class="meta-chip">
        <i class="fas fa-clock"></i>
        <span>Updated {{ now()->diffForHumans() }}</span>
    </div>
    <div class="meta-chip">
        <i class="fas fa-bolt"></i>
        <span>{{ $metrics['open_tasks'] }} Open Tasks / {{ $metrics['overdue_tasks'] }} Overdue</span>
    </div>
    <div class="meta-chip">
        <i class="fas fa-chart-line"></i>
        <span>Pipeline ${{ number_format($metrics['revenue'],2) }}</span>
    </div>
</div>


{{-- ===== TOP STATS GRID ===== --}}
<div class="stats-grid">
    {{-- Visitors --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-header-left">
                <div class="stat-label">Visitors</div>
                <div class="stat-value js-count" data-target="{{ $metrics['visitors'] }}">
                    {{ number_format($metrics['visitors']) }}
                </div>
            </div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-change">
            <i class="fas fa-arrow-up"></i>
            <span class="positive">{{ $metrics['growth_7d'] }}%</span>
            <span>(last 7 days)</span>
        </div>
    </div>

    {{-- Impressions --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-header-left">
                <div class="stat-label">Impressions</div>
                <div class="stat-value js-count" data-target="{{ $metrics['impressions'] }}">
                    {{ number_format($metrics['impressions']) }}
                </div>
            </div>
            <div class="stat-icon"><i class="fas fa-eye"></i></div>
        </div>
        <div class="stat-change">
            <i class="fas fa-arrow-up"></i>
            <span class="positive">{{ $metrics['growth_30d'] }}%</span>
            <span>(last 30 days)</span>
        </div>
    </div>

    {{-- CTA Clicks --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-header-left">
                <div class="stat-label">CTA Clicks</div>
                <div class="stat-value js-count" data-target="{{ $metrics['cta_clicks'] }}">
                    {{ number_format($metrics['cta_clicks']) }}
                </div>
            </div>
            <div class="stat-icon"><i class="fas fa-mouse-pointer"></i></div>
        </div>
        <div class="stat-change">
            <i class="fas fa-arrow-down"></i>
            <span class="negative">{{ $metrics['cta_change'] }}%</span>
            <span>(vs yesterday)</span>
        </div>
    </div>

    {{-- Active Orders --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-header-left">
                <div class="stat-label">Active Orders</div>
                <div class="stat-value js-count" data-target="{{ $metrics['active_orders'] }}">
                    {{ $metrics['active_orders'] }}
                </div>
            </div>
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
        <div class="stat-change">
            <span>Currently active</span>
        </div>
    </div>

    {{-- Revenue --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-header-left">
                <div class="stat-label">Revenue (Budget)</div>
                <div class="stat-value">
                    ${{ number_format($metrics['revenue'],2) }}
                </div>
            </div>
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
        <div class="stat-change">
            <i class="fas fa-arrow-up"></i>
            <span class="positive">↑ pipeline</span>
            <span>From {{ $metrics['project_count'] }} projects</span>
        </div>
    </div>

    {{-- Overdue --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-header-left">
                <div class="stat-label">Overdue / Blocked</div>
                <div class="stat-value js-count" data-target="{{ $metrics['overude_count'] ?? $metrics['overdue_count'] }}">
                    {{ $metrics['overdue_count'] }}
                </div>
            </div>
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
        <div class="stat-change">
            <span>{{ $metrics['overdue_tasks'] }} overdue tasks</span>
        </div>
    </div>
</div>


{{-- ===== TREND CARD (LINE CHART) ===== --}}
<div class="trend-card">
    <div class="trend-header">
        <div class="trend-header-left">
            <div class="trend-title">
                <span><i class="fas fa-chart-line"></i> Trend Overview</span>
                <span class="trend-meta-chip">
                    <i class="fas fa-bolt"></i> Live Analytics
                </span>
            </div>
            <div class="trend-subtitle">
                Visitors vs Impressions over time
            </div>
        </div>

        <div class="trend-header-right">
            <button class="time-filter-btn active" data-range="7d">7d</button>
            <button class="time-filter-btn" data-range="30d">30d</button>
            <button class="time-filter-btn" data-range="90d">90d</button>
        </div>
    </div>

    <div class="chart-body-wrapper">
        <div class="chart-row">
            <canvas id="lineChartCanvas"></canvas>
        </div>
    </div>

    <div class="chart-legend">
        <div class="chart-legend-item">
            <span class="chart-legend-dot impressions"></span>
            <span>Impressions</span>
        </div>
        <div class="chart-legend-item">
            <span class="chart-legend-dot visitors"></span>
            <span>Visitors</span>
        </div>
    </div>
</div>


{{-- ===== FUNNEL + DONUT ROW ===== --}}
<div class="row-cards">
    {{-- Funnel --}}
    <div class="card-block">
        <div class="block-title">
            <i class="fas fa-filter"></i>
            <span>Conversion Funnel</span>
        </div>
        <div class="block-subtitle">
            Awareness → Contact → Paid work
        </div>

        <div class="funnel-list">
            @foreach ($funnelStages as $stage)
                <div class="funnel-row">
                    <div class="funnel-label">{{ $stage['label'] }}</div>
                    <div class="funnel-bar-wrap">
                        <div class="funnel-bar-fill" style="width: {{ $stage['percent'] }}%;"></div>
                    </div>
                    <div class="funnel-pct">{{ $stage['percent'] }}%</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Donut (Project Status Distribution) --}}
    <div class="card-block">
        <div class="block-title">
            <i class="fas fa-project-diagram"></i>
            <span>Project Status</span>
        </div>
        <div class="block-subtitle">
            Distribution across lifecycle
        </div>

        <div class="donut-wrap">
            <div class="donut-chart-box">
                <canvas id="donutChartCanvas"></canvas>
            </div>

            <div class="donut-legend" id="donutLegend">
                {{-- JS injects legend rows --}}
            </div>
        </div>
    </div>
</div>


{{-- ===== DUE SOON ===== --}}
<div class="due-soon-card">
    <div class="section-heading">
        <i class="fas fa-hourglass-half"></i>
        <span>Due Soon</span>
    </div>
    <div class="section-sub">
        Upcoming milestones & tasks sorted by closest or overdue due date
    </div>

    @forelse ($dueSoonTasks as $item)
        <div class="due-item">
            <div class="due-main">
                <h6>
                    {{ $item['title'] }}
                    <span class="due-project-name">— {{ $item['project_name'] }}</span>
                </h6>
                <div class="due-meta-line">
                    @if ($item['is_overdue'])
                        <span class="overdue-badge">Overdue</span>
                    @else
                        <span>{{ $item['due_human'] }}</span>
                    @endif
                    @if ($item['amount'])
                        • <span>{{ $item['amount'] }}</span>
                    @endif
                    • Assignee: <strong>{{ $item['assignee_name'] }}</strong>
                </div>
            </div>
            <div class="due-cta">
                <span>{{ $item['is_overdue'] ? 'Send Reminder' : 'Open →' }}</span>
            </div>
        </div>
    @empty
        <div class="due-item">
            <div class="due-main">
                <h6>No deadlines in the next 7 days</h6>
                <div class="due-meta-line">You're clear 🎉</div>
            </div>
            <div class="due-cta">—</div>
        </div>
    @endforelse
</div>


{{-- ===== TIMELINE + ACTIVITY ===== --}}
<div class="bottom-grid">

    {{-- Today Timeline --}}
    <div class="timeline-card">
        <div class="section-heading">
            <i class="fas fa-stream"></i>
            <span>Today's Timeline</span>
        </div>
        <div class="section-sub">Most recent work updates from today</div>

        <div class="timeline-list">
            @foreach ($todayTimeline as $row)
                <div class="timeline-row">
                    <div class="timeline-time-col">
                        <div>{{ $row['time'] }}</div>
                        @if ($row['duration'])
                            <div class="timeline-time-extra">{{ $row['duration'] }}</div>
                        @endif
                    </div>
                    <div class="timeline-body">
                        <div class="timeline-card-inner">
                            <div class="timeline-head">
                                <span>{{ $row['task'] }}</span>
                                @if ($row['duration'])
                                    <span class="timeline-duration">{{ $row['duration'] }}</span>
                                @endif
                            </div>
                            <div class="timeline-meta">
                                <span>
                                    <i class="fas fa-folder"></i>
                                    {{ $row['project'] }}
                                </span>
                                <span>
                                    <i class="fas fa-user"></i>
                                    {{ $row['member'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- Recent Activity --}}
    <div class="activity-card">
        <div class="section-heading">
            <i class="fas fa-bolt"></i>
            <span>Recent Activity</span>
        </div>
        <div class="section-sub">
            Team updates, approvals, and status changes
        </div>

        <div class="activity-feed">
            @forelse ($recentActivities as $act)
                <div class="activity-row">
                    <img src="{{ $act['avatar_url'] }}"
                         class="activity-avatar"
                         alt="{{ $act['user_name'] }}"
                         referrerpolicy="no-referrer"
                         crossorigin="anonymous">
                    <div class="activity-main">
                        <div class="activity-top">
                            <div class="activity-name">{{ $act['user_name'] }}</div>
                            <div class="activity-time">{{ $act['when'] }}</div>
                        </div>
                        <div class="activity-desc">
                            {{ $act['action'] }} <strong>{{ $act['task_title'] }}</strong>
                        </div>
                        <div class="activity-context">
                            <i class="fas fa-folder"></i>
                            <span>{{ $act['project_label'] }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="activity-row">
                    <div class="activity-main">
                        <div class="activity-top">
                            <div class="activity-name">No Recent Activity</div>
                            <div class="activity-time">{{ now()->diffForHumans() }}</div>
                        </div>
                        <div class="activity-desc">
                            Your workspace has been quiet. Once tasks move, updates appear here.
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection


@section('right')
    {{-- RIGHT SIDEBAR / ASSISTIVE PANEL --}}
    <div class="right-card">
        <h6>Tutorial Video</h6>
        <div class="tutorial-placeholder">
            <i class="fas fa-play-circle"></i>
        </div>
    </div>

    <div class="right-card refiner-card">
        <h6>AI Message Refiner</h6>
        <button class="refine-button js-refine-btn">
            Refine <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <div class="right-card">
        <div class="pinned-section">
            <div class="pinned-header">
                <span class="pinned-title">Pinned links</span>
                <i class="fas fa-pen pinned-edit"></i>
            </div>
            <ul class="pinned-list">
                <li>Proposal template</li>
                <li>Contract (e-sign)</li>
                <li>Invoice template</li>
                <li>Brand assets</li>
            </ul>
        </div>
    </div>

    <div class="right-card">
        <div class="pinned-section">
            <div class="pinned-header">
                <span class="pinned-title">Help & learn</span>
                <i class="fas fa-pen pinned-edit"></i>
            </div>
            <ul class="pinned-list">
                <li>How orders work</li>
                <li>Connect Stripe</li>
                <li>About milestones</li>
                <li>Keyboard shortcuts</li>
            </ul>
        </div>
    </div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"
        integrity="sha384-wR0b2JsKq0iPPl0mN1nY1NyNtgT+K2s6dwaBxGx8vXUP0d7IQ1q/EWvjXGrA+u3M"
        crossorigin="anonymous"></script>

<script>
/**
 * Dynamic data from Laravel
 */
window.DASHBOARD_DATA = {
    trendDataSets: @json($trendDataSets),
    donutData: @json($donutData),
};

/**
 * read CSS custom properties so all chart styling uses tokens only
 */
function getToken(name){
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
}
const TOK = {
    accent:        getToken('--accent'),
    accentDark:    getToken('--accent-dark'),
    accentLight:   getToken('--accent-light'),
    muted:         getToken('--muted'),
    border:        getToken('--border'),
    bg:            getToken('--bg'),
    card:          getToken('--card'),
    textPrimary:   getToken('--text-primary'),
    textBody:      getToken('--text-body'),
    apc1:          getToken('--apc-g1'),
    apc2:          getToken('--apc-g2'),
};

/**
 * animate stat counters
 */
function animateNumber(el, target){
    const startVal = parseInt(el.textContent.replace(/[^0-9]/g,''),10) || 0;
    const endVal = parseInt(target,10);
    if (!isFinite(endVal) || endVal===startVal) return;

    const dur = 650;
    const startTs = performance.now();
    function step(ts){
        const p = Math.min(1, (ts-startTs)/dur);
        const eased = 1 - Math.pow(1-p,3);
        const val = Math.round(startVal + (endVal-startVal)*eased);
        el.textContent = val.toLocaleString();
        if(p<1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

/**
 * Build / Update Line Chart
 */
let lineChart;
function renderLineChart(rangeKey){
    const dataObj = window.DASHBOARD_DATA.trendDataSets[rangeKey];

    const ctx = document.getElementById('lineChartCanvas').getContext('2d');

    const chartData = {
        labels: dataObj.labels,
        datasets: [
            {
                label: 'Impressions',
                data: dataObj.impressions,
                borderColor: TOK.accent,
                backgroundColor: TOK.accentLight,
                borderWidth: 2,
                tension: 0.35,
                fill: true,
            },
            {
                label: 'Visitors',
                data: dataObj.visitors,
                borderColor: TOK.muted,
                backgroundColor: TOK.border,
                borderWidth: 2,
                tension: 0.35,
                fill: true,
            },
        ]
    };

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                ticks: {
                    color: TOK.muted,
                },
                grid: {
                    display: false,
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: TOK.muted,
                },
                grid: {
                    color: TOK.border
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: TOK.card,
                borderColor: TOK.border,
                borderWidth: 1,
                titleColor: TOK.textPrimary,
                bodyColor: TOK.textBody,
                padding: 10,
                displayColors: true,
                boxPadding: 4,
            }
        }
    };

    if(lineChart){
        lineChart.data = chartData;
        lineChart.options = chartOptions;
        lineChart.update();
    } else {
        lineChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: chartOptions
        });
    }
}

/**
 * Build Donut Chart + Legend
 */
function renderDonut(){
    const data = window.DASHBOARD_DATA.donutData;
    const total = data.values.reduce((a,b)=>a+b,0) || 1;

    // palette ONLY from tokens
    const colors = [
        TOK.accent,
        TOK.accentDark,
        TOK.accentLight,
        TOK.apc1,
        TOK.apc2,
    ];

    const ctx = document.getElementById('donutChartCanvas').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: colors.slice(0, data.values.length),
                borderColor: colors.slice(0, data.values.length),
                borderWidth: 1,
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: TOK.card,
                    borderColor: TOK.border,
                    borderWidth: 1,
                    titleColor: TOK.textPrimary,
                    bodyColor: TOK.textBody,
                    padding: 10,
                    displayColors: true,
                    boxPadding: 4,
                    callbacks: {
                        label: function(ctx){
                            const label = ctx.label || '';
                            const value = ctx.raw || 0;
                            const pct = ((value/total)*100).toFixed(1);
                            return `${label}: ${value} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    // build legend
    const legendWrap = document.getElementById('donutLegend');
    legendWrap.innerHTML = '';
    data.labels.forEach((label, i) => {
        const value = data.values[i] || 0;
        const pct = ((value/total)*100).toFixed(1);

        const row = document.createElement('div');
        row.className = 'donut-legend-item';

        row.innerHTML = `
            <div class="donut-dot" style="background:${colors[i]};"></div>
            <div>
                <div class="donut-label">${label}</div>
                <div class="donut-desc">${value} projects • ${pct}%</div>
            </div>
        `;
        legendWrap.appendChild(row);
    });
}

/**
 * Range buttons
 */
function setupRangeButtons(){
    const buttons = document.querySelectorAll('.time-filter-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const rangeKey = btn.getAttribute('data-range');
            renderLineChart(rangeKey);
        });
    });
}

/**
 * Micro-interactions
 */
function setupInteractions(){
    // animate stat counters
    document.querySelectorAll('.js-count').forEach(el=>{
        const target = el.dataset.target || el.textContent;
        animateNumber(el, target);
    });

    // AI Refiner button demo
    const refineBtn = document.querySelector('.js-refine-btn');
    if(refineBtn){
        refineBtn.addEventListener('click', () => {
            refineBtn.disabled = true;
            refineBtn.innerHTML = 'Refining… <i class="fas fa-spinner fa-spin"></i>';
            setTimeout(()=>{
                refineBtn.disabled = false;
                refineBtn.innerHTML = 'Refine <i class="fas fa-arrow-right"></i>';
            }, 1000);
        });
    }
}

/**
 * INIT
 */
document.addEventListener('DOMContentLoaded', () => {
    renderLineChart('7d');
    renderDonut();
    setupRangeButtons();
    setupInteractions();
});
</script>
@endpush
