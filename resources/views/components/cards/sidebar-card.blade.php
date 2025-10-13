<section class="card pad24">





    <div class="cards-header">
        <h2 class="portfolios-title">{{ $title }}</h2>
        <button class="edit-card icon-btn" aria-label="Edit card">
            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
          </button>
          
    </div>









    <div class="card-content">
        {{ $slot }}
    </div>
    
    @if($showSeeAll ?? false)
    <x-ui.see-all :text="'See all ' . $title" onclick="showAll{{ str_replace(' ', '', $title) }}()" />
@endif

</section>