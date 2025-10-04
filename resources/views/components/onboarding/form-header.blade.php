@props([
    'step' => 1,
    'totalSteps' => 8,
    'title',
    'subtitle' => null
])

<div class="form-header">
    <div class="step-badge">
        Step {{ $step }} of {{ $totalSteps }}
    </div>
    
    <h1 class="form-title">{{ $title }}</h1>
    
    @if($subtitle)
        <p class="form-subtitle">{{ $subtitle }}</p>
    @endif
</div>