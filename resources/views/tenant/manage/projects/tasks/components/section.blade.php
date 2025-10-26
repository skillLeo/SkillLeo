@php
/**
 * Props required:
 *  - $title (string)
 *  - $icon (string, fontawesome class)
 *  - $tasks (Collection<Task>)
 *  - $highlight (string: danger|info|accent|warning|muted etc.)
 *  - $viewer (User)     // optional but nice for future permissions
 */
$colorsByHighlight = [
    'danger'  => ['bg' => 'rgba(239,68,68,0.1)', 'fg' => '#ef4444', 'border' => 'rgba(239,68,68,0.3)'],
    'info'    => ['bg' => 'rgba(59,130,246,0.1)', 'fg' => '#3b82f6', 'border' => 'rgba(59,130,246,0.3)'],
    'accent'  => ['bg' => 'var(--accent-light)', 'fg' => 'var(--accent)', 'border' => 'var(--accent)33'],
    'warning' => ['bg' => 'rgba(245,158,11,0.1)', 'fg' => '#f59e0b', 'border' => 'rgba(245,158,11,0.3)'],
    'muted'   => ['bg' => 'var(--bg)', 'fg' => 'var(--text-muted)', 'border' => 'var(--border)'],
];
$theme = $colorsByHighlight[$highlight] ?? $colorsByHighlight['muted'];
@endphp

<section class="task-section-card">
    <header class="task-section-header" style="border-left-color: {{ $theme['fg'] }};">
        <div class="task-section-header-left">
            <div class="task-section-icon" style="background: {{ $theme['bg'] }}; color: {{ $theme['fg'] }};">
                <i class="fas {{ $icon }}"></i>
            </div>
            <div>
                <div class="task-section-title">{{ $title }}</div>
                <div class="task-section-count">{{ $tasks->count() }} task{{ $tasks->count() !== 1 ? 's' : '' }}</div>
            </div>
        </div>

        <div class="task-section-header-right">
            <button class="project-btn project-btn-ghost task-section-action">
                <i class="fas fa-eye"></i>
                <span>Focus View</span>
            </button>
        </div>
    </header>

    <div class="task-section-body">
        @foreach($tasks as $task)
            @include('tenant.manage.projects.tasks.components.task-row', [
                'task' => $task,
                'themeColor' => $theme['fg'],
            ])
        @endforeach
    </div>
</section>

<style>
.task-section-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    margin-bottom:24px;
    overflow:hidden;
}

.task-section-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    flex-wrap:wrap;
    gap:16px;
    padding:16px 20px;
    border-bottom:1px solid var(--border);
    border-left:4px solid transparent;
}

.task-section-header-left{
    display:flex;
    align-items:flex-start;
    gap:12px;
    flex:1;
    min-width:200px;
}

.task-section-icon{
    width:40px;
    height:40px;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    flex-shrink:0;
}

.task-section-title{
    font-size:var(--fs-h3);
    font-weight:var(--fw-semibold);
    color:var(--text-heading);
    margin:0;
    line-height:1.3;
}
.task-section-count{
    font-size:var(--fs-subtle);
    color:var(--text-muted);
    line-height:1.3;
}

.task-section-header-right{
    display:flex;
    align-items:center;
    gap:8px;
}
.task-section-action{
    display:flex;
    align-items:center;
    gap:6px;
    white-space:nowrap;
}

.task-section-body{
    display:flex;
    flex-direction:column;
}
</style>
