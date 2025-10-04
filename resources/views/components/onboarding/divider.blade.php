@props([
    'text' => null
])

@if($text)
    <div class="divider-text">
        {{ $text }}
    </div>
@else
    <div class="divider"></div>
@endif