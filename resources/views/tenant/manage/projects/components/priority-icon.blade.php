{{-- resources/views/tenant/projects/components/priority-icon.blade.php --}}
@php
    $priorityConfig = [
        'highest' => ['icon' => 'angle-double-up', 'color' => '#ef4444', 'label' => 'Highest'],
        'high' => ['icon' => 'angle-up', 'color' => '#f59e0b', 'label' => 'High'],
        'medium' => ['icon' => 'minus', 'color' => '#3b82f6', 'label' => 'Medium'],
        'low' => ['icon' => 'angle-down', 'color' => '#10b981', 'label' => 'Low'],
        'lowest' => ['icon' => 'angle-double-down', 'color' => '#6b7280', 'label' => 'Lowest'],
    ];
    
    $config = $priorityConfig[$priority] ?? $priorityConfig['medium'];
@endphp

<div class="priority-icon" title="{{ $config['label'] }}">
    <i class="fas fa-{{ $config['icon'] }}" style="color: {{ $config['color'] }};"></i>
</div>

<style>
    .priority-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }

    .priority-icon i {
        font-size: 16px;
    }
</style>