



@extends('marketing.layouts.app')
@section('title', 'Register')
@section('content')
    <!-- header area -->




    <style> 
      .container{background: transparent !important;}

      .cfu-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  user-select: none;
  justify-content: center;
  margin: 1vw 0;
}

.cfu-logo-img {
  height: 48px;
  width: auto;
  border-radius: 12px;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}


 

 

    </style>
    <div class="bg-main-600 tw-py-205 d-sm-block d-none">
      <div class="container">
          <div class="d-flex justify-content-center">
              <p class="text-white bg-white-13 d-inline-block tw-py-1 tw-px-5 rounded-pill fw-normal">Connect with verified developers, designers, and skilled pros — create your CV/portfolio, upload project docs, match with the right talent, and manage orders & projects with AI assistance.</p>
          </div>
      </div>
  </div>
  <!-- Top H    <!-- header area end -->

    <div id="smooth-content">
       

        <!-- Account Section start= -->
        <section class="account py-120">
            <div class="container">
                <div class="center   tw-rounded-2xl tw-p-60-px mx-auto">  <div class="auth-container">
                  <!-- Logo -->
                  <div class="cfu-logo">
                    <img src="{{asset('assets/images/logos/croped/logo_light.png')}}" alt="SkillLeo Logo" class="cfu-logo-img">
                  </div>
                  
      
                  <!-- Header -->
                  <div class="auth-header">
                      <h1>Start Your Journey with us</h1>
                      <p>Create your account to get started</p>
                  </div>
      
                  <!-- Form -->
                  <form action="{{ route('auth.register.submit') }}" method="POST" novalidate>
                    @csrf
                  
                    {{-- Honeypot (bots fill this) --}}
                    <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" />
                  
                    <div class="form-group">
                      <label for="name" class="form-label">Full name</label>
                      <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input"
                        placeholder="Jane Doe"
                        required
                        value="{{ old('name') }}"
                      >
                      @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                  
                    <div class="form-group">
                      <label for="email" class="form-label">Enter your email</label>
                      <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="you@example.com"
                        required
                        value="{{ old('email') }}"
                        autocomplete="email"
                      >
                      @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                  
                    <div class="form-group password-group">
                      <label for="password" class="form-label">Password</label>
                    
                      <div class="password-input-wrap">
                        <input
                          id="password"
                          name="password"
                          type="password"
                          required
                          class="form-input password-input"
                          placeholder="••••••••"
                          autocomplete="current-password"
                          aria-describedby="password-toggle-help"
                        >
                        <button
                          type="button"
                          class="password-toggle"
                          id="passwordToggle"
                          aria-label="Show password"
                          aria-pressed="false"
                        >
                          {{-- Eye icon (visible) + Eye-off icon (hidden until toggled) --}}
                          <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/>
                            <circle cx="12" cy="12" r="3"/>
                          </svg>
                          <svg class="icon-eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="display:none">
                            <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a20.82 20.82 0 0 1 5.06-6.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a20.82 20.82 0 0 1-3.22 4.49"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                          </svg>
                        </button>
                      </div>
                    
                      <small id="password-toggle-help" class="visually-hidden">Press the button to show or hide your password.</small>
                      @error('password')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    
                    <style>
                    .password-group { position: relative; }
                    .password-input-wrap { position: relative; }
                    .password-input { padding-right: 44px; } /* room for the icon button */
                    .password-toggle {
                      position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
                      width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
                      border: 0; background: transparent; border-radius: 8px; cursor: pointer;
                      color: var(--text-muted);
                    }
                    .password-toggle:hover { color: var(--text-heading); background: var(--card); }
                    .password-toggle:focus { outline: none; box-shadow: 0 0 0 3px var(--accent-light); border-radius: 8px; }
                    .password-toggle .icon-eye, .password-toggle .icon-eye-off { pointer-events: none; }
                    .visually-hidden { position:absolute; width:1px; height:1px; margin:-1px; padding:0; border:0; clip:rect(0 0 0 0); overflow:hidden; white-space:nowrap; }
                    </style>
                    
                    <script>
                    document.addEventListener('DOMContentLoaded', () => {
                      const input  = document.getElementById('password');
                      const toggle = document.getElementById('passwordToggle');
                      const eye    = toggle.querySelector('.icon-eye');
                      const eyeOff = toggle.querySelector('.icon-eye-off');
                    
                      toggle.addEventListener('click', () => {
                        const show = input.type === 'password';
                        input.type = show ? 'text' : 'password';
                        toggle.setAttribute('aria-pressed', String(show));
                        toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                        eye.style.display = show ? 'none' : '';
                        eyeOff.style.display = show ? '' : 'none';
                        // Keep caret at end
                        const val = input.value; input.value = ''; input.value = val; input.focus();
                      });
                    });
                    </script>
                  
              
                  
                    <div class="checkbox-group">
                      <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        value="1"
                        class="checkbox-input"
                        required
                      >
                      <label for="terms" class="checkbox-label">
                        I agree to SkillLeo <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                      </label>
                      @error('terms')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                  
                    <button type="submit" class="btn btn-primary">
                      Create account
                    </button>
                  </form>
                  
      
                  <!-- Divider -->
                  <div class="divider">Or continue with</div>
      
                  <!-- Social Login Buttons -->
                  <div class="social-buttons">
                      <!-- Google -->
                      <button type="button" class="btn-social" onclick="window.location.href='/auth/google/redirect'">
                          <svg viewBox="0 0 24 24" fill="none">
                              <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                              <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                              <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                              <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                          </svg>
                          <span>Google</span>
                      </button>
      
                      <!-- LinkedIn -->
                      <button type="button" class="btn-social" onclick="window.location.href='/auth/linkedin/redirect'">
                          <svg viewBox="0 0 24 24" fill="#0A66C2">
                              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                          </svg>
                          <span>LinkedIn</span>
                      </button>
      
                      <!-- GitHub -->
                      <button type="button" class="btn-social" onclick="window.location.href='/auth/github/redirect'">
                          <svg viewBox="0 0 24 24" fill="#181717">
                              <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                          </svg>
                          <span>GitHub</span>
                      </button>
                  </div>
      
                  <!-- Footer -->
                  <div class="auth-footer">
                      Already have an account? <a href="{{route('auth.register')}}">Log in</a>
                  </div>
              </div>               </div>
            </div>
        </section>
        <!-- Account Section End = -->

        <!-- Task Management Section Start -->
        <section class="task-management bg-pink-more-light-half drag-rotate-element-section bg-neutral-light-half">
            <div class="container" style="width: 100%;">
                <div class="text-end tw--mb-40-px position-relative z-2">
                    <img src="{{asset('assets/marketing/images/thumbs/laptop-man.png')}}" alt="Image" class="tw-me-84-px" />
                </div>

                <div class="bg-green-deep tw-rounded-3xl bg-green-deep tw-pt-100-px position-relative z-1">
                    <img src="{{asset('assets/marketing/images/shapes/hill-shape.png')}}" alt="Hill Shape"
                        class="position-absolute w-100 h-100 top-0 tw-start-0 z-n1" />
                    <img src="{{asset('assets/marketing/images/thumbs/task-management-img.png')}}" alt="Image"
                        class="position-absolute tw-end-0 top-0 tw-me-5 tw-mt-5 d-lg-block d-none" />

                    <div class="tw-mb-8 text-center max-w-570-px mx-auto">
                        <div class="tw-py-3 tw-px-305 rounded-pill fw-medium text-capitalize tw-leading-none d-inline-flex align-items-center tw-gap-2 tw-mb-405 min-w-max text-white bg-white-13"
                            data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="600">
                            <div class="">
                                Up to
                                <span class="text-yellow text-stroke-yellow">70%</span>
                                off managed cloud hosting
                            </div>
                        </div>
                        <h3 class="splitTextStyleOne text-white">
                            Ready to revolutionize our service?
                        </h3>

                        <div class="d-none">
                            <a href="javascript:void(0)"
                                class="hover--translate-y-1 active--translate-y-scale-9 btn btn-main hover-style-one button--stroke d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-px-13 rounded-0 tw-py-6 fw-bold tw-mt-7"
                                data-block="button">
                                <span class="button__flair"></span>
                                <div class="d-flex align-items-center tw-gap-2 z-1">
                                    <span class="button__label">Download for free</span>
                                </div>
                            </a>
                        </div>
                        <div class="d-block">
                            <div class="d-flex align-items-center tw-gap-4 justify-content-center flex-wrap">
                                <a href="javascript:void(0)"
                                    class="hover--translate-y-1 active--translate-y-scale-9 btn btn-main hover-style-one button--stroke d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-px-705 tw-rounded-2xl tw-py-6 fw-bold tw-mt-7"
                                    data-block="button">
                                    <span class="button__flair"></span>
                                    <div class="d-flex align-items-center tw-gap-2 z-1">
                                        <span class="button__label">Get Started Trial</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="hover--translate-y-1 active--translate-y-scale-9 btn hover-style-two button--stroke d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-px-705 tw-rounded-2xl tw-py-6 fw-bold tw-mt-7"
                                    data-block="button">
                                    <span class="button__flair"></span>
                                    <div class="d-flex align-items-center tw-gap-2 z-1">
                                        <span class="button__label">Get Started Trial</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tw-pt-8 text-center">
                        <div
                            class="myContainer position-relative d-flex flex-wrap align-items-center justify-content-center tw-gap-6 tw-pt-16 overflow-hidden w-100 tw-px-6">
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-white gradient-bg-six rounded-pill">Project
                                management</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading bg-paste rounded-pill">Technology</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading gradient-bg-six rounded-pill">Technology</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading bg-yellow rounded-pill">Project
                                management</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading bg-orange rounded-pill">Technology</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading gradient-bg-six rounded-pill">Technology</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading bg-orange rounded-pill">Project
                                management</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading gradient-bg-six rounded-pill">Technology</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading bg-paste rounded-pill">Project
                                management</span>
                            <span
                                class="drag-rotate-element cursor-grab min-w-max z-1 tw-px-9 tw-py-2 fw-semibold text-heading bg-pink rounded-pill">Technology</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Task Management Section End -->

        <!-- footer area -->
        <!-- footer area end -->
    </div>
</div>

@endsection

































