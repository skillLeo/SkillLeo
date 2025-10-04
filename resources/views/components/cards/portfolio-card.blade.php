{{-- resources/views/components/cards/portfolio-card.blade.php --}}
<div class="portfolio-card">
    <img src="#" 
         alt="{{ $portfolio['title'] ?? 'Portfolio Project' }}" 
         class="portfolio-image">
    
    <div class="portfolio-content">
        <h3 class="portfolio-title">
            {{ $portfolio['title'] ?? 'How Laravel + AI Can Transform Your Business' }}
        </h3>
        
        <div class="portfolio-tech">
            {{ implode(' | ', $portfolio['technologies'] ?? ['PHP+', 'CSS3', 'HTML', 'Laravel', 'Python']) }}
        </div>
        
        <p class="portfolio-desc">
            {{ $portfolio['description'] ?? 'A productivity tool for organizing tasks, setting deadlines, and tracking progress.' }}
        </p>
        
        {{-- NEW: Professional link display with icon --}}
        <div class="portfolio-link">
            <x-ui.icon name="link" variant="outlined" size="sm" class="color-accent" />
            <span>{{ $portfolio['url'] ?? 'www.codefixxer.com' }}</span>
        </div>
        
        <div class="launched-info">
            {{ $portfolio['timeline'] ?? 'Launched in 3 Weeks' }}
        </div>
        
        {{-- NEW: Button with eye icon --}}
        <x-ui.button 
        variant="solid" 
        shape="square" 
        color="primary" 
        size="md"
        onclick="viewProject({{ $portfolio['id'] ?? 1 }})"
        class="w-full mt-3"
    >
        <x-ui.icon name="eye" variant="outlined" size="sm" />
        View Details
    </x-ui.button>
    </div>
</div>