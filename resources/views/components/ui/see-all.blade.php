@props([
    'text' => 'See all',
    'href' => '#',
    'onclick' => null,
])

<div class="see-all-container">
    <a href="{{ $href }}" class="see-all-link"
        @if ($onclick) onclick="{{ $onclick }}" @endif {{ $attributes }}>
        <span class="see-all-text">{{ $text }}</span>
        <i class="fas fa-arrow-right see-all-icon"></i>
    </a>
</div>


<style>

</style>
