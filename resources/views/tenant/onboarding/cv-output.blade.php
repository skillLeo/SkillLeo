{{-- resources/views/tenant/onboarding/cv-output.blade.php --}}
@extends('layouts.onboarding')

@section('title', 'Review Your Profile')

@section('card-content')
<div id="cv-output-root">
  <!-- Content will be rendered by JavaScript -->
</div>

{{-- Pass normalized server-side data to JS (Blade will output JSON) --}}
<script>
  // Server-provided normalized $cv array
  // Blade will safely JSON-encode the PHP $cv variable here.
  const SERVER_CV = @json($cv ?? []);
</script>

<script>
(function () {
  // Utility helpers
  const isObject = v => v && typeof v === 'object' && !Array.isArray(v);
  const toArray = v => Array.isArray(v) ? v : (v === undefined || v === null ? [] : [v]);
  const safeText = (v) => v === null || v === undefined ? '' : String(v);

  // Normalizer for items that might have different key names (case-insensitive)
  function keyMap(item) {
    if (!isObject(item)) return item;
    const map = {};
    Object.keys(item).forEach(k => map[k.toLowerCase()] = item[k]);
    return map;
  }

  function normalizeExperience(item) {
    const m = keyMap(item);
    const title = m.title || m.job_title || m.position || m.role || m.name || null;
    const company = m.company || m.employer || null;
    const duration = m.duration || m.period || m.year || null;
    let description = m.description || m.details || m.responsibilities || m.bullets || null;

    if (typeof description === 'string' && description.indexOf('\n') !== -1) {
      description = description.split(/\r?\n/).map(s => s.trim()).filter(Boolean);
    }

    if (Array.isArray(description)) {
      description = description.map(d => typeof d === 'string' ? d : JSON.stringify(d));
    } else if (description !== null && !Array.isArray(description)) {
      description = String(description);
    }

    return {
      Title: title ? String(title).trim() : null,
      Company: company ? String(company).trim() : null,
      Duration: duration ? String(duration).trim() : null,
      Description: description,
    };
  }

  function normalizeEducation(item) {
    const m = keyMap(item);
    const degree = m.degree || m.title || m.qualification || null;
    const institution = m.institution || m.school || m.college || null;
    const year = m.year || m.duration || m.period || null;
    return {
      Degree: degree ? String(degree).trim() : null,
      Institution: institution ? String(institution).trim() : null,
      Year: year ? String(year).trim() : null,
    };
  }

  function normalizeProject(item) {
    const m = keyMap(item);
    const name = m.name || m.title || null;
    const description = m.description || m.summary || null;
    let technologies = m.technologies || m.tech || m.tools || null;
    if (typeof technologies === 'string') {
      technologies = technologies.split(/[,;|]/).map(s => s.trim()).filter(Boolean);
    }
    if (!Array.isArray(technologies)) {
      technologies = technologies ? [String(technologies)] : [];
    }
    return {
      Name: name ? String(name).trim() : null,
      Description: description ? (typeof description === 'string' ? String(description).trim() : JSON.stringify(description)) : null,
      Technologies: technologies.map(t => String(t)),
    };
  }

  // Top-level normalize for server data (in case some keys are missing or differently cased)
  function normalizeTop(cvRaw) {
    const top = {};
    // Create lowercase mapping from incoming object so we can find alternate key names
    if (isObject(cvRaw)) {
      Object.keys(cvRaw).forEach(k => top[k.toLowerCase()] = cvRaw[k]);
    }

    const name = top.name || top.fullname || top['full_name'] || top.person || top.candidate || cvRaw.Name || null;
    const about = top.about || top.summary || top.profile || top.about || cvRaw.About || null;
    const email = top.email || top.mail || cvRaw.Email || null;
    const phone = top.phone || top.contact || cvRaw.Phone || null;

    // skills
    let skills = top.skills || top.skillset || top.technologies || cvRaw.Skills || [];
    if (!Array.isArray(skills)) {
      if (typeof skills === 'string') {
        skills = skills.split(/[,;|]/).map(s => s.trim()).filter(Boolean);
      } else {
        skills = [skills];
      }
    }
    skills = skills.map(s => isObject(s) ? (s.name || s.skill || JSON.stringify(s)) : String(s));

    // experience
    let experienceRaw = top.experience || top['work_experience'] || top.work || cvRaw.Experience || [];
    if (isObject(experienceRaw) && !Array.isArray(experienceRaw)) {
      // if AI returned object with numeric keys, convert to array
      experienceRaw = Object.values(experienceRaw);
    }
    experienceRaw = toArray(experienceRaw);
    const experience = experienceRaw.map(item => normalizeExperience(item)).filter(i => i.Title || i.Company || i.Description);

    // education
    let educationRaw = top.education || top.qualifications || cvRaw.Education || [];
    if (isObject(educationRaw) && !Array.isArray(educationRaw)) educationRaw = Object.values(educationRaw);
    educationRaw = toArray(educationRaw);
    const education = educationRaw.map(item => normalizeEducation(item)).filter(e => e.Degree || e.Institution);

    // projects
    let projectsRaw = top.projects || cvRaw.Projects || [];
    if (isObject(projectsRaw) && !Array.isArray(projectsRaw)) projectsRaw = Object.values(projectsRaw);
    projectsRaw = toArray(projectsRaw);
    const projects = projectsRaw.map(item => normalizeProject(item)).filter(p => p.Name || p.Description);

    // languages
    let languages = top.languages || top.language || cvRaw.Languages || [];
    if (!Array.isArray(languages)) {
      if (typeof languages === 'string') languages = languages.split(/[,;|]/).map(s => s.trim()).filter(Boolean);
      else languages = [languages];
    }
    languages = languages.map(l => isObject(l) ? (l.name || l.language || JSON.stringify(l)) : String(l));

    return {
      Name: name ? String(name) : (cvRaw.Name || null),
      About: about ? String(about) : (cvRaw.About || null),
      Email: email || cvRaw.Email || null,
      Phone: phone || cvRaw.Phone || null,
      Skills: skills,
      Experience: experience,
      Education: education,
      Projects: projects,
      Languages: languages,
      raw: cvRaw // keep raw for debugging if needed
    };
  }

  // Render helpers: create elements safely
  function e(tag, attrs = {}, children = []) {
    const el = document.createElement(tag);
    Object.keys(attrs).forEach(k => {
      if (k === 'class') el.className = attrs[k];
      else if (k === 'text') el.textContent = attrs[k];
      else if (k === 'html') el.innerHTML = attrs[k];
      else el.setAttribute(k, attrs[k]);
    });
    children.forEach(c => {
      if (c == null) return;
      if (typeof c === 'string') el.appendChild(document.createTextNode(c));
      else el.appendChild(c);
    });
    return el;
  }

  // Main render function
  function render(cv) {
    const root = document.getElementById('cv-output-root');
    root.innerHTML = ''; // clear

    // Header
    const header = e('div', { class: 'output-header' }, [
      e('div', { class: 'success-icon' }, [
        e('svg', { width: 40, height: 40, viewBox: '0 0 24 24', 'aria-hidden': 'true' }, [])
      ]),
      e('div', {}, [
        e('h1', { text: 'Profile extracted successfully!' }),
        e('p', { text: 'Review and edit your information below before continuing' })
      ])
    ]);
    root.appendChild(header);

    // Profile overview card
    const profileCard = e('div', { class: 'profile-card' });
    const avatarLetter = (cv.Name && cv.Name.length) ? cv.Name.charAt(0).toUpperCase() : 'U';
    const avatar = e('div', { class: 'profile-avatar', text: avatarLetter });
    const profileInfo = e('div', { class: 'profile-info' }, [
      e('h2', { text: cv.Name || 'Not provided' })
    ]);
    if (cv.Email) profileInfo.appendChild(e('p', { class: 'profile-meta', text: cv.Email }));
    if (cv.Phone) profileInfo.appendChild(e('p', { class: 'profile-meta', text: cv.Phone }));

    const profileHeader = e('div', { class: 'profile-header' }, [avatar, profileInfo]);
    profileCard.appendChild(profileHeader);

    if (cv.About) {
      profileCard.appendChild(e('div', { class: 'profile-about' }, [
        e('h3', { text: 'About' }),
        e('p', { text: cv.About })
      ]));
    }
    root.appendChild(profileCard);

    // Sections container
    const sections = e('div', { id: 'sections-grid' });
    root.appendChild(sections);

    // Skills
    if (cv.Skills && cv.Skills.length) {
      const card = e('section', { class: 'section-card' });
      card.appendChild(e('div', { class: 'section-header' }, [
        e('h3', { text: 'Skills' }),
        e('span', { class: 'count', text: String(cv.Skills.length) })
      ]));
      const skillsList = e('div', { class: 'skills-list' });
      cv.Skills.forEach(s => skillsList.appendChild(e('span', { class: 'skill-tag', text: s })));
      card.appendChild(skillsList);
      sections.appendChild(card);
    }

    // Experience (timeline)
    if (cv.Experience && cv.Experience.length) {
      const card = e('section', { class: 'section-card full-width' });
      card.appendChild(e('div', { class: 'section-header' }, [
        e('h3', { text: 'Work Experience' }),
        e('span', { class: 'count', text: String(cv.Experience.length) })
      ]));

      const timeline = e('div', { class: 'timeline' });
      cv.Experience.forEach(exp => {
        const item = e('div', { class: 'timeline-item' });
        item.appendChild(e('div', { class: 'timeline-marker' }));
        const content = e('div', { class: 'timeline-content' });
        content.appendChild(e('h4', { text: exp.Title || 'Position' }));
        if (exp.Company) content.appendChild(e('p', { class: 'timeline-company', text: exp.Company }));
        if (exp.Duration) content.appendChild(e('p', { class: 'timeline-date', text: exp.Duration }));
        if (exp.Description) {
          if (Array.isArray(exp.Description)) {
            const ul = e('ul');
            exp.Description.forEach(b => ul.appendChild(e('li', { text: b })));
            content.appendChild(ul);
          } else {
            content.appendChild(e('p', { class: 'timeline-desc', text: exp.Description }));
          }
        }
        item.appendChild(content);
        timeline.appendChild(item);
      });

      card.appendChild(timeline);
      sections.appendChild(card);
    }

    // Education
    if (cv.Education && cv.Education.length) {
      const card = e('section', { class: 'section-card full-width' });
      card.appendChild(e('div', { class: 'section-header' }, [
        e('h3', { text: 'Education' }),
        e('span', { class: 'count', text: String(cv.Education.length) })
      ]));
      const list = e('div', { class: 'education-list' });
      cv.Education.forEach(ed => {
        const edItem = e('div', { class: 'education-item' });
        edItem.appendChild(e('h4', { text: ed.Degree || 'Degree' }));
        if (ed.Institution) edItem.appendChild(e('p', { class: 'edu-institution', text: ed.Institution }));
        if (ed.Year) edItem.appendChild(e('p', { class: 'edu-date', text: ed.Year }));
        list.appendChild(edItem);
      });
      card.appendChild(list);
      sections.appendChild(card);
    }

    // Projects
    if (cv.Projects && cv.Projects.length) {
      const card = e('section', { class: 'section-card full-width' });
      card.appendChild(e('div', { class: 'section-header' }, [
        e('h3', { text: 'Projects' }),
        e('span', { class: 'count', text: String(cv.Projects.length) })
      ]));
      const grid = e('div', { class: 'projects-grid' });
      cv.Projects.forEach(p => {
        const pc = e('div', { class: 'project-card' });
        pc.appendChild(e('h4', { text: p.Name || 'Project' }));
        if (p.Description) pc.appendChild(e('p', { text: p.Description }));
        if (p.Technologies && p.Technologies.length) {
          const techWrap = e('div', { class: 'project-tech' });
          p.Technologies.forEach(t => techWrap.appendChild(e('span', { class: 'tech-badge', text: t })));
          pc.appendChild(techWrap);
        }
        grid.appendChild(pc);
      });
      card.appendChild(grid);
      sections.appendChild(card);
    }

    // Languages
    if (cv.Languages && cv.Languages.length) {
      const card = e('section', { class: 'section-card' });
      card.appendChild(e('div', { class: 'section-header' }, [
        e('h3', { text: 'Languages' }),
        e('span', { class: 'count', text: String(cv.Languages.length) })
      ]));
      const languagesList = e('div', { class: 'languages-list' });
      cv.Languages.forEach(lang => {
        const item = e('div', { class: 'language-item' });
        if (isObject(lang)) {
          const km = keyMap(lang);
          const name = km.language || km.name || km.lang || 'Language';
          const level = km.proficiency || km.level || km.level || '';
          item.appendChild(e('span', { class: 'lang-name', text: String(name) }));
          if (level) item.appendChild(e('span', { class: 'lang-level', text: String(level) }));
        } else {
          item.appendChild(e('span', { class: 'lang-name', text: String(lang) }));
        }
        languagesList.appendChild(item);
      });
      card.appendChild(languagesList);
      sections.appendChild(card);
    }

    // Action buttons block
    const actions = e('div', { class: 'output-actions' }, [
      e('button', { type: 'button', class: 'btn btn-secondary', id: 'btn-upload-different' , text: 'Upload Different CV' }),
      e('button', { type: 'button', class: 'btn btn-primary', id: 'btn-continue', text: 'Continue to Dashboard' })
    ]);
    root.appendChild(actions);

    // Hook buttons
    document.getElementById('btn-upload-different').addEventListener('click', () => {
      // navigate to welcome/upload page
      window.location.href = '{{ route("tenant.onboarding.welcome") }}';
    });
    document.getElementById('btn-continue').addEventListener('click', () => {
      // submit final confirmation form to server (POST) - create a small form and submit
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("tenant.onboarding.confirm") }}';
      // insert CSRF token input if available in page as meta or Laravel's blade
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || null;
      if (token) {
        const inpt = document.createElement('input');
        inpt.type = 'hidden';
        inpt.name = '_token';
        inpt.value = token;
        form.appendChild(inpt);
      }
      document.body.appendChild(form);
      form.submit();
    });
  } // end render

  // Use server CV if provided; otherwise normalize SERVER_CV and render
  const normalized = normalizeTop(SERVER_CV || {});
  render(normalized);
})();
</script>
@endsection


