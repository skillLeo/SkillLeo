@props(['variant' => 'default'])

<div class="card card-{{ $variant }} {{ $attributes->get('class') }}" {{ $attributes->except(['class']) }}>
    {{ $slot }}
</div>

@once
@push('styles')
<style>

</style>
@endpush
@endonce