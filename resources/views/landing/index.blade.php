@extends('marketing.layouts.app')
@section('title', 'Index One')
@section('content')

    <!-- header area -->
    @include('marketing.partials.header')
    <!-- header area end -->

    <div id="smooth-content">
   




























































































 
        <style>
            /* Scoped design tokens */
            .cfu-hero {
              --cfu-primary: #0052CC;
              --cfu-primary-dark: hsl(249, 63%, 15%)
              ;
              --cfu-primary-light: #4C9AFF;
              --cfu-ink: #172B4D;
              --cfu-ink-light: #42526E;
              --cfu-neutral-100: #F4F5F7;
              --cfu-neutral-200: #EBECF0;
              --cfu-white: #FFFFFF;
              --cfu-gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
              --cfu-gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
              --cfu-gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
          
            /* Local reset within hero only */
            .cfu-hero, .cfu-hero * { box-sizing: border-box; }
          
            .cfu-hero {
              font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
              -webkit-font-smoothing: antialiased;
              -moz-osx-font-smoothing: grayscale;
              overflow-x: hidden;
              min-height: 100vh;
              display: flex;
              align-items: center;
              justify-content: center;
              padding: 80px 24px 80px;
              background: radial-gradient(ellipse at top, #F7F8FC 0%, #FFFFFF 100%);
              position: relative;
              overflow: hidden;
            }
          
            /* Advanced Background Animations */
            .cfu-hero::before,
            .cfu-hero::after {
              content: '';
              position: absolute;
              border-radius: 50%;
              opacity: 0.6;
              filter: blur(80px);
              animation: cfu-morphing 20s ease-in-out infinite;
            }
          
            .cfu-hero::before {
              top: -10%;
              right: -5%;
              width: 600px;
              height: 600px;
              background: radial-gradient(circle, rgba(79, 172, 254, 0.15) 0%, transparent 70%);
              animation-delay: 0s;
            }
          
            .cfu-hero::after {
              bottom: -15%;
              left: -5%;
              width: 700px;
              height: 700px;
              background: radial-gradient(circle, rgba(102, 126, 234, 0.12) 0%, transparent 70%);
              animation-delay: 3s;
            }
          
            .cfu-floating-shapes {
              position: absolute;
              width: 100%;
              height: 100%;
              top: 0;
              left: 0;
              pointer-events: none;
              overflow: hidden;
            }
          
            .cfu-shape {
              position: absolute;
              border-radius: 50%;
              filter: blur(60px);
              opacity: 0.4;
            }
          
            .cfu-shape-1 {
              top: 20%;
              left: 10%;
              width: 300px;
              height: 300px;
              background: var(--cfu-gradient-3);
              animation: cfu-float-1 15s ease-in-out infinite;
            }
          
            .cfu-shape-2 {
              top: 60%;
              right: 15%;
              width: 250px;
              height: 250px;
              background: var(--cfu-gradient-1);
              animation: cfu-float-2 18s ease-in-out infinite;
            }
          
            .cfu-shape-3 {
              bottom: 10%;
              left: 50%;
              width: 200px;
              height: 200px;
              background: var(--cfu-gradient-2);
              animation: cfu-float-3 12s ease-in-out infinite;
            }
          
            .cfu-container {
              max-width: 1280px;
              width: 100%;
              position: relative;
              z-index: 2;
            }
          
            .cfu-hero-content {
              text-align: center;
              max-width: 900px;
              margin: 0 auto;
            }
          
            .cfu-badge {
              display: inline-flex;
              align-items: center;
              gap: 10px;
              padding: 10px 20px;
              background: rgba(255, 255, 255, 0.95);
              backdrop-filter: blur(10px);
              border: 1px solid rgba(0, 82, 204, 0.1);
              border-radius: 100px;
              font-size: 14px;
              font-weight: 600;
              color: var(--cfu-primary);
              box-shadow: 0 4px 20px rgba(0, 82, 204, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05);
              margin-bottom: 40px;
              animation: cfu-badge-entrance 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s both;
              transition: all 0.3s ease;
            }
          
            .cfu-badge:hover {
              transform: translateY(-2px);
              box-shadow: 0 6px 25px rgba(0, 82, 204, 0.12), 0 2px 5px rgba(0, 0, 0, 0.08);
            }
          
            .cfu-badge-dot {
              width: 8px;
              height: 8px;
              background: var(--cfu-primary);
              border-radius: 50%;
              animation: cfu-pulse-dot 2s ease-in-out infinite;
            }
          
            .cfu-hero h1 {
              font-size: clamp(48px, 8vw, 88px);
              font-weight: 800;
              letter-spacing: -0.03em;
              line-height: 1.1;
              margin-bottom: 24px;
            }
          
            .cfu-title-line {
              display: block;
              animation: cfu-title-slide-up 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) both;
              /* line-height: 1.2; */
              padding-bottom: 10px;
            }
          
            .cfu-title-line:nth-child(1) {
              color: var(--cfu-ink);
              animation-delay: 0.5s;
            }
          
            .cfu-title-line:nth-child(2) {
              background: linear-gradient(135deg, var(--cfu-primary) 0%, var(--cfu-primary-light) 50%, var(--cfu-primary) 100%);
              background-size: 200% auto;
              -webkit-background-clip: text;
              -webkit-text-fill-color: transparent;
              background-clip: text;
              animation: cfu-title-slide-up 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) 0.6s both, 
                         cfu-gradient-flow 8s ease infinite;
            }
          
            .cfu-subtitle {
              font-size: clamp(18px, 2.5vw, 22px);
              font-weight: 400;
              line-height: 1.7;
              color: var(--cfu-ink-light);
              max-width: 720px;
              margin: 0 auto 48px;
              animation: cfu-fade-in-up 1.2s ease 0.8s both;
            }
          
            .cfu-cta-group {
              display: flex;
              gap: 16px;
              justify-content: center;
              align-items: center;
              flex-wrap: wrap;
              animation: cfu-fade-in-up 1.2s ease 1s both;
            }
          
            .cfu-btn {
              position: relative;
              padding: 18px 36px;
              font-size: 16px;
              font-weight: 600;
              border-radius: 12px;
              border: none;
              cursor: pointer;
              text-decoration: none;
              display: inline-flex;
              align-items: center;
              gap: 10px;
              transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
              overflow: hidden;
            }
          
            .cfu-btn::before {
              content: '';
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
              transform: translateX(-100%);
              transition: transform 0.6s;
            }
          
            .cfu-btn:hover::before { transform: translateX(100%); }
          
            .cfu-btn-primary {
              background: linear-gradient(135deg, var(--cfu-primary) 0%, var(--cfu-primary-light) 100%);
              color: var(--cfu-white);
              box-shadow: 0 8px 24px rgba(0, 82, 204, 0.25), 0 4px 8px rgba(0, 82, 204, 0.15);
            }
          
            .cfu-btn-primary:hover {
              transform: translateY(-3px);
              box-shadow: 0 12px 32px rgba(0, 82, 204, 0.35), 0 6px 12px rgba(0, 82, 204, 0.2);
              color: var(--cfu-white);
            }
          
            .cfu-btn-primary:active { transform: translateY(-1px); }
          
            .cfu-btn-secondary {
              background: var(--cfu-white);
              color: var(--cfu-ink);
              border: 2px solid var(--cfu-neutral-200);
              box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }
          
            .cfu-btn-secondary:hover {
              transform: translateY(-3px);
              border-color: var(--cfu-primary);
              color: var(--cfu-primary);
              box-shadow: 0 8px 20px rgba(0, 82, 204, 0.15);
            }
          
            .cfu-btn-icon { transition: transform 0.3s ease; }
            .cfu-btn:hover .cfu-btn-icon { transform: translateX(4px); }
          
            .cfu-stats {
              margin-top: 100px;
              display: grid;
              grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
              gap: 48px;
              animation: cfu-fade-in-up 1.2s ease 1.2s both;
            }
          
            .cfu-stat {
              text-align: center;
              padding: 32px 24px;
              background: rgba(255, 255, 255, 0.7);
              backdrop-filter: blur(10px);
              border-radius: 20px;
              border: 1px solid rgba(0, 82, 204, 0.08);
              transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
          
            .cfu-stat:hover {
              transform: translateY(-8px);
              background: rgba(255, 255, 255, 0.95);
              box-shadow: 0 12px 32px rgba(0, 82, 204, 0.12);
              border-color: rgba(0, 82, 204, 0.2);
            }
          
            .cfu-stat-number {
              font-size: clamp(40px, 5vw, 56px);
              font-weight: 800;
              background: linear-gradient(135deg, var(--cfu-primary) 0%, var(--cfu-primary-light) 100%);
              -webkit-background-clip: text;
              -webkit-text-fill-color: transparent;
              background-clip: text;
              margin-bottom: 12px;
              display: block;
              line-height: 1;
            }
          
            .cfu-stat-label {
              font-size: 15px;
              color: var(--cfu-ink-light);
              font-weight: 500;
              letter-spacing: 0.01em;
            }
          
            /* Animations */
            @keyframes cfu-gradient-flow {
              0%, 100% { background-position: 0% 50%; }
              50% { background-position: 100% 50%; }
            }
          
            @keyframes cfu-badge-entrance {
              0% { opacity: 0; transform: scale(0.8) translateY(-20px); }
              100% { opacity: 1; transform: scale(1) translateY(0); }
            }
          
            @keyframes cfu-title-slide-up {
              0% { opacity: 0; transform: translateY(40px); }
              100% { opacity: 1; transform: translateY(0); }
            }
          
            @keyframes cfu-fade-in-up {
              0% { opacity: 0; transform: translateY(30px); }
              100% { opacity: 1; transform: translateY(0); }
            }
          
            @keyframes cfu-pulse-dot {
              0%, 100% { transform: scale(1); opacity: 1; }
              50% { transform: scale(1.3); opacity: 0.7; }
            }
          
            @keyframes cfu-morphing {
              0%, 100% { transform: translate(0, 0) scale(1); }
              33% { transform: translate(30px, -30px) scale(1.1); }
              66% { transform: translate(-20px, 20px) scale(0.9); }
            }
          
            @keyframes cfu-float-1 {
              0%, 100% { transform: translate(0, 0) rotate(0deg); }
              33% { transform: translate(30px, -40px) rotate(5deg); }
              66% { transform: translate(-25px, 30px) rotate(-5deg); }
            }
          
            @keyframes cfu-float-2 {
              0%, 100% { transform: translate(0, 0) rotate(0deg); }
              33% { transform: translate(-35px, 25px) rotate(-5deg); }
              66% { transform: translate(30px, -30px) rotate(5deg); }
            }
          
            @keyframes cfu-float-3 {
              0%, 100% { transform: translate(0, 0) rotate(0deg); }
              33% { transform: translate(25px, 35px) rotate(5deg); }
              66% { transform: translate(-30px, -25px) rotate(-5deg); }
            }
          
            /* Responsive Design */
            @media (max-width: 768px) {
              .cfu-hero { padding: 80px 20px 60px; }
              .cfu-badge { font-size: 13px; padding: 8px 16px; margin-bottom: 32px; }
              .cfu-hero h1 { margin-bottom: 20px; }
              .cfu-subtitle { font-size: 17px; margin-bottom: 36px; }
              .cfu-cta-group { flex-direction: column; width: 100%; }
              .cfu-btn { width: 100%; justify-content: center; padding: 16px 28px; }
              .cfu-stats { margin-top: 60px; gap: 24px; grid-template-columns: 1fr; }
              .cfu-stat { padding: 24px 20px; }
            }
          
            @media (max-width: 480px) {
              .cfu-shape-1, .cfu-shape-2, .cfu-shape-3 { width: 150px; height: 150px; }
            }


            .cfu-title-line {
  display: block;
  padding-bottom: 10px;
  animation: cfu-title-slide-up 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) both, cfu-gradient-flow 8s ease infinite;
  background: linear-gradient(135deg, var(--cfu-primary) 0%, var(--cfu-primary-light) 50%, var(--cfu-primary) 100%);
  background-size: 200% auto;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.cfu-title-line:nth-child(1) {
  animation-delay: 0.5s;
}

.cfu-title-line:nth-child(2) {
  animation-delay: 0.6s;
}
          </style>
          
          <section class="cfu-hero">
            <div class="cfu-floating-shapes">
              <div class="cfu-shape cfu-shape-1"></div>
              <div class="cfu-shape cfu-shape-2"></div>
              <div class="cfu-shape cfu-shape-3"></div>
            </div>
          
            <div class="cfu-container">
              <div class="cfu-hero-content">
                <div class="cfu-badge">
                  <div class="cfu-badge-dot"></div>
                  Trusted by 2.3M+ Professionals Worldwide
                </div>
          
                <h1>
                  <span class="cfu-title-line">Where talent meets</span>
                  <span class="cfu-title-line">Opportunity</span>
                </h1>
          
                <p class="cfu-subtitle">
                  Where talent meets opportunity. Publish a polished portfolio, match with clients, 
                  create orders and milestones, manage projects with teammates, and get paid — 
                  with AI drafting your bio, case studies, and proposals from your CV.
                </p>
          
                <div class="cfu-cta-group">
                  <a href="{{route('register')}}" class="cfu-btn cfu-btn-primary">
                    Get Started Free
                    <svg class="cfu-btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                      <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </a>
                  <a href="#" class="cfu-btn cfu-btn-secondary">
                    See How It Works
                  </a>
                </div>
          
                <div class="cfu-stats">
                  <div class="cfu-stat">
                    <span class="cfu-stat-number">88%</span>
                    <div class="cfu-stat-label">Average Revenue Uplift</div>
                  </div>
                  <div class="cfu-stat">
                    <span class="cfu-stat-number">5,000+</span>
                    <div class="cfu-stat-label">Client Reviews</div>
                  </div>
                  <div class="cfu-stat">
                    <span class="cfu-stat-number">99.8%</span>
                    <div class="cfu-stat-label">Client Satisfaction</div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          




























































































































        <!-- Banner Section End -->

        <!-- brand slider start -->
        <div class="brand-slider-wrapper">
            <div class="brand-slider swiper">
                <div class="swiper-wrapper align-items-center">
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img1.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="700">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img2.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="800">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img3.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img4.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="700">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img5.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="800">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img6.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img7.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                    <div class="swiper-slide me-0 tw-py-4 border tw-border-dashed border-neutral-200 border-top-0 tw-h-114-px border-end-0 tw-px-4 d-flex justify-content-center align-items-center"
                        data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="700">
                        <div class="text-center">
                            <img src="{{asset('assets/marketing/images/thumbs/brand-img3.png')}}" alt="Logo" class="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- brand slider end -->

        <!-- About section start -->
        <section class="py-120 drag-rotate-element-section">
            <div class="container">
                <div class="tw-rounded-50-px gradient-bg-one tw-px-36-px tw-pt-9">
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <div class="tw-pe-12 position-relative">
                                <div class="row g-2">
                                    <div class="col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                        data-aos-duration="800">
                                        <div
                                            class="bg-main-600 tw-rounded-3xl tw-p-8 text-center h-100 d-flex flex-column justify-content-center align-items-center">
                                            <h3 class="text-white d-inline-flex align-items-center tw-gap-3 tw-mb-9">
                                                <span class="d-flex">
                                                    <img src="{{asset('assets/marketing/images/icons/arrow-up.svg')}}" alt="" />
                                                </span>
                                                88%
                                            </h3>
                                            <p class="text-white tw-text-sm">
                                                Average revenue uplift for engaged clients
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                        data-aos-duration="800">
                                        <div
                                            class="bg-main-two-600 tw-rounded-3xl tw-h-300-px d-flex flex-column justify-content-center align-items-center position-relative">
                                            <img src="{{asset('assets/marketing/images/thumbs/model.png')}}" alt=""
                                                class="position-absolute tw-start-50 bottom-0 tw-translate-x-50" />
                                        </div>

                                        <div
                                            class="bg-white tw-rounded-lg common-shadow-one border-bottom border-3 border-main-600 tw-px-4 tw-py-2 d-flex align-items-center tw-gap-3 z-1 position-absolute top-0 tw-end-0 tw-mt-194-px">
                                            <span
                                                class="bg-neutral-200 tw-w-10 tw-h-10 tw-rounded-xl d-flex justify-content-center align-items-center text-neutral-500">
                                                <i class="ph-bold ph-smiley"></i>
                                            </span>
                                            <div class="">
                                                <h6 class="">99.8%</h6>
                                                <p class="fw-medium tw-text-sm text-neutral-500">
                                                    Client Satisfaction
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                        data-aos-duration="800">
                                        <div
                                            class="bg-main-two-600 tw-rounded-3xl tw-p-8 text-center d-flex flex-column justify-content-center align-items-center tw-h-300-px">
                                            <div class="circle-border d-inline-block">
                                                <svg class="radial-progress" data-percentage="78" viewBox="0 0 80 80">
                                                    <circle class="incomplete" cx="40" cy="40" r="35">
                                                    </circle>
                                                    <circle class="complete" cx="40" cy="40" r="35">
                                                    </circle>
                                                    <text class="percentage" x="50%" y="57%"
                                                        transform="matrix(0, 1, -1, 0, 80, 0)">
                                                        78%
                                                    </text>
                                                </svg>
                                            </div>
                                            <p class="text-white tw-text-sm tw-mt-5">
                                                Projects delivered on time
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                        data-aos-duration="800">
                                        <div
                                            class="myContainer position-relative d-flex flex-column justify-content-center align-items-center h-100 tw-gap-705 overflow-hidden">
                                            <span
                                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-1 fw-semibold text-white bg-main-two-600 rounded-pill">Profiles</span>
                                            <span
                                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-4 tw-py-1 fw-semibold text-white bg-pink rounded-pill">Orders
                                                &amp; Milestones</span>
                                            <span
                                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-1 fw-semibold text-white bg-main-600 rounded-pill">Client
                                                Portal</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="common-shadow-three tw-rounded-40-px bg-white tw-ps-56-px tw-pe-6 tw-py-84-px">
                                <div class="tw-mb-14">
                                    <span
                                        class="tw-py-1 tw-px-705 bg-main-50 text-main-600 tw-text-sm fw-bold text-capitalize rounded-pill tw-mb-205">About
                                        Us</span>
                                    <h3 class="splitTextStyleOne fw-light tw-leading-104">
                                        <span class="d-inline-block fw-semibold">Profiles</span>
                                        <span class="d-inline-block">Projects</span>
                                        <span class="d-inline-block fw-semibold">&amp; Payments</span>
                                        <span class="d-inline-block">
                                            for
                                        </span>
                                        <span class="d-inline-block fw-semibold">
                                            Pros &amp; Teams</span>
                                    </h3>
                                </div>
                                <div class="d-flex flex-column tw-gap-10">
                                    <div class="d-flex align-items-start tw-gap-26-px animation-item" data-aos="fade-up"
                                        data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                                        <span class="tw-w-14 d-flex justify-content-center align-items-center">
                                            <img src="{{asset('assets/marketing/images/icons/about-icon1.svg')}}" alt=""
                                                class="animate__bounce" />
                                        </span>
                                        <div class="">
                                            <h6 class="tw-mb-4">
                                                AI that writes with you
                                            </h6>
                                            <p class="text-neutral-500 max-w-400-px">
                                                Upload your CV or describe your work — SkillLeo drafts your bio, skills, and case studies for a polished public profile.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start tw-gap-26-px animation-item" data-aos="fade-up"
                                        data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                                        <span class="tw-w-14 d-flex justify-content-center align-items-center">
                                            <img src="{{asset('assets/marketing/images/icons/about-icon2.svg')}}" alt=""
                                                class="animate__bounce" />
                                        </span>
                                        <div class="">
                                            <h6 class="tw-mb-4">
                                                From brief to clear scope
                                            </h6>
                                            <p class="text-neutral-500 max-w-400-px">
                                                Turn client docs or short notes into milestones, tasks, and timelines your client can approve with one click.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start tw-gap-26-px animation-item" data-aos="fade-up"
                                        data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                                        <span class="tw-w-14 d-flex justify-content-center align-items-center">
                                            <img src="{{asset('assets/marketing/images/icons/about-icon3.svg')}}" alt=""
                                                class="animate__bounce" />
                                        </span>
                                        <div class="">
                                            <h6 class="tw-mb-4">
                                                Everything in one place
                                            </h6>
                                            <p class="text-neutral-500 max-w-400-px">
                                                Messaging, files, approvals, invoices, and status updates — a simple client portal keeps everyone aligned.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About section End -->

        <!-- Roadmap section start -->
        <section class="py-120 section-bg-one">
            <div class="container max-w-1440-px">
                <!-- what we do Start -->
                <div class="bg-main-two-600 tw-rounded-3xl overflow-hidden position-relative" id="roadmap-section">
                    <div class="tw-ps-74-px tw-pe-6">
                        <div class="d-flex flex-xl-nowrap flex-wrap tw-gap-126-px">
                            <div class="max-w-432-px w-100 flex-shrink-0">
                                <div class="pt-120 tw-pb-9 position-relative">
                                    <img src="{{asset('assets/marketing/images/shapes/curve-arrow-white.png')}}" alt="Arrow"
                                        class="position-absolute top-0 tw-end-0 animate__wobble__two" />

                                    <span
                                        class="tw-py-1 tw-px-705 bg-white-13 text-white tw-text-sm fw-semibold text-capitalize rounded-pill tw-mb-3">What
                                        we do</span>
                                    <h3 class="splitTextStyleOne fw-light tw-leading-104 text-white tw-mb-6">
                                        <span class="d-inline-block fw-semibold">Working Roadmap</span>
                                    </h3>
                                    <p class="splitTextStyleOne text-neutral-400 max-w-432-px">
                                        Show → Connect → Scope → Agree → Deliver → Get Paid. A simple, repeatable flow that turns a brief into approved work.
                                    </p>
                                    <div class="tw-mt-11 d-flex align-items-center tw-gap-42-px flex-wrap">
                                        <a href="#"
                                            class="hover--translate-y-1 active--translate-y-scale-9 btn btn-main hover-style-three button--stroke d-sm-inline-flex d-none align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-px-56-px tw-py-5 fw-semibold rounded-pill"
                                            data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                            data-aos-duration="800" data-block="button">
                                            <span class="button__flair"></span>
                                            <span class="button__label">Get Started</span>
                                        </a>
                                        <div class="" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                            data-aos-duration="800">
                                            <div class="d-flex align-items-center tw-gap-4">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="tw-w-9 tw-h-9 rounded-circle overflow-hidden tw-duration-300 hover-scale-14 tw-hover-z-9 position-relative z-2">
                                                        <img src="{{asset('assets/marketing/images/thumbs/client-img1.png')}}" alt="Client Image"
                                                            class="w-100 h-100 object-fit-cover" />
                                                    </div>
                                                    <div
                                                        class="tw-w-9 tw-h-9 rounded-circle overflow-hidden tw-duration-300 hover-scale-14 tw-hover-z-9 position-relative tw--ms-10-px z-1">
                                                        <img src="{{asset('assets/marketing/images/thumbs/client-img2.png')}}" alt="Client Image"
                                                            class="w-100 h-100 object-fit-cover" />
                                                    </div>
                                                    <div
                                                        class="tw-w-9 tw-h-9 rounded-circle overflow-hidden tw-duration-300 hover-scale-14 tw-hover-z-9 position-relative tw--ms-10-px">
                                                        <img src="{{asset('assets/marketing/images/thumbs/client-img3.png')}}" alt="Client Image"
                                                            class="w-100 h-100 object-fit-cover" />
                                                    </div>
                                                </div>
                                                <span class="h5 counter text-white">2.3M+</span>
                                            </div>
                                            <p
                                                class="fw-bold tw-text-sm font-heading text-heading tw-mt-2 counter text-white">
                                                5,000+ client reviews
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex tw-gap-3 flex-wrap">
                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-main-600 tw-mt-705">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">01</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon1.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            Brainstorming
                                        </h6>
                                    </div>

                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-dark-deep tw--mt-8-px">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">02</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon2.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            UX <br />
                                            Research
                                        </h6>
                                    </div>

                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-sky-deep tw-mt-505">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">03</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon3.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            Product Designing
                                        </h6>
                                    </div>

                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-dark-deep tw--mt-28-px">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">04</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon4.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            Front-End Development
                                        </h6>
                                    </div>

                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-dark-deep tw-mt-8">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">05</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon5.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            Usability <br />
                                            Testing
                                        </h6>
                                    </div>

                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-pink tw--mt-8-px">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">06</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon6.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            Back-End Development
                                        </h6>
                                    </div>

                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-main-600 tw-mt-4">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">07</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon7.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            SEO Optimization
                                        </h6>
                                    </div>

                                    <div
                                        class="ball text-center rounded-circle tw-w-180-px tw-h-180-px tw-w-180-px d-flex flex-column justify-content-center align-items-center position-relative animation-item bg-sky-deep tw--mt-28-px">
                                        <span
                                            class="tw-h-6 tw-w-6 bg-sky rounded-circle text-white fw-medium tw-text-xs d-flex justify-content-center align-items-center position-absolute top-0 tw-start-0 tw-mt-6 tw-ms-2">08</span>
                                        <img src="{{asset('assets/marketing/images/icons/roadmap-icon8.svg')}}" alt="Icon"
                                            class="animate__swing" />
                                        <h6 class="tw-text-base text-white tw-mt-4 fw-medium max-w-118-px mx-auto">
                                            Digital Marketing
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tw-pb-9">
                        <img src="{{asset('assets/marketing/images/shapes/curve-line.png')}}" alt="" class="w-100" />
                    </div>
                </div>
                <!-- what we do end -->

                <!-- Global Increase Start -->
                <div class="pt-120">
                    <h3 class="splitTextStyleOne fw-light tw-leading-104 tw-mb-14 text-center">
                        <span class="d-inline-block">We Drive Global
                        </span>
                        <span class="d-inline-block fw-semibold">
                            Growth &amp; Revenue
                        </span>
                        <span class="d-inline-block">Together</span>
                    </h3>
                    <div class="d-flex flex-wrap justify-content-center">
                        <div class="tw-min-h-184-px bg-main-600 tw-py-4 tw-px-4 rounded-pill text-center max-w-388-px w-100"
                            data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                            <h3 class="h1 counter text-white tw-mb-4 fw-medium">
                                $29k
                            </h3>
                            <p class="text-white max-w-228-px mx-auto">
                                Average revenue uplift per successful client
                            </p>
                        </div>

                        <div class="tw-min-h-184-px bg-main-600 tw-py-4 tw-px-4 rounded-pill max-w-514-px w-100 d-flex align-items-center tw-gap-505 justify-content-center"
                            data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                            <h3 class="h1 counter text-white tw-mb-4 fw-medium d-inline-flex align-items-center tw-gap-4">
                                <img src="{{asset('assets/marketing/images/icons/arrow-up-green.svg')}}" alt="" />
                                88%
                            </h3>
                            <p class="text-white max-w-194-px">
                                Average client revenue growth
                            </p>
                        </div>

                        <div class="tw-min-h-184-px bg-white tw-py-4 tw-px-4 rounded-pill max-w-388-px w-100 d-flex align-items-center tw-gap-505 justify-content-center"
                            data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                            <h3 class="h1 counter text-main-600 fw-medium d-inline-flex align-items-center tw-gap-4">
                                3x
                            </h3>
                            <div class="d-flex align-items-center tw-gap-2">
                                <span class="text-main-two-600 tw-text-2xl fw-semibold">//</span>
                                <p class="text-main-two-600 max-w-194-px">
                                    Faster time to launch
                                </p>
                            </div>
                        </div>

                        <div class="tw-min-h-184-px bg-white tw-py-4 tw-px-4 rounded-pill max-w-490-px w-100 d-flex align-items-center tw-gap-505 justify-content-center border border-main-50"
                            data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                            <h3 class="h1 counter text-main-600 fw-medium d-inline-flex align-items-center tw-gap-4">
                                50%
                            </h3>
                            <p class="text-main-two-600 max-w-194-px">
                                Improved financial performance vs. previous benchmarks
                            </p>
                        </div>

                        <div class="tw-min-h-184-px bg-main-600 tw-py-4 tw-px-4 rounded-pill text-center max-w-288-px w-100 tw-mt-1 d-flex flex-column justify-content-center"
                            data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                            <h3 class="h1 counter text-white fw-medium">
                                95%
                            </h3>
                            <p class="text-white max-w-228-px mx-auto">
                                Positive review rate
                            </p>
                        </div>

                        <div class="tw-min-h-184-px bg-white tw-py-4 tw-px-4 rounded-pill max-w-514-px w-100 d-flex align-items-center tw-gap-505 justify-content-center border border-main-50"
                            data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                            <h3 class="h1 counter text-main-600 fw-medium d-inline-flex align-items-center tw-gap-4">
                                1.3m
                            </h3>
                            <div class="d-flex align-items-center tw-gap-2">
                                <p class="text-main-two-600 max-w-194-px">
                                    Aggregate revenue uplift across clients
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Global Increase End -->
            </div>
        </section>
        <!-- Roadmap section End -->

        <!-- Offer section start -->
        <section class="offer py-120 overflow-hidden">
            <div class="container">
                <div class="tw-mb-13">
                    <span
                        class="tw-py-1 tw-px-705 bg-main-50 text-main-600 tw-text-sm fw-bold text-capitalize rounded-pill tw-mb-205">What
                        We Offer</span>
                    <div class="d-flex flex-lg-nowrap flex-wrap justify-content-between align-items-center">
                        <div class="max-w-672-px">
                            <h3 class="splitTextStyleOne tw-leading-104">
                                The platform to create profiles, win work, and get paid
                            </h3>
                        </div>
                        <p class="splitTextStyleOne text-neutral-500 max-w-500-px">
                            For independent pros, micro-agencies, and clients: publish a premium profile, scope orders into milestones, manage projects, and complete secure payments — with AI drafting and summaries throughout.
                        </p>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-lg-4">
                        <div class="row gy-4">
                            <div class="col-lg-12 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                data-aos-duration="600">
                                <div
                                    class="group-item position-relative tw-rounded-3xl overflow-hidden z-1 h-100 tw-pt-15 bg-pink-dark tw-px-40-px tw-pb-84-px tw-duration-300">
                                    <span class="tw-text-base fw-semibold tw-mb-2 text-pink">Core feature</span>
                                    <h4 class="splitTextStyleOne tw-mb-5 max-w-218-px">
                                        Publish Profile &amp; Portfolio
                                    </h4>
                                    <a href="#"
                                        class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9">
                                        <i class="ph-bold ph-arrow-up-right"></i>
                                    </a>
                                    <img src="{{asset('assets/marketing/images/thumbs/offer-img1.png')}}" alt="Image"
                                        class="tw-duration-300 position-absolute bottom-0 tw-end-0 z-n1" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                data-aos-duration="600">
                                <div
                                    class="group-item position-relative tw-rounded-3xl overflow-hidden z-1 h-100 tw-pt-15 bg-pink-lighter tw-px-40-px tw-pb-136-px tw-duration-300">
                                    <span class="tw-text-base fw-semibold tw-mb-2 text-pink">AI assistant</span>
                                    <h4 class="splitTextStyleOne fw-light tw-mb-5 max-w-330-px">
                                        <span class="d-inline-block fw-semibold">AI Profile &amp; Case Study Builder</span>
                                    </h4>
                                    <a href="#"
                                        class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-pink-light tw-text-2xl text-white hover--translate-y-1 active--translate-y-scale-9">
                                        <i class="ph-bold ph-arrow-up-right"></i>
                                    </a>
                                    <img src="{{asset('assets/marketing/images/thumbs/offer-img2.png')}}" alt="Image"
                                        class="tw-duration-300 position-absolute bottom-0 tw-end-0 z-n1" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                        data-aos-duration="600">
                        <div
                            class="group-item position-relative tw-rounded-3xl overflow-hidden z-1 tw-pt-15 bg-main-50 tw-px-40-px tw-pb-84-px tw-duration-300 h-100">
                            <span class="text-main-600 fw-semibold tw-mb-2">Project tools</span>
                            <h4 class="splitTextStyleOne tw-mb-5 max-w-218-px">
                                Orders, Milestones &amp; Payments
                            </h4>
                            <a href="#"
                                class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle text-white tw-text-2xl bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9">
                                <i class="ph-bold ph-arrow-up-right"></i>
                            </a>
                            <img src="{{asset('assets/marketing/images/thumbs/offer-img3.png')}}" alt="Image"
                                class="tw-duration-300 position-absolute bottom-0 tw-end-0 z-n1 tw-mx-4 tw-mb-12 d-md-block d-none" />
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="row gy-4">
                            <div class="col-lg-12 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                data-aos-duration="600">
                                <div
                                    class="group-item position-relative tw-rounded-3xl overflow-hidden z-1 tw-pt-15 h-100 bg-purple-light tw-px-40-px tw-pb-84-px tw-duration-300">
                                    <span class="tw-text-base fw-semibold tw-mb-2 text-purple">Client-ready</span>
                                    <h4 class="splitTextStyleOne tw-mb-5 max-w-218-px">
                                        Client Portal &amp; Project Docs
                                    </h4>
                                    <a href="#"
                                        class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9">
                                        <i class="ph-bold ph-arrow-up-right"></i>
                                    </a>
                                    <img src="{{asset('assets/marketing/images/thumbs/offer-img4.png')}}" alt="Image"
                                        class="tw-duration-300 position-absolute bottom-0 tw-end-0 z-n1" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                data-aos-duration="600">
                                <div
                                    class="group-item position-relative tw-rounded-3xl overflow-hidden z-1 tw-pt-15 h-100 bg-paste-light tw-px-40-px tw-pb-136-px tw-duration-300">
                                    <span class="tw-text-base fw-semibold tw-mb-2 text-pink">Team-first</span>
                                    <h4 class="splitTextStyleOne fw-light tw-mb-5 max-w-330-px">
                                        <span class="d-inline-block fw-semibold">Team Projects &amp; Collaboration</span>
                                    </h4>
                                    <a href="#"
                                        class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-paste tw-text-2xl text-white hover--translate-y-1 active--translate-y-scale-9">
                                        <i class="ph-bold ph-arrow-up-right"></i>
                                    </a>
                                    <img src="{{asset('assets/marketing/images/thumbs/offer-img5.png')}}" alt="Image"
                                        class="tw-duration-300 position-absolute bottom-0 tw-end-0 z-n1" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Offer section end -->

        <!-- FAQ section start -->
        <section class="faq py-120 position-relative z-1 overflow-hidden">
            <img src="{{asset('assets/marketing/images/shapes/faq-bg.png')}}" alt=""
                class="position-absolute tw-start-0 top-0 w-100 h-100 z-n1" />

            <div class="container">
                <div class="tw-mb-8">
                    <span
                        class="tw-py-1 tw-px-705 bg-white text-main-600 tw-text-sm fw-bold text-capitalize rounded-pill tw-mb-205">SkillLeo
                        Platform</span>
                    <div class="d-flex flex-lg-nowrap flex-wrap justify-content-between align-items-center">
                        <div class="max-w-672-px">
                            <h3 class="splitTextStyleOne fw-light tw-leading-104">
                                <span class="d-inline-block fw-semibold">The Complete
                                </span>
                                <span class="d-inline-block">Platform
                                </span>
                                <span class="d-inline-block fw-semibold">To
                                </span>
                                <span class="d-inline-block fw-semibold">
                                    Power
                                </span>
                                <span class="d-inline-block fw-semibold">Your
                                </span>
                                <span class="d-inline-block fw-semibold">Workflow</span>
                            </h3>
                        </div>
                        <a href="#"
                            class="hover--translate-y-1 active--translate-y-scale-9 btn btn-main hover-style-one button--stroke d-sm-inline-flex d-none align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-px-56-px tw-py-5 fw-semibold rounded-pill"
                            data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Sign up Now</span>
                        </a>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-lg-5">
                        <p class="splitTextStyleOne text-neutral-600 max-w-500-px tw-mb-15">
                            Create a profile, post a project, agree on milestones, and get paid — all in one place. Upload your CV or project docs and let AI do the heavy lifting.
                        </p>
                        <div class="accordion common-accordion accordion-border-left" id="accordionExample">
                            <div class="accordion-item tw-py-4 tw-px-40-px tw-rounded-xl bg-transparent border-0 mb-0"
                                data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="800">
                                <h5 class="accordion-header">
                                    <button
                                        class="accordion-button tw-pb-8 tw-pt-4 shadow-none px-0 bg-transparent h5 collapsed"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                        aria-expanded="false" aria-controls="collapseTwo"
                                        data-img="{{asset('assets/marketing/images/thumbs/faq-thumb2.png')}}">
                                        Create &amp; publish your profile
                                    </button>
                                </h5>
                                <div id="collapseTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body p-0">
                                        <p class="text-neutral-500">
                                            Upload your CV or describe your work. AI drafts your bio, skills, and case studies. You review, edit, and publish.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item tw-py-4 tw-px-40-px tw-rounded-xl bg-transparent border-0 mb-0"
                                data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="800">
                                <h5 class="accordion-header">
                                    <button class="accordion-button tw-pb-8 tw-pt-4 shadow-none px-0 bg-transparent h5"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne"
                                        data-img="{{asset('assets/marketing/images/thumbs/faq-thumb1.png')}}">
                                        Scope orders into milestones
                                    </button>
                                </h5>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body p-0">
                                        <p class="text-neutral-500">
                                            Turn a brief or PDF into a clear plan with milestones, tasks, and acceptance criteria your client can approve.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item tw-py-4 tw-px-40-px tw-rounded-xl bg-transparent border-0 mb-0"
                                data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="800">
                                <h5 class="accordion-header">
                                    <button
                                        class="accordion-button tw-pb-8 tw-pt-4 shadow-none px-0 bg-transparent h5 collapsed"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                        aria-expanded="false" aria-controls="collapseThree"
                                        data-img="{{asset('assets/marketing/images/thumbs/faq-thumb3.png')}}">
                                        Client portal, files &amp; approvals
                                    </button>
                                </h5>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body p-0">
                                        <p class="text-neutral-500">
                                            Keep everything tidy — messages, files, and approvals — so clients can track progress and sign off quickly.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item tw-py-4 tw-px-40-px tw-rounded-xl bg-transparent border-0 mb-0"
                                data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="800">
                                <h5 class="accordion-header">
                                    <button
                                        class="accordion-button tw-pb-8 tw-pt-4 shadow-none px-0 bg-transparent h5 collapsed"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                        aria-expanded="false" aria-controls="collapseFour"
                                        data-img="{{asset('assets/marketing/images/thumbs/faq-thumb4.png')}}">
                                        Contracts, invoices &amp; payments
                                    </button>
                                </h5>
                                <div id="collapseFour" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body p-0">
                                        <p class="text-neutral-500">
                                            Send contracts and branded invoices with payment links. Get paid per milestone — fast and securely.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="position-relative tw-pb-8 tw-ps-12 h-100">
                            <div class="bg-white tw-p-205 tw-rounded-3xl h-100 tw-min-h-400-px">
                                <img src="{{asset('assets/marketing/images/thumbs/faq-thumb1.png')}}" id="faqImage" alt="Image"
                                    class="w-100 h-100 object-fit-cover tw-rounded-2xl tw-duration-300" />
                            </div>
                            <img src="{{asset('assets/marketing/images/thumbs/faq-img-1.png')}}" alt=""
                                class="position-absolute top-0 tw-end-0 tw-mt-9 tw--me-48-px" />
                            <img src="{{asset('assets/marketing/images/thumbs/faq-img-2.png')}}" alt=""
                                class="position-absolute bottom-0 tw-end-0 tw--me-32-px" />
                            <img src="{{asset('assets/marketing/images/thumbs/faq-img-3.png')}}" alt=""
                                class="position-absolute bottom-0 tw-start-0 tw--ms-24-px" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ section end -->

        <!-- Choose Us section start -->
        <section class="py-120 overflow-hidden">
            <div class="container">
                <div class="row gy-4 gx-5">
                    <div class="col-lg-5 d-sm-block d-none">
                        <div class="tw-ps-8 position-relative z-1 overflow-hidden" id="box-wrapper">
                            <img src="{{asset('assets/marketing/images/thumbs/choose-us-img.png')}}" alt="Image"
                                class="w-100 h-100 object-fit-cover tw-rounded-3xl" />

                            <div
                                class="bg-white max-w-150-px tw-h-150-px w-100 d-flex justify-content-center align-items-center tw-rounded-xl common-shadow-four box position-absolute tw-start-0 top-0 tw-mt-705 z-1">
                                <img src="{{asset('assets/marketing/images/thumbs/choose-us-icon1.png')}}" alt="" />
                            </div>
                            <div
                                class="bg-white max-w-150-px tw-h-150-px w-100 d-flex justify-content-center align-items-center tw-rounded-xl common-shadow-four box position-absolute tw-end-0 top-0 tw-mt-705 z-1 tw-me-14">
                                <img src="{{asset('assets/marketing/images/thumbs/choose-us-icon2.png')}}" alt="" />
                            </div>
                            <div
                                class="bg-white max-w-150-px tw-h-150-px w-100 d-flex justify-content-center align-items-center tw-rounded-xl common-shadow-four box position-absolute tw-start-0 top-0 z-1 tw-mt-194-px tw-ms-148-px">
                                <img src="{{asset('assets/marketing/images/thumbs/choose-us-icon3.png')}}" alt="" />
                            </div>
                            <div
                                class="bg-white max-w-150-px tw-h-150-px w-100 d-flex justify-content-center align-items-center tw-rounded-xl common-shadow-four box position-absolute tw-end-0 bottom-0 tw-me-90-px z-1 tw-mb-148-px">
                                <img src="{{asset('assets/marketing/images/thumbs/choose-us-icon4.png')}}" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="ps-lg-5">
                            <span
                                class="tw-py-1 tw-px-705 bg-main-600 text-white tw-text-sm fw-bold text-capitalize rounded-pill tw-mb-205">Why
                                Choose Us</span>
                            <div class="max-w-672-px">
                                <h3 class="fw-light tw-leading-104 cursor-big tw-mb-9 d-flex gap-2">
                                    <span class="splitTextStyleOne d-inline-block fw-medium">
                                        <span class="fw-semibold">SkillLeo</span>
                                        lets you show your best work, scope projects clearly, and get to
                                        Business
                                        <span class="fw-semibold">
                                            Growth.</span>
                                    </span>
                                </h3>
                                <p class="splitTextStyleOne text-neutral-600 max-w-500-px">
                                    Publish a credible profile, turn briefs into milestones, and manage delivery with a delightful client portal — all in one place.
                                </p>
                            </div>

                            <div class="tw-mt-10">
                                <div class="row gy-4">
                                    <div class="col-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                        data-aos-duration="600">
                                        <div
                                            class="bg-neutral-50 tw-ps-705 tw-pe-4 tw-py-9 border border-neutral-50 hover-border-main-600 tw-rounded-lg tw-duration-300">
                                            <h6 class="tw-mb-2">
                                                Profiles that convert
                                            </h6>
                                            <p class="text-neutral-500">
                                                AI-assisted bios, skill tags, and case studies that help you land the next conversation.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                        data-aos-duration="600">
                                        <div
                                            class="bg-neutral-50 tw-ps-705 tw-pe-4 tw-py-9 border border-neutral-50 hover-border-main-600 tw-rounded-lg tw-duration-300">
                                            <h6 class="tw-mb-2">
                                                Milestones &amp; transparency
                                            </h6>
                                            <p class="text-neutral-500">
                                                Clear scope, visible progress, and fast approvals keep projects moving and clients confident.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center tw-gap-505 tw-mt-8" data-aos="fade-up"
                                data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                                <h3 class="h1 text-main-600 d-inline-flex align-items-center tw-gap-4">
                                    1.3m
                                </h3>
                                <p class="text-main-two-600 fw-medium max-w-228-px">
                                    Aggregate earnings processed across creators and teams
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Choose Us section end -->

        <!-- Show-case section start -->
        <section class="show-case py-120 bg-main-two-600 overflow-hidden">
            <div class="max-w-602-px mx-auto text-center tw-mb-15">
                <span
                    class="tw-py-1 tw-px-705 bg-white-13 text-white tw-text-sm fw-medium text-capitalize rounded-pill tw-mb-205">Work
                    Showcase</span>
                <h3 class="fw-light tw-leading-104 tw-mb-9 text-white">
                    <span class="splitTextStyleOne d-inline-block fw-medium">
                        <span class="fw-semibold">Profiles &amp; Portfolios
                        </span>
                        <span class=""> that</span>
                        <span class="fw-semibold"> Win Work</span>
                    </span>
                </h3>
            </div>

            <div class="show-case-slider swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                        data-aos-duration="600">
                        <div class="group-item">
                            <div class="position-relative">
                                <a href="#"
                                    class="w-100 h-100 tw-max-h-410-px overflow-hidden tw-rounded-28-px">
                                    <img src="{{asset('assets/marketing/images/thumbs/show-case-img1.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                </a>
                                <a href="#"
                                    class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9 position-absolute top-0 tw-end-0 tw-me-8 tw-mt-8 hidden opacity-0 group-hover-item-opacity-1 group-hover-item-visible tw-scale-04 group-hover-item-scale-1">
                                    <i class="ph-bold ph-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="tw-mt-8">
                                <span class="tw-text-base fw-medium text-white">Featured Case Study</span>
                                <h5 class="text-white tw-mt-1">
                                    <a href="#"
                                        class="hover-text-main-600 text-white line-clamp-1 hover--translate-y-1">Mobile
                                        Application
                                        Development</a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                        data-aos-duration="600">
                        <div class="group-item">
                            <div class="position-relative">
                                <a href="#"
                                    class="w-100 h-100 tw-max-h-410-px overflow-hidden tw-rounded-28-px">
                                    <img src="{{asset('assets/marketing/images/thumbs/show-case-img2.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                </a>
                                <a href="#"
                                    class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9 position-absolute top-0 tw-end-0 tw-me-8 tw-mt-8 hidden opacity-0 group-hover-item-opacity-1 group-hover-item-visible tw-scale-04 group-hover-item-scale-1">
                                    <i class="ph-bold ph-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="tw-mt-8">
                                <span class="tw-text-base fw-medium text-white">Featured Case Study</span>
                                <h5 class="text-white tw-mt-1">
                                    <a href="#"
                                        class="hover-text-main-600 text-white line-clamp-1 hover--translate-y-1">Cloud
                                        Computing System</a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                        data-aos-duration="600">
                        <div class="group-item">
                            <div class="position-relative">
                                <a href="#"
                                    class="w-100 h-100 tw-max-h-410-px overflow-hidden tw-rounded-28-px">
                                    <img src="{{asset('assets/marketing/images/thumbs/show-case-img3.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                </a>
                                <a href="#"
                                    class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9 position-absolute top-0 tw-end-0 tw-me-8 tw-mt-8 hidden opacity-0 group-hover-item-opacity-1 group-hover-item-visible tw-scale-04 group-hover-item-scale-1">
                                    <i class="ph-bold ph-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="tw-mt-8">
                                <span class="tw-text-base fw-medium text-white">Featured Case Study</span>
                                <h5 class="text-white tw-mt-1">
                                    <a href="#"
                                        class="hover-text-main-600 text-white line-clamp-1 hover--translate-y-1">Mobile
                                        Application
                                        Development</a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                        data-aos-duration="600">
                        <div class="group-item">
                            <div class="position-relative">
                                <a href="#"
                                    class="w-100 h-100 tw-max-h-410-px overflow-hidden tw-rounded-28-px">
                                    <img src="{{asset('assets/marketing/images/thumbs/show-case-img4.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                </a>
                                <a href="#"
                                    class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9 position-absolute top-0 tw-end-0 tw-me-8 tw-mt-8 hidden opacity-0 group-hover-item-opacity-1 group-hover-item-visible tw-scale-04 group-hover-item-scale-1">
                                    <i class="ph-bold ph-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="tw-mt-8">
                                <span class="tw-text-base fw-medium text-white">Featured Case Study</span>
                                <h5 class="text-white tw-mt-1">
                                    <a href="#"
                                        class="hover-text-main-600 text-white line-clamp-1 hover--translate-y-1">Creative
                                        UI/UX Designing</a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                        data-aos-duration="600">
                        <div class="group-item">
                            <div class="position-relative">
                                <a href="#"
                                    class="w-100 h-100 tw-max-h-410-px overflow-hidden tw-rounded-28-px">
                                    <img src="{{asset('assets/marketing/images/thumbs/show-case-img3.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                </a>
                                <a href="#"
                                    class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9 position-absolute top-0 tw-end-0 tw-me-8 tw-mt-8 hidden opacity-0 group-hover-item-opacity-1 group-hover-item-visible tw-scale-04 group-hover-item-scale-1">
                                    <i class="ph-bold ph-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="tw-mt-8">
                                <span class="tw-text-base fw-medium text-white">Featured Case Study</span>
                                <h5 class="text-white tw-mt-1">
                                    <a href="#"
                                        class="hover-text-main-600 text-white line-clamp-1 hover--translate-y-1">Mobile
                                        Application
                                        Development</a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                        data-aos-duration="600">
                        <div class="group-item">
                            <div class="position-relative">
                                <a href="#"
                                    class="w-100 h-100 tw-max-h-410-px overflow-hidden tw-rounded-28-px">
                                    <img src="{{asset('assets/marketing/images/thumbs/show-case-img2.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                </a>
                                <a href="#"
                                    class="tw-w-15 tw-h-15 d-flex justify-content-center align-items-center rounded-circle bg-white tw-text-2xl hover-bg-main-600 hover-text-white hover--translate-y-1 active--translate-y-scale-9 position-absolute top-0 tw-end-0 tw-me-8 tw-mt-8 hidden opacity-0 group-hover-item-opacity-1 group-hover-item-visible tw-scale-04 group-hover-item-scale-1">
                                    <i class="ph-bold ph-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="tw-mt-8">
                                <span class="tw-text-base fw-medium text-white">Featured Case Study</span>
                                <h5 class="text-white tw-mt-1">
                                    <a href="#"
                                        class="hover-text-main-600 text-white line-clamp-1 hover--translate-y-1">Cloud
                                        Computing System</a>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Show-case section End -->

        <!-- Testimonials section start -->
        <section class="testimonials py-120 bg-neutral-50 position-relative z-1">
            <img src="{{asset('assets/marketing/images/shapes/faq-bg.png')}}" alt=""
                class="position-absolute tw-start-0 top-0 w-100 h-100 z-n1" />

            <div class="container">
                <div class="max-w-602-px mx-auto text-center tw-mb-15">
                    <span
                        class="tw-py-1 tw-px-705 bg-neutral-100 tw-text-sm fw-medium text-capitalize rounded-pill tw-mb-205">Work
                        Showcase</span>
                    <h3 class="splitTextStyleOne fw-light tw-leading-104 tw-mb-9 d-flex gap-2">
                        <span class="d-inline-block fw-medium">
                            <span class="">What </span>
                            <span class="fw-semibold">
                                Our Clients
                            </span>
                            <span class=""> Say</span>
                            <span class="fw-semibold">About Us</span>
                        </span>
                    </h3>
                </div>

                <div class="d-flex tw-gap-6 testimonials-item-wrapper flex-wrap">
                    <!-- Testimonials Item Start -->
                    <div class="testimonials-item cursor-pointer bg-white tw-rounded-28-px border border-neutral-100">
                        <div class="current-content">
                            <div class="tw-max-h-397-px overflow-hidden text-center">
                                <img src="{{asset('assets/marketing/images/thumbs/testimonials-img1.png')}}" alt="Image" class="" />
                            </div>
                            <div class="tw-py-8 tw-px-4 text-center">
                                <h6 class="tw-mb-2">Webcly jhonson</h6>
                                <span class="text-neutral-600 fw-medium tw-text-sm">
                                    <span class="fw-semibold text-neutral-600">Tung Phan -</span>
                                    Ceo and Founder
                                </span>
                            </div>
                        </div>

                        <div class="hidden-content tw-px-56-px tw-py-76-px">
                            <div class="">
                                <span class="tw-mb-6 animate-left-right animation-delay-02 tw-duration-200">
                                    <img src="{{asset('assets/marketing/images/icons/ratings.svg')}}" alt="" class="" />
                                </span>
                                <div class="border-bottom border-neutral-200 tw-pb-10 tw-mb-8">
                                    <p
                                        class="testimonials-item__desc h5 text-heading fw-medium tw-leading-153 animate-left-right animation-delay-03 tw-duration-200 line-clamp-4">
                                        “SkillLeo helped us move from scattered docs to a clear milestone plan. Clients understood the scope, approved fast, and payments were smooth.”
                                    </p>
                                </div>
                            </div>
                            <div
                                class="d-flex align-items-center tw-gap-6 animate-left-right animation-delay-04 tw-duration-200">
                                <div class="tw-h-84-px tw-w-84-px rounded-circle">
                                    <img src="{{asset('assets/marketing/images/thumbs/testimonials-short-img1.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover" />
                                </div>
                                <div class="">
                                    <h6 class="tw-mb-2">
                                        Webcly jhonson
                                    </h6>
                                    <span class="text-neutral-600">
                                        <span class="fw-semibold text-neutral-600">Tung Phan -
                                        </span>
                                        and Founder
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonials Item End -->

                    <!-- Testimonials Item Start -->
                    <div
                        class="testimonials-item cursor-pointer bg-white tw-rounded-28-px border border-neutral-100 active">
                        <div class="current-content">
                            <div class="tw-max-h-397-px overflow-hidden text-center">
                                <img src="{{asset('assets/marketing/images/thumbs/testimonials-img3.png')}}" alt="Image" class="" />
                            </div>
                            <div class="tw-py-8 tw-px-4 text-center">
                                <h6 class="tw-mb-2">James anderson</h6>
                                <span class="text-neutral-600 fw-medium tw-text-sm">
                                    <span class="fw-semibold text-main-600">CEO -</span>
                                    and Founder
                                </span>
                            </div>
                        </div>

                        <div class="hidden-content tw-px-56-px tw-py-76-px">
                            <div class="">
                                <span class="tw-mb-6 animate-left-right animation-delay-02 tw-duration-200">
                                    <img src="{{asset('assets/marketing/images/icons/ratings.svg')}}" alt="" class="" />
                                </span>
                                <div class="border-bottom border-neutral-200 tw-pb-10 tw-mb-8">
                                    <p
                                        class="testimonials-item__desc h5 text-heading fw-medium tw-leading-153 animate-left-right animation-delay-03 tw-duration-200 line-clamp-4">
                                        “We uploaded our CVs and got AI-drafted profiles and case studies in minutes. Everything — tasks, files, invoices — lives in one place.”
                                    </p>
                                </div>
                            </div>
                            <div
                                class="d-flex align-items-center tw-gap-6 animate-left-right animation-delay-04 tw-duration-200">
                                <div class="tw-h-84-px tw-w-84-px rounded-circle">
                                    <img src="{{asset('assets/marketing/images/thumbs/testimonials-short-img3.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover" />
                                </div>
                                <div class="">
                                    <h6 class="tw-mb-2">
                                        James anderson
                                    </h6>
                                    <span class="text-neutral-600">
                                        <span class="fw-semibold text-main-600">CEO</span>
                                        and Founder
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonials Item End -->

                    <!-- Testimonials Item Start -->
                    <div class="testimonials-item cursor-pointer bg-white tw-rounded-28-px border border-neutral-100">
                        <div class="current-content">
                            <div class="tw-max-h-397-px overflow-hidden text-center">
                                <img src="{{asset('assets/marketing/images/thumbs/testimonials-img2.png')}}" alt="Image" class="" />
                            </div>
                            <div class="tw-py-8 tw-px-4 text-center">
                                <h6 class="tw-mb-2">John Doe</h6>
                                <span class="text-neutral-600 fw-medium tw-text-sm">
                                    <span class="fw-semibold text-neutral-600">Developer -</span>
                                    Web Developer
                                </span>
                            </div>
                        </div>

                        <div class="hidden-content tw-px-56-px tw-py-76-px">
                            <div class="">
                                <span class="tw-mb-6 animate-left-right animation-delay-02 tw-duration-200">
                                    <img src="{{asset('assets/marketing/images/icons/ratings.svg')}}" alt="" class="" />
                                </span>
                                <div class="border-bottom border-neutral-200 tw-pb-10 tw-mb-8">
                                    <p
                                        class="testimonials-item__desc h5 text-heading fw-medium tw-leading-153 animate-left-right animation-delay-03 tw-duration-200 line-clamp-4">
                                        “SkillLeo’s milestone approvals and payment links cut our back-and-forth in half. Clients always know what’s next.”
                                    </p>
                                </div>
                            </div>
                            <div
                                class="d-flex align-items-center tw-gap-6 animate-left-right animation-delay-04 tw-duration-200">
                                <div class="tw-h-84-px tw-w-84-px rounded-circle">
                                    <img src="{{asset('assets/marketing/images/thumbs/testimonials-short-img2.png')}}" alt="Image"
                                        class="w-100 h-100 object-fit-cover" />
                                </div>
                                <div class="">
                                    <h6 class="tw-mb-2">
                                        Webcly jhonson
                                    </h6>
                                    <span class="text-neutral-600 fw-medium tw-text-sm">
                                        <span class="fw-semibold text-neutral-600">Developer -</span>
                                        Web Developer
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonials Item End -->
                </div>
            </div>
        </section>
        <!-- Testimonials section End -->

        <div class="position-relative z-1">
            <img src="{{asset('assets/marketing/images/shapes/shape-image.png')}}" alt="Shape"
                class="position-absolute w-100 tw-start-0 bottom-0 z-n1" />
            <!-- Blog section start -->
            <section class="blog">
                <div class="container">
                    <div class="bg-white common-shadow-five py-120 tw-px-90-px">
                        <span class="line w-0 tw-h-2 bg-main-600 tw-mb-4"></span>
                        <div class="d-flex align-items-center justify-content-between tw-mb-12">
                            <h6 class="">
                                Insights to help you turn profiles into paid work — faster, clearer, and with less friction.
                            </h6>
                            <a href="#"
                                class="hover--translate-y-1 active--translate-y-scale-9 btn btn-main-two hover-style-two button--stroke d-sm-inline-flex d-none align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-px-9 rounded-pill tw-py-4"
                                data-block="button">
                                <span class="button__flair"></span>
                                <span class="button__label">Sign Up Now</span>
                            </a>
                        </div>

                        <!-- Blog items start -->
                        <div class="row gy-4">
                            <div class="col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                data-aos-duration="200">
                                <div class="group-item">
                                    <a href="#" class="w-100 h-100 overflow-hidden">
                                        <img src="{{asset('assets/marketing/images/thumbs/blog-img1.png')}}" alt="Blog Image"
                                            class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                    </a>
                                    <div class="tw-mt-7 d-flex align-items-center tw-gap-5 flex-wrap">
                                        <span
                                            class="text-heading fw-medium tw-py-05 tw-px-405 border border-neutral-400 rounded-pill tw-text-base">02
                                            Apr 2021</span>
                                        <span class="text-heading fw-medium tw-text-base">Comments (03)</span>
                                    </div>
                                    <h5 class="tw-mt-4">
                                        <a href="#"
                                            class="hover-text-main-600 line-clamp-1 tw-mb-3 hover--translate-y-1">Portfolio Basics: 7 tips to make your profile convert</a>
                                    </h5>
                                    <a href="#"
                                        class="text-main-600 fw-medium tw-text-lg hover--translate-y-1">
                                        Learn More
                                        <span class="d-inline-flex tw-text-sm">
                                            <i class="ph-bold ph-arrow-up-right"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
                                data-aos-duration="400">
                                <div class="group-item">
                                    <a href="#" class="w-100 h-100 overflow-hidden">
                                        <img src="{{asset('assets/marketing/images/thumbs/blog-img2.png')}}" alt="Blog Image"
                                            class="w-100 h-100 object-fit-cover group-hover-item-scale-12 tw-duration-300" />
                                    </a>
                                    <div class="tw-mt-7 d-flex align-items-center tw-gap-5 flex-wrap">
                                        <span
                                            class="text-heading fw-medium tw-py-05 tw-px-405 border border-neutral-400 rounded-pill tw-text-base">02
                                            Apr 2021</span>
                                        <span class="text-heading fw-medium tw-text-base">Comments (03)</span>
                                    </div>
                                    <h5 class="tw-mt-4">
                                        <a href="#"
                                            class="hover-text-main-600 line-clamp-1 tw-mb-3 hover--translate-y-1">Milestones that work: scope your next project in 20 minutes</a>
                                    </h5>
                                    <a href="#"
                                        class="text-main-600 fw-medium tw-text-lg hover--translate-y-1">
                                        Learn More
                                        <span class="d-inline-flex tw-text-sm">
                                            <i class="ph-bold ph-arrow-up-right"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Blog items end -->
                    </div>
                </div>
            </section>
            <!-- Blog section End -->
            <!-- Cta section start -->
            <section class="cta py-md-0 py-5">
                <div class="container">
                    <div class="row gy-4 align-items-center">
                        <div class="col-md-6 d-md-block d-none">
                            <div class="pe-lg-2">
                                <div class="position-relative">
                                    <img src="{{asset('assets/marketing/images/thumbs/model-img.png')}}" alt="Model" />
                                    <img src="{{asset('assets/marketing/images/shapes/arrow-right-curve.png')}}" alt="Arrow shape"
                                        class="position-absolute top-0 tw-end-0 tw-mt-160-px animate__wobble__two" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ps-lg-4 max-w-532-px">
                                <span
                                    class="tw-py-1 tw-px-705 bg-main-600 text-white tw-text-sm fw-bold text-capitalize rounded-pill tw-mb-205">Have
                                    A Project?</span>
                                <h3 class="splitTextStyleOne fw-light tw-leading-104 tw-mb-5 d-flex gap-2">
                                    <span class="d-inline-block fw-semibold">
                                        <span class="fw-normal">Post your</span>
                                        Project &amp; get matched to the right
                                        <span class="fw-normal">
                                            expert.</span>
                                    </span>
                                </h3>
                                <p class="text-neutral-600 tw-text-lg splitTextStyleOne">
                                    Share your brief or upload a PDF. We’ll help you scope milestones and connect with verified talent.
                                </p>
                                <a href="#"
                                    class="hover--translate-y-1 active--translate-y-scale-9 btn btn-main-two hover-style-two button--stroke d-sm-inline-flex d-none align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-px-9 rounded-0 tw-py-5 w-100 tw-mt-10"
                                    data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="800"
                                    data-block="button">
                                    <span class="button__flair"></span>
                                    <span class="button__label">Get Started Today</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Cta section End -->
        </div>

        <!-- footer area -->
        @include('marketing.partials.footer')
        <!-- footer area end -->
    </div>
@endsection
