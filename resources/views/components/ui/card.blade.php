@props(['variant' => 'default'])

<div class="card card-{{ $variant }} {{ $attributes->get('class') }}" {{ $attributes->except(['class']) }}>
    {{ $slot }}
</div>

@once
@push('styles')
<style>
    .card {
        border: 2px solid var(--border) !important;
        border-radius: 12px;
        padding: 20px;
        background: var(--white);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        position: relative;
        margin-bottom: 14px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .card:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
    }

    .card-display {
        background: var(--gray-100);
    }

    .card-edit {
        border: 1px solid var(--dark);
    }
</style>
@endpush
@endonce