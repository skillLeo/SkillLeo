<nav class="top-nav">
    <div class="nav-inner">
        <div class="nav-row nav-row--top">
            <img class="nav-avatar" src="{{ $user->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" alt="Profile">
            <a class="brand" href="#" aria-label="{{ $brandName ?? 'Portfolio' }}">
                <svg class="brand-mark" viewBox="0 0 48 24" aria-hidden="true">
                    <path d="M18 3 6 12l12 9" fill="none" stroke="#1351d8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M30 3 18 12l12 9" fill="none" stroke="#0a6bff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="brand-text">{{ $brandName ?? 'Portfolio' }}</span>
            </a>
            <button class="share-btn">
                <i class="fa-solid fa-share-nodes"></i><span>Share</span>
            </button>
        </div>

        <div class="nav-row nav-row--bottom">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Search">
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

        <div class="nav-row nav-row--desktop">
            <a class="brand" href="#">
                <svg class="brand-mark" viewBox="0 0 48 24" aria-hidden="true">
                    <path d="M18 3 6 12l12 9" fill="none" stroke="#1351d8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M30 3 18 12l12 9" fill="none" stroke="#0a6bff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="brand-text">{{ $brandName ?? 'Portfolio' }}</span>
            </a>
            <div class="search-wrap search-wrap--desktop">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" type="text" placeholder="Graphic Designer, Web Developer etc.">
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