{{-- resources/views/tenant/projects/components/issue-type-icon.blade.php --}}
@php
    $typeConfig = [
        'story' => ['icon' => 'bookmark', 'color' => '#10b981', 'label' => 'Story'],
        'task' => ['icon' => 'check-square', 'color' => '#3b82f6', 'label' => 'Task'],
        'bug' => ['icon' => 'bug', 'color' => '#ef4444', 'label' => 'Bug'],
        'spike' => ['icon' => 'lightbulb', 'color' => '#f59e0b', 'label' => 'Spike'],
    ];
    
    $config = $typeConfig[$type] ?? $typeConfig['task'];
@endphp

<div class="issue-type-icon" title="{{ $config['label'] }}">
    <i class="fas fa-{{ $config['icon'] }}" style="color: {{ $config['color'] }};"></i>
</div>

<style>
    .issue-type-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }

    .issue-type-icon i {
        font-size: 16px;
    }
</style>