@push('styles')
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --success: #10b981;
            --success-light: #d1fae5;
            --text-dark: #1a1a1a;
            --text-body: #333;
            --text-muted: #666;
            --border: #e5e7eb;
            --card-bg: #fff;
            --bg-subtle: #f9fafb;
        }

        .output-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Success Header */
        .success-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px;
            background: linear-gradient(135deg, var(--success-light) 0%, #f0fdf4 100%);
            border-radius: 12px;
            margin-bottom: 32px;
            border: 1px solid #bbf7d0;
        }

        .success-icon {
            width: 56px;
            height: 56px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--success);
            flex-shrink: 0;
        }

        .success-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 4px;
        }

        .success-header p {
            font-size: 15px;
            color: var(--text-muted);
            margin: 0;
        }

        /* Profile Card */
        .profile-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 32px;
            margin-bottom: 24px;
        }

        .profile-header {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-info h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 8px;
        }

        .profile-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-muted);
            margin: 4px 0;
        }

        .profile-meta svg {
            color: var(--text-muted);
        }

        .profile-about {
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .profile-about h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 12px;
        }

        .profile-about p {
            font-size: 15px;
            line-height: 1.6;
            color: var(--text-body);
            margin: 0;
        }

        /* Sections Grid */
        .sections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .section-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .section-card.full-width {
            grid-column: 1 / -1;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .section-header svg {
            color: var(--primary);
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
            flex: 1;
        }

        .count {
            background: var(--bg-subtle);
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* Skills */
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-tag {
            background: var(--bg-subtle);
            color: var(--text-body);
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid var(--border);
        }

        /* Timeline (Experience) */
        .timeline {
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 12px;
            bottom: 12px;
            width: 2px;
            background: var(--border);
        }

        .timeline-item {
            position: relative;
            padding-left: 36px;
            padding-bottom: 24px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 4px;
            width: 18px;
            height: 18px;
            background: var(--primary);
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 0 0 1px var(--border);
        }

        .timeline-content h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 4px;
        }

        .timeline-company {
            font-size: 15px;
            color: var(--text-body);
            font-weight: 500;
            margin: 0 0 4px;
        }

        .timeline-date {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0 0 12px;
        }

        .timeline-desc {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-body);
            margin: 0 0 8px;
        }

        .timeline-list {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .timeline-list li {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-body);
            margin-bottom: 4px;
        }

        /* Education */
        .education-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .education-item h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 4px;
        }

        .edu-institution {
            font-size: 15px;
            color: var(--text-body);
            margin: 0 0 4px;
        }

        .edu-date,
        .edu-gpa {
            font-size: 13px;
            color: var(--text-muted);
            margin: 2px 0;
        }

        /* Projects */
        .projects-grid {
            display: grid;
            gap: 16px;
        }

        .project-card {
            padding: 16px;
            background: var(--bg-subtle);
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .project-card h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 8px;
        }

        .project-card p {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-body);
            margin: 0 0 12px;
        }

        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tech-badge {
            background: white;
            border: 1px solid var(--border);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: var(--text-body);
        }

        /* Languages */
        .languages-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .language-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: var(--bg-subtle);
            border-radius: 8px;
        }

        .lang-name {
            font-size: 15px;
            font-weight: 500;
            color: var(--text-body);
        }

        .lang-level {
            font-size: 13px;
            color: var(--text-muted);
            background: white;
            padding: 4px 12px;
            border-radius: 4px;
            border: 1px solid var(--border);
        }

        /* Action Buttons */
        .output-actions {
            display: flex;
            gap: 16px;
            justify-content: space-between;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 14px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: white;
            color: var(--text-body);
            border: 1.5px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--bg-subtle);
            border-color: var(--primary);
        }

        @media (max-width: 768px) {
            .output-wrapper {
                padding: 16px;
            }

            .sections-grid {
                grid-template-columns: 1fr;
            }

            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .output-actions {
                flex-direction: column;
            }

            .success-header {
                flex-direction: column;
                text-align: center;
            }
        }





        .form-card {
            max-width: 1200px;
        }
    </style>
@endpush
