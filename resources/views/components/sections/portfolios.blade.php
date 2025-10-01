<section class="portfolios-section">


<style>
    .cards-header button .ui-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        /* color: var(--accent) !important; */
        width: 1.6em;
        height: 1.6em;
    }
    .projects-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2vw;

    }
</style>

    <div class="cards-header">
        <h2 class="portfolios-title">Portfolios</h2>

<div class="projects-btn">        
        <x-ui.button variant="special-outlined" shape="rounded" color="primary" size="sm">
            Add Projects
            {{-- <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" /> --}}
        </x-ui.button>

        <button class="edit-card icon-btn" aria-label="Edit card">
            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
          </button>


        </div>





    </div>

    <div class="filter-tabs">
        @foreach ($categories ?? ['All', 'Laravel', 'React Js', 'Node Js', 'AI', 'Mern Stack'] as $category)
        <x-ui.button 
            variant="{{ $loop->first ? 'solid' : 'outlined' }}" 
            shape="rounded" 
            color="primary_muted"
            size="sm" 
            class="filter-tab-btn {{ $loop->first ? 'active' : '' }}"
            data-filter="{{ strtolower($category) }}" 
            onclick="filterCategory('{{ strtolower($category) }}', this)">
            {{ $category }}
        </x-ui.button>
    @endforeach
    </div>

    <div class="portfolio-grid">
        @forelse($portfolios ?? [] as $portfolio)
            <x-cards.portfolio-card :portfolio="$portfolio" />
        @empty
            <x-cards.portfolio-card />
        @endforelse
    </div>

    <x-ui.see-all text="See all Projects" onclick="showAllProjects()" />
</section>

<script>
    function filterCategory(category, buttonElement) {
        // Remove active state from all filter buttons
        document.querySelectorAll('.filter-tab-btn').forEach(btn => {
            btn.classList.remove('btn-solid');
            btn.classList.add('btn-outlined');
            btn.classList.remove('active');
        });

        // Add active state to clicked button
        buttonElement.classList.remove('btn-outlined');
        buttonElement.classList.add('btn-solid', 'active');

        // Filter logic here
        console.log('Filtering by:', category);
    }

    function showAllProjects() {
        console.log('Showing all projects...');
    }
</script>
