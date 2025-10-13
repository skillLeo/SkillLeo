<section class="skills-showcase">
  @php
      // Use your variables; harmonize to these names:
      $allHardSkills  = $skillsData ?? ($skills ?? []);
      $allSoftSkills  = $user->softSkills ?? ($softSkills ?? []);
      $visibleHard    = collect($allHardSkills)->take(4);
      $visibleSoft    = collect($allSoftSkills)->take(6);
      $showSeeAllSkills = (count($allHardSkills) > 4) || (count($allSoftSkills) > 6);
  @endphp

  <div class="cards-header">
    <h2 class="portfolios-title">Skills</h2>
    <button class="edit-card icon-btn" aria-label="Edit card">
      <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
    </button>
  </div>

  <div class="skills-main-content">
    {{-- LEFT: Progress bars for hard skills (max 4) --}}
    <div class="skills-progress-section">
      @forelse($visibleHard as $skill)
        <div class="skill-progress">
          <div class="skill-header">
            <span class="skill-name">{{ $skill['name'] }}</span>
            <span class="skill-percentage">{{ $skill['percentage'] }}%</span>
          </div>
          <div class="progress-bar-container" aria-label="{{ $skill['name'] }} proficiency {{ $skill['percentage'] }}%">
            <div class="progress-bar-fill" style="width: {{ $skill['percentage'] }}%">
              <div class="skill-progress-icon"><i class="fas fa-graduation-cap"></i></div>
            </div>
          </div>
        </div>
      @empty
        {{-- no skills -> render nothing --}}
      @endforelse
    </div>

    {{-- RIGHT: Soft skills (max 6) --}}
    <div class="soft-skills-section">
      <h4>Soft Skills</h4>

      @if(!empty($visibleSoft))
        <ul class="soft-skills-list">
          @foreach($visibleSoft as $s)
            <li class="soft-skill">
              <span class="soft-skill-icon">
                <i class="fa-solid fa-{{ $s['icon'] ?? 'sparkles' }}"></i>
              </span>
              <span class="soft-skill-name">{{ $s['name'] }}</span>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  @if($showSeeAllSkills)
    <x-ui.see-all text="See all Skills" onclick="showAllSkills()" />
  @endif
</section>

<script>
    // Open the "See All Skills" modal
    window.showAllSkills = function () {
        openModal('seeAllSkillsModal');
    };
</script>




