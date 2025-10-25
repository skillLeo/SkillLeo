<?php
// app/Http/Controllers/Tenant/Project/TeamController.php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Show Team Members page (grid + table)
     */
    public function index(string $username)
    {
        $owner = Auth::user();

        // Pull all unique users who are on any of THIS owner's projects
        $teamMembers = User::whereHas('projectsAsTeamMember', function ($q) use ($owner) {
                $q->where('projects.user_id', $owner->id);
            })
            ->with([
                // we'll eager load the pivot data for role etc.
                'projectsAsTeamMember' => function ($q) use ($owner) {
                    $q->where('projects.user_id', $owner->id)
                      ->withPivot(['role', 'tech_stack', 'position']);
                },
            ])
            ->withCount([
                // active tasks
                'assignedTasks as active_tasks_count' => function ($q) {
                    $q->whereNotIn('status', ['done', 'completed', 'review']);
                },
                // completed tasks
                'assignedTasks as completed_tasks_count' => function ($q) {
                    $q->whereIn('status', ['done', 'completed']);
                },
            ])
            ->orderBy('name')
            ->get();

        // Enrich each member with derived data for Blade
        $teamMembers->transform(function ($member) {
            $activeTasks = (int) ($member->active_tasks_count ?? 0);

            // crude workload guess: 10% per active task, max 100
            $capacityPercent = min(100, $activeTasks * 10);

            // last active human-friendly
            $lastActiveHuman = $member->last_seen_at
                ? $member->last_seen_at->diffForHumans()
                : ($member->updated_at ? $member->updated_at->diffForHumans() : '—');

            // map to UI status class
            // online_status comes from User accessor:
            //   online | active_recently | offline
            // we convert to: active | busy | away
            $rawStatus = $member->online_status ?? 'offline';
            if ($rawStatus === 'online') {
                $computedStatus = 'active';
            } elseif ($rawStatus === 'active_recently') {
                $computedStatus = 'busy';
            } else {
                $computedStatus = 'away';
            }

            // role fallback attempt: first project pivot role
            $pivotRole = optional($member->projectsAsTeamMember->first())->pivot->role ?? null;
            $displayRole = $pivotRole ?: 'Team Member';

            $member->setAttribute('capacity_percent', $capacityPercent);
            $member->setAttribute('last_active_human', $lastActiveHuman);
            $member->setAttribute('display_role', $displayRole);
            $member->setAttribute('computed_status', $computedStatus);

            return $member;
        });

        // Team-level stats (header cards)
        $totalMembers = $teamMembers->count();

        $activeNow = $teamMembers->filter(function ($member) {
            // treat "active" statusClass as currently active
            return ($member->computed_status ?? '') === 'active';
        })->count();

        $totalActiveTasks = $teamMembers->sum('active_tasks_count');

        $avgCapacityPct = $teamMembers->count() > 0
            ? round($teamMembers->avg('capacity_percent'))
            : 0;

        $stats = [
            'total_members'     => $totalMembers,
            'active_now'        => $activeNow,
            'active_tasks'      => $totalActiveTasks,
            'avg_capacity_pct'  => $avgCapacityPct,
        ];

        return view('tenant.manage.projects.team.index', compact(
            'username',
            'teamMembers',
            'stats'
        ));
    }

    /**
     * Workload subpage
     */
    public function workload(string $username)
    {
        return view('tenant.manage.projects.team.workload', compact('username'));
    }
}
