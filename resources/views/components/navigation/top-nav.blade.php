<style>
    /* ===== BRAND LOGO ===== */
    .brand-logo-light,
    .brand-logo-dark {
      height: 40px;
      width: auto;
      max-width: 180px;
      object-fit: contain;
      display: block;
      transition: opacity 0.2s ease;
    }

    .brand:hover .brand-logo-light,
    .brand:hover .brand-logo-dark {
      opacity: 0.85;
    }
    
    .brand-logo-dark {
      display: none;
    }
    
    [data-theme="dark"] .brand-logo-light {
      display: none;
    }
    
    [data-theme="dark"] .brand-logo-dark {
      display: block;
    }
    
    @media (max-width: 768px) {
      .brand-logo-light,
      .brand-logo-dark {
        height: 35px;
        max-width: 140px;
      }
    }
    
    .brand-mark,
    .brand-text {
      display: none;
    }
    
    /* ===== NAVIGATION BASE ===== */
    .top-nav {
      background: var(--nav-bg);
      backdrop-filter: blur(12px) saturate(180%) brightness(105%);
      -webkit-backdrop-filter: blur(16px) saturate(180%) brightness(105%);
      border-bottom: 1px solid var(--nav-border);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 12px rgba(0, 0, 0, 0.03);
      z-index: 1000;
      transition: all 0.3s ease;
    }
    
    /* ===== DARK MODE NAVIGATION ===== */
    [data-theme="dark"] .top-nav {
      background: var(--nav-bg-gradient);
      backdrop-filter: blur(20px) saturate(160%);
      -webkit-backdrop-filter: blur(20px) saturate(160%);
      border-bottom: 1px solid var(--nav-border-dark);
      box-shadow: var(--nav-shadow-dark);
    }
    
    /* ===== SEARCH BAR ===== */
    .search-wrap {
      transition: all 0.25s ease;
    }

    .search-wrap:focus-within {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(19, 81, 216, 0.1), 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    [data-theme="dark"] .search-wrap {
      background: var(--search-bg-dark);
      border: 1px solid var(--search-border-dark);
      box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    [data-theme="dark"] .search-wrap:focus-within {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(74, 143, 255, 0.15), inset 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    
    [data-theme="dark"] .search-wrap i {
      color: var(--search-icon-dark);
      transition: color 0.2s ease;
    }

    [data-theme="dark"] .search-wrap:focus-within i {
      color: var(--accent);
    }
    
    [data-theme="dark"] .search-input {
      color: var(--search-text-dark);
    }
    
    [data-theme="dark"] .search-input::placeholder {
      color: var(--search-placeholder-dark);
    }
    
    /* ===== ICON BUTTONS - REFINED ===== */
    .icon-btn {
      position: relative;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .icon-btn i {
      transition: transform 0.2s ease;
    }

    .icon-btn:hover i {
      transform: scale(1.1);
    }

    .icon-btn:active {
      transform: scale(0.95);
    }
    
    [data-theme="dark"] .icon-btn {
      background: var(--icon-btn-bg-dark);
      color: var(--icon-color-dark);
      border: 1px solid var(--nav-border-dark);
    }
    
    [data-theme="dark"] .icon-btn:hover {
      background: var(--icon-btn-hover-dark);
      border-color: var(--accent);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(74, 143, 255, 0.2);
    }

    [data-theme="dark"] .icon-btn:active {
      transform: translateY(0);
      box-shadow: 0 2px 6px rgba(74, 143, 255, 0.15);
    }
    
    /* ===== SHARE BUTTON - PREMIUM ===== */
    .share-btn {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .share-btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      transform: translate(-50%, -50%);
      transition: width 0.4s ease, height 0.4s ease;
    }

    .share-btn:hover::before {
      width: 300px;
      height: 300px;
    }

    .share-btn i,
    .share-btn span {
      position: relative;
      z-index: 1;
    }

    .share-btn:active {
      transform: scale(0.96);
    }
    
    [data-theme="dark"] .share-btn {
      background: var(--share-btn-bg-dark);
      border: 1px solid rgba(74, 143, 255, 0.3);
      box-shadow: 0 4px 12px rgba(74, 143, 255, 0.2);
    }
    
    [data-theme="dark"] .share-btn:hover {
      background: var(--share-btn-hover-dark);
      box-shadow: 0 6px 20px rgba(74, 143, 255, 0.35);
      transform: translateY(-2px);
    }

    [data-theme="dark"] .share-btn:active {
      transform: translateY(0) scale(0.96);
      box-shadow: 0 3px 10px rgba(74, 143, 255, 0.25);
    }
    
    /* ===== BADGES - POLISHED ===== */
    .badge-count {
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .badge-dot {
      animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {
      0%, 100% { 
        transform: scale(1); 
        box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.7);
      }
      50% { 
        transform: scale(1.1); 
        box-shadow: 0 0 0 4px rgba(255, 59, 48, 0);
      }
    }
    
    [data-theme="dark"] .badge-dot,
    [data-theme="dark"] .badge-count {
      background: var(--badge-bg-dark);
      border-color: var(--badge-border-dark);
      box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }
    
    /* ===== AVATAR - REFINED ===== */
    .nav-avatar {
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .nav-avatar:hover {
      transform: scale(1.08);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    [data-theme="dark"] .nav-avatar {
      border: 2px solid var(--nav-border-dark);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    [data-theme="dark"] .nav-avatar:hover {
      border-color: var(--accent);
      box-shadow: 0 4px 20px rgba(74, 143, 255, 0.3);
    }

    /* ===== LIGHT MODE ENHANCEMENTS ===== */
    .search-wrap i {
      transition: color 0.2s ease;
    }

    .search-wrap:focus-within i {
      color: var(--accent);
    }

    /* ===== ACCESSIBILITY ===== */
    .icon-btn:focus-visible,
    .share-btn:focus-visible,
    .search-input:focus-visible {
      outline: none !important;
    }

    /* ===== RESPONSIVE POLISH ===== */
    @media (max-width: 768px) {
      .share-btn span {
        display: inline;
      }
      
      .icon-btn {
        width: 40px;
        height: 40px;
      }
    }

    @media (min-width: 769px) {
      .nav-inner {
        padding: 10px 24px;
      }
    }
</style>

<style>
    /* ... your existing nav styles ... */

    /* ===== AI SEARCH SUGGESTIONS ===== */
    .search-suggestions {
      position: absolute;
      top: calc(100% + 8px);
      left: 0;
      right: 0;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
      max-height: 400px;
      overflow-y: auto;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 9999;
    }

    .search-suggestions.active {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    [data-theme="dark"] .search-suggestions {
      background: rgba(27, 31, 35, 0.98);
      border-color: var(--nav-border-dark);
      box-shadow: 0 12px 48px rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(20px);
    }

    /* Search Header */
    .search-header {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    [data-theme="dark"] .search-header {
      border-color: var(--nav-border-dark);
    }

    .search-header i {
      font-size: 16px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: sparkle 2s ease-in-out infinite;
    }

    @keyframes sparkle {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.6; }
    }

    .search-header-text {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-muted);
      letter-spacing: 0.3px;
    }

    [data-theme="dark"] .search-header-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Suggestion Items */
    .suggestion-item {
      padding: 14px 20px;
      cursor: pointer;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .suggestion-item:hover {
      background: rgba(74, 143, 255, 0.08);
      border-left-color: var(--accent);
    }

    [data-theme="dark"] .suggestion-item:hover {
      background: rgba(74, 143, 255, 0.15);
    }

    .suggestion-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 16px;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
      color: var(--accent);
      transition: all 0.2s ease;
    }

    .suggestion-item:hover .suggestion-icon {
      transform: scale(1.1) rotate(5deg);
      background: linear-gradient(135deg, var(--accent) 0%, #764ba2 100%);
      color: white;
    }

    .suggestion-content {
      flex: 1;
      min-width: 0;
    }

    .suggestion-title {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-heading);
      margin-bottom: 2px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    [data-theme="dark"] .suggestion-title {
      color: var(--text-heading);
    }

    .suggestion-match {
      color: var(--accent);
      font-weight: 700;
    }

    .suggestion-desc {
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.4;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .suggestion-badge {
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge-trending {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
    }

    .badge-ai {
      background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
      color: white;
    }

    .badge-popular {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }

    /* No Results */
    .no-results {
      padding: 40px 20px;
      text-align: center;
    }

    .no-results i {
      font-size: 48px;
      color: var(--text-disabled);
      margin-bottom: 12px;
      opacity: 0.5;
    }

    .no-results-text {
      font-size: 14px;
      color: var(--text-muted);
    }

    /* Loading State */
    .search-loading {
      padding: 24px 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      color: var(--text-muted);
      font-size: 14px;
    }

    .loading-spinner {
      width: 20px;
      height: 20px;
      border: 3px solid var(--border);
      border-top-color: var(--accent);
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Scrollbar */
    .search-suggestions::-webkit-scrollbar {
      width: 6px;
    }

    .search-suggestions::-webkit-scrollbar-track {
      background: transparent;
    }

    .search-suggestions::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 3px;
    }

    [data-theme="dark"] .search-suggestions::-webkit-scrollbar-thumb {
      background: var(--nav-border-dark);
    }

    /* Wrapper for positioning */
    .search-wrap {
      position: relative;
    }
</style>

<nav class="top-nav">
    <div class="nav-inner">
        <!-- Mobile View -->
        <div class="nav-row nav-row--top">
            <img class="nav-avatar" src="{{ $user->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" alt="Profile">
            <a class="brand" href="#" aria-label="{{ $brandName ?? 'Portfolio' }}">
                <img class="brand-logo brand-logo-light" src="{{ asset('assets/images/logos/croped_720x200/logo_light.png') }}" alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
                <img class="brand-logo brand-logo-dark" src="{{ asset('assets/images/logos/croped_720x200/logo_dark.png') }}" alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
            </a>
            <button class="share-btn">
                <i class="fa-solid fa-share-nodes"></i><span>Share</span>
            </button>
        </div>
        
        <div class="nav-row nav-row--bottom">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Search" id="search-mobile">
                
                <!-- Suggestions Dropdown -->
                <div class="search-suggestions" id="suggestions-mobile">
                    <div class="search-header">
                        <i class="fa-solid fa-sparkles"></i>
                        <span class="search-header-text">AI-POWERED SUGGESTIONS</span>
                    </div>
                    <div id="suggestions-list-mobile"></div>
                </div>
            </div>
            <a class="icon-btn" href="#" aria-label="Messages">
                <i class="fa-regular fa-message"></i>
                <span class="badge badge-count">{{ $messageCount ?? 0 }}</span>
            </a>
            <a class="icon-btn" href="#" aria-label="Notifications">
                <i class="fa-regular fa-bell"></i>
                <span class="badge badge-dot"></span>
            </a>
        </div>

        <!-- Desktop View -->
        <div class="nav-row nav-row--desktop">
            <a class="brand" href="#" aria-label="{{ $brandName ?? 'Portfolio' }}">
                <img class="brand-logo brand-logo-light" src="{{ asset('assets/images/logos/croped_720x200/logo_light.png') }}" alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
                <img class="brand-logo brand-logo-dark" src="{{ asset('assets/images/logos/croped_720x200/logo_dark.png') }}" alt="{{ $brandName ?? 'Portfolio' }}" width="720" height="200">
            </a>
            
            <div class="search-wrap search-wrap--desktop">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Graphic Designer, Web Developer etc." id="search-desktop">
                
                <!-- Suggestions Dropdown -->
                <div class="search-suggestions" id="suggestions-desktop">
                    <div class="search-header">
                        <i class="fa-solid fa-sparkles"></i>
                        <span class="search-header-text">AI-POWERED SUGGESTIONS</span>
                    </div>
                    <div id="suggestions-list-desktop"></div>
                </div>
            </div>
            
            <div class="actions">
                <a class="icon-btn" href="#" aria-label="Messages">
                    <i class="fa-regular fa-message"></i>
                    <span class="badge badge-count">{{ $messageCount ?? 0 }}</span>
                </a>
                <a class="icon-btn" href="#" aria-label="Notifications">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge badge-dot"></span>
                </a>
                <button class="share-btn">
                    <i class="fa-solid fa-share-nodes"></i><span>Share</span>
                </button>
                <img class="nav-avatar" src="{{ $user->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" alt="Profile">
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Suggestion database with icons and descriptions
    const suggestionData = [
        { title: 'Web Development', icon: 'fa-code', desc: 'Full-stack, Frontend & Backend developers', badge: 'trending', category: 'development' },
        { title: 'UI/UX Design', icon: 'fa-palette', desc: 'User interface and experience designers', badge: 'popular', category: 'design' },
        { title: 'Mobile App Development', icon: 'fa-mobile-screen', desc: 'iOS, Android & Cross-platform apps', badge: 'ai', category: 'development' },
        { title: 'Graphic Design', icon: 'fa-pen-nib', desc: 'Branding, logos & visual identity', badge: 'popular', category: 'design' },
        { title: 'SaaS Projects', icon: 'fa-cloud', desc: 'Software as a Service solutions', badge: 'trending', category: 'business' },
        { title: 'E-commerce', icon: 'fa-cart-shopping', desc: 'Online stores & marketplace platforms', badge: 'popular', category: 'business' },
        { title: 'Real Estate', icon: 'fa-building', desc: 'Property management & listings', badge: 'trending', category: 'business' },
        { title: 'Digital Marketing', icon: 'fa-bullhorn', desc: 'SEO, Social Media & Content marketing', badge: 'popular', category: 'marketing' },
        { title: 'Content Writing', icon: 'fa-pen-fancy', desc: 'Blog posts, articles & copywriting', badge: 'ai', category: 'content' },
        { title: 'Video Production', icon: 'fa-video', desc: 'Editing, animation & motion graphics', badge: 'trending', category: 'media' },
        { title: 'Photography', icon: 'fa-camera', desc: 'Product, portrait & commercial photography', badge: 'popular', category: 'media' },
        { title: 'AI & Machine Learning', icon: 'fa-brain', desc: 'Artificial intelligence solutions', badge: 'ai', category: 'tech' },
        { title: 'Blockchain Development', icon: 'fa-coins', desc: 'Web3, NFT & cryptocurrency projects', badge: 'trending', category: 'tech' },
        { title: 'Data Analysis', icon: 'fa-chart-line', desc: 'Business intelligence & insights', badge: 'ai', category: 'data' },
        { title: 'Game Development', icon: 'fa-gamepad', desc: '2D, 3D & mobile gaming', badge: 'popular', category: 'development' },
    ];

    // Initialize search for both mobile and desktop
    initializeSearch('search-mobile', 'suggestions-mobile', 'suggestions-list-mobile');
    initializeSearch('search-desktop', 'suggestions-desktop', 'suggestions-list-desktop');

    function initializeSearch(inputId, dropdownId, listId) {
        const searchInput = document.getElementById(inputId);
        const suggestionsDropdown = document.getElementById(dropdownId);
        const suggestionsList = document.getElementById(listId);
        let debounceTimer;

        if (!searchInput) return;

        // Show suggestions on focus
        searchInput.addEventListener('focus', function() {
            if (this.value.trim()) {
                performSearch(this.value);
            } else {
                showTrendingSuggestions();
            }
        });

        // Search on input with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (!query) {
                showTrendingSuggestions();
                return;
            }

            // Show loading state
            suggestionsList.innerHTML = '<div class="search-loading"><div class="loading-spinner"></div><span>Searching...</span></div>';
            suggestionsDropdown.classList.add('active');

            debounceTimer = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-wrap')) {
                suggestionsDropdown.classList.remove('active');
            }
        });

        function performSearch(query) {
            const lowerQuery = query.toLowerCase();
            const results = suggestionData.filter(item => 
                item.title.toLowerCase().includes(lowerQuery) ||
                item.desc.toLowerCase().includes(lowerQuery) ||
                item.category.toLowerCase().includes(lowerQuery)
            );

            displayResults(results, query);
        }

        function showTrendingSuggestions() {
            const trending = suggestionData.filter(item => item.badge === 'trending').slice(0, 5);
            displayResults(trending, '');
        }

        function displayResults(results, query) {
            if (results.length === 0) {
                suggestionsList.innerHTML = `
                    <div class="no-results">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <div class="no-results-text">No results found for "${query}"</div>
                    </div>
                `;
            } else {
                suggestionsList.innerHTML = results.map(item => {
                    const highlightedTitle = query 
                        ? item.title.replace(new RegExp(query, 'gi'), match => `<span class="suggestion-match">${match}</span>`)
                        : item.title;

                    return `
                        <div class="suggestion-item" onclick="selectSuggestion('${item.title}', '${inputId}')">
                            <div class="suggestion-icon">
                                <i class="fa-solid ${item.icon}"></i>
                            </div>
                            <div class="suggestion-content">
                                <div class="suggestion-title">
                                    ${highlightedTitle}
                                    <span class="suggestion-badge badge-${item.badge}">${item.badge}</span>
                                </div>
                                <div class="suggestion-desc">${item.desc}</div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            suggestionsDropdown.classList.add('active');
        }
    }

    // Global function to select suggestion
    window.selectSuggestion = function(title, inputId) {
        const input = document.getElementById(inputId);
        input.value = title;
        document.querySelectorAll('.search-suggestions').forEach(el => el.classList.remove('active'));
        
        // You can add navigation logic here
        console.log('Selected:', title);
    };
});
</script>