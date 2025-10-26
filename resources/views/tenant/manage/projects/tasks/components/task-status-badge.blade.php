@php
/**
 * Prop:
 *  - $status (string: todo | in-progress | review | done | blocked | postponed ...)
 */
$status = strtolower($status ?? 'todo');

$map = [
    'todo'         => ['bg' => 'rgba(107,114,128,0.1)', 'fg' => '#6b7280', 'icon' => 'fa-circle'],
    'in-progress'  => ['bg' => 'rgba(59,130,246,0.1)',  'fg' => '#3b82f6', 'icon' => 'fa-play'],
    'review'       => ['bg' => 'rgba(245,158,11,0.1)', 'fg' => '#f59e0b', 'icon' => 'fa-clipboard-check'],
    'done'         => ['bg' => 'rgba(16,185,129,0.1)', 'fg' => '#10b981', 'icon' => 'fa-check-circle'],
    'blocked'      => ['bg' => 'rgba(239,68,68,0.1)',  'fg' => '#ef4444', 'icon' => 'fa-ban'],
    'postponed'    => ['bg' => 'rgba(156,163,175,0.15)', 'fg' => '#9ca3af', 'icon' => 'fa-clock'],
];

$cfg = $map[$status] ?? $map['todo'];
@endphp

<span class="task-status-badge" style="background:{{ $cfg['bg'] }};color:{{ $cfg['fg'] }};">
    <i class="fas {{ $cfg['icon'] }}"></i>
    <span>{{ ucfirst(str_replace('-', ' ', $status)) }}</span>
</span>

<style>
.task-status-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:4px 10px;
    border-radius:999px;
    font-size:var(--fs-subtle);
    font-weight:var(--fw-semibold);
    line-height:1.2;
    white-space:nowrap;
}
</style>