<style>
  /* layout */
  .skills-showcase .skills-main-content{
    display:grid; align-items:start; align-content:start;
  }
  @media (min-width: 1200px){ .skills-showcase .skills-main-content{ grid-template-columns:57% 40%; gap:28px; } }
  @media (min-width: 992px) and (max-width: 1199px){ .skills-showcase .skills-main-content{ grid-template-columns:55% 42%; gap:22px; } }
  @media (max-width: 991px){ .skills-showcase .skills-main-content{ grid-template-columns:1fr; gap:18px; } }
  
  /* progress bars (left) – keep your existing look & feel */
    .skill-header{ display:flex; justify-content:space-between; margin:0 0 6px; font-weight:600; }
  .skill-progress{ margin:0 0 14px; }
  
  /* soft skills (right) */
  .soft-skills-section{ padding-left:24px; }
  @media (max-width: 1199px){ .soft-skills-section{ padding-left:16px; } }
  @media (max-width: 991px){ .soft-skills-section{ padding-left:0; } }
  
  .soft-skills-section h4{ margin:0 0 10px; font-weight:700; color:var(--text-heading); }
  .soft-skills-list{
    list-style:none; padding:0; margin:0;
    display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px;
  }
  @media (max-width: 480px){ .soft-skills-list{ grid-template-columns:1fr; } }
  
  .soft-skill{
    display:flex; align-items:center; gap:8px;
  }
  .soft-skill-icon{ width:22px; height:22px; display:grid; place-items:center; color:var(--accent, #3b5bff); }
  .soft-skill-name{ font-size:14px; color:var(--text-body); }

 
/* ---------- Base tidy-up (all screens) ---------- */
.skills-showcase .skills-main-content{
  display:grid;
  align-items:start;          /* prevent vertical stretching */
  align-content:start;        /* avoid space-between gaps */
}

.skills-showcase .tech-stack-section{
  margin:0;                   /* kill accidental outer gaps */
  padding:0;                  /* base = no extra vertical padding */
  display:grid;
  gap:10px;                   /* compact internal rhythm */
}

.skills-showcase .tech-category{ margin:0 0 14px; }
.skills-showcase .tech-category:last-child{ margin-bottom:0; }

.skills-showcase .tech-separator-line{
  height:1px;
  background:var(--border, #e6e8eb);
  margin:10px 0 12px;        /* tighter line spacing */
}

.skills-showcase .skills-see-more{ margin-top:12px; }

/* Ensure the last element inside tech-stack does not push extra space */
.skills-showcase .tech-stack-section > *:last-child{ margin-bottom:0 !important; }

/* ---------- Desktop & large screens ---------- */
@media (min-width: 1200px){
  .skills-showcase .skills-main-content{ grid-template-columns:57% 40%; gap:28px; }
  .skills-showcase .skills-progress-section{ padding-right:24px; }
  .skills-showcase .tech-stack-section{ padding-left:24px; padding-top:0; }
}

/* ---------- Laptop / tablet landscape ---------- */
@media (min-width: 992px) and (max-width: 1199px){
  .skills-showcase .skills-main-content{ grid-template-columns:55% 42%; gap:22px; }
  .skills-showcase .skills-progress-section{ padding-right:16px; }
  .skills-showcase .tech-stack-section{ padding-left:16px; padding-top:0; }
  .skills-showcase .tech-category{ margin:0 0 14px; }
}

/* ---------- Tablet portrait ---------- */
@media (min-width: 768px) and (max-width: 991px){
  .skills-showcase .skills-main-content{ grid-template-columns:1fr; gap:18px; }
  .skills-showcase .skills-progress-section{ padding:0; border-bottom:1px solid var(--border, #e6e8eb); }
  .skills-showcase .tech-stack-section{
    padding: 0;       /* reduce top/bottom padding */
    gap:8px;                 /* tighter stack */
  }
  .skills-showcase .tech-separator-line{ margin:10px 0 12px; }
  .skills-showcase .tech-category{ margin:0 0 12px; }
}

/* ---------- Mobile large ---------- */
@media (min-width: 481px) and (max-width: 767px){
  .skills-showcase{ padding:20px !important; margin-bottom:16px; }
  .skills-showcase .skills-main-content{ grid-template-columns:1fr; gap:16px; }

  .skills-showcase .skills-progress-section{
    padding: 0;
    border-bottom:1px solid var(--border, #e6e8eb);
  }

  .skills-showcase .tech-stack-section{
    padding: 0;       /* REMOVE extra space above/below */
    gap:8px;
  }

  .skills-showcase .tech-category{ margin:0 0 10px; }
  .skills-showcase .tech-separator-line{ margin:8px 0 10px; }
  .skills-showcase .skills-see-more{ margin-top:10px; display:block; text-align:center; }
}

/* ---------- Mobile small ---------- */
@media (max-width: 480px){
  .skills-showcase{ padding:16px !important; margin-bottom:12px; border-radius:0 !important; }
  .skills-showcase .skills-main-content{ grid-template-columns:1fr; gap:14px; }

  .skills-showcase .skills-progress-section{
    padding:0 0 10px 0;
    border-bottom:1px solid var(--border, #e6e8eb);
  }

  .skills-showcase .tech-stack-section{
    padding: 0;       /* minimal top padding */
    gap:6px;
  }

  .skills-showcase .tech-category{ margin:0 0 8px; }
  .skills-showcase .tech-separator-line{ margin:6px 0 8px; }
  .skills-showcase .skills-see-more{
    margin-top:8px; text-align:center; display:block;
    font-size:var(--fs-body); padding:10px 12px;
    background:var(--gray-100, #f5f6f7); border-radius:8px; border:1px solid var(--border, #e6e8eb);
  }
}

/* ---------- Extra small ---------- */
@media (max-width: 359px){
  .skills-showcase .skills-main-content{ gap:12px; }
  .skills-showcase .tech-stack-section{ gap:6px; }
  .skills-showcase .tech-category{ margin:0 0 6px; }
  .skills-showcase .tech-separator-line{ margin:6px 0; }
}

/* ---------- Landscape short screens ---------- */
@media (max-height: 500px) and (orientation: landscape){
  .skills-showcase .skills-main-content{ gap:12px; }
  .skills-showcase .skills-progress-section{ padding-bottom:8px; }
  .skills-showcase .tech-stack-section{ padding-top:0; }
  .skills-showcase .tech-category{ margin:0 0 8px; }
  .skills-showcase .tech-separator-line{ margin:6px 0 8px; }
}

/* ---------- High DPI tweak ---------- */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi){
  .skills-showcase .progress-bar-container{ border:0.5px solid var(--border, #e6e8eb); }
}

/* ---------- Reduced motion ---------- */
@media (prefers-reduced-motion: reduce){
  .skills-showcase .progress-bar-fill,
  .skills-showcase .skill-progress-icon{ transition:none !important; }
}

/* ---------- Print ---------- */
@media print{
  .skills-showcase{ break-inside:avoid; box-shadow:none; border:1px solid #000; }
  .skills-showcase .add-projects-btn,
  .skills-showcase .skills-see-more{ display:none !important; }
}

</style>