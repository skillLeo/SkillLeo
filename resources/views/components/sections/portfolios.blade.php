<section class="portfolios-section">
    <style>
        .cards-header button .ui-icon { display:flex; align-items:center; justify-content:center; }
        .projects-btn { display:flex; align-items:center; justify-content:space-between; gap:2vw; }
    </style>

    <div class="cards-header">
        <h2 class="portfolios-title">Portfolios</h2>
        <div class="projects-btn">
            <x-ui.button variant="special-outlined" shape="rounded" color="primary" size="sm">
                Add Projects
            </x-ui.button>

            <button class="edit-card icon-btn" aria-label="Edit card">
                <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
            </button>
        </div>
    </div>

    @php use Illuminate\Support\Str; @endphp

    <div class="filter-tabs">
        @foreach(($categories ?? ['All']) as $categoryLabel)
            @php $cat = Str::slug($categoryLabel ?: 'All'); @endphp
            <x-ui.button
                variant="{{ $loop->first ? 'solid' : 'outlined' }}"
                shape="rounded"
                color="primary_muted"
                size="sm"
                class="filter-tab-btn {{ $loop->first ? 'active' : '' }}"
                data-filter="{{ $cat }}"
                onclick="filterCategory('{{ $cat }}', this)">
                {{ $categoryLabel }}
            </x-ui.button>
        @endforeach
    </div>

    <div id="portfolioGrid" class="portfolio-grid">
        @forelse(($portfolios ?? []) as $p)
            @php $cat = Str::slug($p['category'] ?? 'All'); @endphp
            <div class="portfolio-item" data-category="{{ $cat }}">
                <x-cards.portfolio-card :portfolio="$p" />
            </div>
        @empty
            <div class="portfolio-item" data-category="all"><x-cards.portfolio-card /></div>
            <div class="portfolio-item" data-category="all"><x-cards.portfolio-card /></div>
        @endforelse
    </div>

    <x-ui.see-all text="See all Projects" onclick="showAllProjects()" />
</section>

<script>
(function () {
    const grid = document.getElementById('portfolioGrid');
    const btns = document.querySelectorAll('.filter-tab-btn');

    window.filterCategory = function (category, buttonElement) {
        // Toggle active styles
        btns.forEach(btn => {
            btn.classList.remove('btn-solid', 'active');
            btn.classList.add('btn-outlined');
        });
        buttonElement.classList.remove('btn-outlined');
        buttonElement.classList.add('btn-solid', 'active');

        // Filter items
        const items = grid.querySelectorAll('.portfolio-item');
        const cat = (category || 'all').toLowerCase();
        items.forEach(el => {
            const c = (el.dataset.category || 'all').toLowerCase();
            el.style.display = (cat === 'all' || c === cat) ? '' : 'none';
        });
    };

    window.showAllProjects = function () {
        const firstBtn = document.querySelector('.filter-tab-btn[data-filter="all"]') || btns[0];
        if (firstBtn) filterCategory(firstBtn.dataset.filter, firstBtn);
    };
})();
</script>
