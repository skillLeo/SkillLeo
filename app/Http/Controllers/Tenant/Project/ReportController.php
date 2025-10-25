<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ReportController extends Controller
{
    /**
     * OVERVIEW DASHBOARD
     * - Velocity (avg story points/week last 4 wks)
     * - Completion rate (last 14d)
     * - Cycle time (hours from created -> done)
     * - Urgent open tasks
     * - Team performance
     * - Project health
     */
    public function index(string $username, Request $request)
    {
        $owner = Auth::user();
        $now = now();

        // time window selector (default 30 days)
        $rangeDays   = (int) $request->input('range', 30);
        $startDate   = $now->copy()->subDays($rangeDays);
        $prevStart   = $startDate->copy()->subDays($rangeDays);
        $prevEnd     = $startDate->copy();

        $dateRanges = [
            7   => 'Last 7 days',
            30  => 'Last 30 days',
            90  => 'Last 90 days',
            365 => 'Last year',
        ];
        $selectedRange = $rangeDays;

        // all projects for this tenant
        $projects = Project::where('user_id', $owner->id)
            ->with(['tasks', 'team'])
            ->get();

        $projectIds = $projects->pluck('id');

        /**
         * ================
         * 1) VELOCITY
         * avg story points closed per week (last 4 weeks)
         * ================
         */
        $completedLast28 = Task::whereIn('project_id', $projectIds)
            ->where('status', 'done')
            ->where('updated_at', '>=', $now->copy()->subDays(28))
            ->sum(DB::raw('COALESCE(story_points, 1)'));

        $completedPrev28 = Task::whereIn('project_id', $projectIds)
            ->where('status', 'done')
            ->whereBetween('updated_at', [
                $now->copy()->subDays(56),
                $now->copy()->subDays(28),
            ])
            ->sum(DB::raw('COALESCE(story_points, 1)'));

        $avgVelocity      = $completedLast28 / 4; // pts/week
        $prevAvgVelocity  = $completedPrev28 / 4;
        $velocityTrendPct = $this->trendPctSafe($avgVelocity, $prevAvgVelocity);

        // sparkline: last 8 weeks of completed story points
        $velocitySparkData = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = $now->copy()->startOfWeek()->subWeeks($i);
            $weekEnd   = $weekStart->copy()->endOfWeek();
            $points    = Task::whereIn('project_id', $projectIds)
                ->where('status', 'done')
                ->whereBetween('updated_at', [$weekStart, $weekEnd])
                ->sum(DB::raw('COALESCE(story_points, 1)'));
            $velocitySparkData[] = $points ?: 0;
        }

        /**
         * ================
         * 2) COMPLETION RATE
         * % of touched tasks (updated in last 14d) that are done
         * ================
         */
        $recentTasks = Task::whereIn('project_id', $projectIds)
            ->where('updated_at', '>=', $now->copy()->subDays(14))
            ->get();

        $totalTouched   = $recentTasks->count();
        $doneTouched    = $recentTasks->where('status', 'done')->count();
        $completionRate = $totalTouched > 0
            ? ($doneTouched / $totalTouched) * 100
            : 0;

        $prevTasks = Task::whereIn('project_id', $projectIds)
            ->whereBetween('updated_at', [
                $now->copy()->subDays(28),
                $now->copy()->subDays(14),
            ])
            ->get();

        $totalPrev        = $prevTasks->count();
        $donePrev         = $prevTasks->where('status', 'done')->count();
        $prevCompletion   = $totalPrev > 0 ? ($donePrev / $totalPrev) * 100 : 0;
        $completionTrend  = $this->trendPctSafe($completionRate, $prevCompletion);

        // spark: tasks closed per day last 8 days
        $completionSparkData = [];
        for ($i = 7; $i >= 0; $i--) {
            $dayStart = $now->copy()->subDays($i)->startOfDay();
            $dayEnd   = $now->copy()->subDays($i)->endOfDay();
            $closed   = Task::whereIn('project_id', $projectIds)
                ->where('status', 'done')
                ->whereBetween('updated_at', [$dayStart, $dayEnd])
                ->count();
            $completionSparkData[] = $closed;
        }

        /**
         * ================
         * 3) CYCLE TIME
         * avg hours from "task created" → "task marked done"
         * in current window vs previous same window
         * ================
         */
        $doneNowWindow = Task::whereIn('project_id', $projectIds)
            ->where('status', 'done')
            ->where('updated_at', '>=', $startDate)
            ->get();

        $cycleTimes = $doneNowWindow->map(function ($t) {
            if (!$t->created_at || !$t->updated_at) {
                return null;
            }
            return $t->updated_at->diffInMinutes($t->created_at) / 60;
        })->filter();

        $avgCycleTime = $cycleTimes->avg() ?: 0;

        $donePrevWindow = Task::whereIn('project_id', $projectIds)
            ->where('status', 'done')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->get();

        $cycleTimesPrev = $donePrevWindow->map(function ($t) {
            if (!$t->created_at || !$t->updated_at) {
                return null;
            }
            return $t->updated_at->diffInMinutes($t->created_at) / 60;
        })->filter();

        $avgCyclePrev  = $cycleTimesPrev->avg() ?: 0;
        $cycleTrendPct = $this->trendImprovementPctSafe($avgCyclePrev, $avgCycleTime);
        $cycleSparkData = $cycleTimes->take(8)->values()->all();
        if (count($cycleSparkData) < 2) {
            $cycleSparkData = array_pad($cycleSparkData, 2, $avgCycleTime);
        }

        /**
         * ================
         * 4) URGENT OPEN TASKS
         * (# of priority=urgent AND not done)
         * ================
         */
        $urgentOpen = Task::whereIn('project_id', $projectIds)
            ->where('priority', 'urgent')
            ->where('status', '!=', 'done')
            ->count();

        $urgentPrev = Task::whereIn('project_id', $projectIds)
            ->where('priority', 'urgent')
            ->where('status', '!=', 'done')
            ->where('updated_at', '<', $startDate)
            ->count();

        $urgentTrendPct   = $this->trendPctSafe($urgentOpen, max(1, $urgentPrev));
        $urgentSparkData  = array_fill(0, 8, $urgentOpen); // flat line (we don't track historical snapshots)

        /**
         * METRIC CARDS PAYLOAD
         */
        $metrics = [
            'velocity' => [
                'label'        => 'Avg Velocity (pts/week)',
                'value'        => round($avgVelocity),
                'trend_text'   => $velocityTrendPct . ' vs prev 4 weeks',
                'trend_icon'   => $this->trendIcon($velocityTrendPct),
                'trend_class'  => $this->trendClass($velocityTrendPct),
                'chart_points' => $this->sparklinePolyline($velocitySparkData),
            ],

            'sprint_completion' => [
                'label'        => 'Completion Rate (last 14d)',
                'value'        => round($completionRate),
                'trend_text'   => $completionTrend . ' vs prev 14d',
                'trend_icon'   => $this->trendIcon($completionTrend),
                'trend_class'  => $this->trendClass($completionTrend),
                'chart_points' => $this->sparklinePolyline($completionSparkData),
            ],

            'cycle_time' => [
                'label'        => 'Avg Cycle Time',
                'value'        => number_format($avgCycleTime, 1),
                'trend_text'   => $cycleTrendPct . ' (improved)',
                'trend_icon'   => $this->trendIconPositiveIfLower($cycleTrendPct),
                'trend_class'  => $this->trendClassPositiveIfLower($cycleTrendPct),
                'chart_points' => $this->sparklinePolyline($cycleSparkData),
            ],

            'active_bugs' => [
                'label'        => 'Urgent Open Tasks',
                'value'        => $urgentOpen,
                'trend_text'   => $urgentTrendPct . ' vs older',
                'trend_icon'   => $this->trendIconNegativeIfHigher($urgentTrendPct),
                'trend_class'  => $this->trendClassNegativeIfHigher($urgentTrendPct),
                'chart_points' => $this->sparklinePolyline($urgentSparkData),
            ],
        ];

        /**
         * TEAM PERFORMANCE
         * For each assignee touched in the window:
         *   - completed tasks
         *   - in-progress tasks
         *   - completion percentage
         */
        $tasksWindow = Task::whereIn('project_id', $projectIds)
            ->where('updated_at', '>=', $startDate)
            ->with('assignedTo')
            ->get();

        $byUser = $tasksWindow
            ->groupBy('assigned_to')
            ->map(function ($tasks, $userId) {
                $completed = $tasks->where('status', 'done')->count();
                $inProg    = $tasks->where('status', '!=', 'done')->count();

                $pct = ($completed + $inProg) > 0
                    ? round(($completed / ($completed + $inProg)) * 100)
                    : 0;

                $user = $tasks->first()->assignedTo;

                return [
                    'user_id'        => $userId,
                    'name'           => $user ? $user->name : 'Unassigned',
                    'role'           => 'Team Member',
                    'completed'      => $completed,
                    'in_progress'    => $inProg,
                    'completion_pct' => $pct,
                ];
            });

        $palette = ['667eea','f093fb','4facfe','43e97b','fa709a','8b5cf6','10b981','3b82f6'];

        $teamPerformance = $byUser
            ->sortByDesc('completion_pct')
            ->take(6)
            ->values()
            ->map(function ($row, $i) use ($palette) {
                $row['avatar_bg'] = $palette[$i % count($palette)];
                return $row;
            })
            ->all();

        /**
         * PROJECT HEALTH
         * Progress, deadline risk, urgent load, team size
         */
        $projectHealth = $projects
            ->sortByDesc('updated_at')
            ->take(4)
            ->map(function ($project) {
                $tasks = $project->tasks;
                $totalCount = $tasks->count();
                $doneCount  = $tasks->where('status', 'done')->count();

                $progressPct = $totalCount > 0
                    ? round(($doneCount / $totalCount) * 100)
                    : 0;

                $dueDate  = $project->due_date ? Carbon::parse($project->due_date) : null;
                $daysLeft = $dueDate
                    ? now()->diffInDays($dueDate, false)
                    : null;

                $daysLeftLabel = $daysLeft === null
                    ? 'No deadline'
                    : ($daysLeft >= 0
                        ? $daysLeft . ' days left'
                        : abs($daysLeft) . ' days overdue');

                $memberCount = $project->team ? $project->team->count() : 0;

                $urgentOpenProject = $tasks
                    ->where('priority', 'urgent')
                    ->where('status', '!=', 'done')
                    ->count();

                // Health classification
                if (
                    $progressPct >= 80 &&
                    ($daysLeft === null || $daysLeft >= 0) &&
                    $urgentOpenProject <= 1
                ) {
                    $health = 'excellent';
                    $color  = '#10b981';
                } elseif (
                    $progressPct >= 50 &&
                    ($daysLeft === null || $daysLeft >= 0) &&
                    $project->status !== 'on-hold'
                ) {
                    $health = 'good';
                    $color  = '#3b82f6';
                } elseif (
                    ($daysLeft !== null && $daysLeft < 0) ||
                    $urgentOpenProject > 3 ||
                    $project->status === 'on-hold'
                ) {
                    $health = 'critical';
                    $color  = '#ef4444';
                } else {
                    $health = 'warning';
                    $color  = '#f59e0b';
                }

                return [
                    'project_name'    => $project->name,
                    'progress_pct'    => $progressPct,
                    'tasks_done'      => $doneCount,
                    'tasks_total'     => $totalCount,
                    'days_left_label' => $daysLeftLabel,
                    'members'         => $memberCount,
                    'health'          => $health,
                    'color'           => $color,
                ];
            })
            ->values()
            ->all();

        return view('tenant.manage.projects.reports.index', compact(
            'username',
            'dateRanges',
            'selectedRange',
            'metrics',
            'teamPerformance',
            'projectHealth'
        ));
    }

    /**
     * BURNDOWN (14-day iteration)
     * We build a rolling 14-day burndown across this user's projects,
     * or optionally a single project (?project_id=)
     */
    public function burndown(string $username, Request $request)
    {
        $owner = Auth::user();
        $now   = now();

        $projectFilter = $request->input('project_id');

        $allProjects = Project::where('user_id', $owner->id)
            ->orderBy('name')
            ->get();

        // Which projects are we analyzing?
        if ($projectFilter) {
            $projects = $allProjects->where('id', $projectFilter)->values();
        } else {
            $projects = $allProjects;
        }

        $projectIds = $projects->pluck('id');

        // 14-day rolling window
        $iterationLength = 14;
        $iterationStart  = $now->copy()->subDays($iterationLength - 1)->startOfDay();
        $iterationEnd    = $iterationStart->copy()->addDays($iterationLength - 1)->endOfDay();
        $period          = CarbonPeriod::create($iterationStart, $iterationEnd);

        // All relevant tasks in scope
        $tasksAll = Task::whereIn('project_id', $projectIds)->get();

        // Build daily raw snapshot
        $dailyRaw = [];
        $i = 0;

        foreach ($period as $day) {
            $dayEnd = $day->copy()->endOfDay();

            $scopeTasks = $tasksAll->filter(function ($t) use ($dayEnd) {
                return Carbon::parse($t->created_at)->lte($dayEnd);
            });

            $scopeTotalPoints = $scopeTasks->sum(function ($t) {
                return $t->story_points ?? 1;
            });

            $completedSoFarPoints = $scopeTasks
                ->filter(function ($t) use ($dayEnd) {
                    return $t->status === 'done' &&
                        Carbon::parse($t->updated_at)->lte($dayEnd);
                })
                ->sum(function ($t) {
                    return $t->story_points ?? 1;
                });

            $remainingNow = max(0, $scopeTotalPoints - $completedSoFarPoints);

            $completedToday = $tasksAll
                ->filter(function ($t) use ($day) {
                    return $t->status === 'done' &&
                        Carbon::parse($t->updated_at)->isSameDay($day);
                })
                ->sum(function ($t) {
                    return $t->story_points ?? 1;
                });

            $addedToday = $tasksAll
                ->filter(function ($t) use ($day) {
                    return Carbon::parse($t->created_at)->isSameDay($day);
                })
                ->sum(function ($t) {
                    return $t->story_points ?? 1;
                });

            $dailyRaw[] = [
                'date'                     => $day->copy(),
                'day_index'                => $i + 1,
                'scope_total_points'       => $scopeTotalPoints,
                'completed_so_far_points'  => $completedSoFarPoints,
                'remaining_now'            => $remainingNow,
                'completed_today'          => $completedToday,
                'added_today'              => $addedToday,
            ];
            $i++;
        }

        if (count($dailyRaw) === 0) {
            // safety fallback
            $dailyRaw[] = [
                'date'                     => $iterationStart->copy(),
                'day_index'                => 1,
                'scope_total_points'       => 0,
                'completed_so_far_points'  => 0,
                'remaining_now'            => 0,
                'completed_today'          => 0,
                'added_today'              => 0,
            ];
        }

        $initialScope = $dailyRaw[0]['scope_total_points'] ?? 0;

        // Final daily rows w/ ideal line + status labels
        $dailyFinal = [];
        foreach ($dailyRaw as $idx => $row) {
            $idealRemaining = $this->idealRemainingPointsForDay(
                $initialScope,
                $iterationLength,
                $idx // zero-based
            );

            $status_key   = $row['added_today'] > 0 ? 'warning' : 'success';
            $status_label = $row['added_today'] > 0 ? 'Scope Added' : 'On Track';

            $dailyFinal[] = [
                'date'             => $row['date'],
                'date_label'       => $row['date']->format('M d, Y'),
                'day_index'        => $row['day_index'],
                'ideal_remaining'  => round($idealRemaining, 1),
                'actual_remaining' => $row['remaining_now'],
                'completed_today'  => $row['completed_today'],
                'added_today'      => $row['added_today'],
                'status_key'       => $status_key,
                'status_label'     => $status_label,
            ];
        }

        $lastRow = end($dailyFinal);

        $totalPoints          = $initialScope;
        $completedPointsNow   = $totalPoints - ($lastRow['actual_remaining'] ?? 0);
        $completionRate       = $totalPoints > 0
            ? round(($completedPointsNow / $totalPoints) * 100)
            : 0;

        // Compare total scope vs previous 14-day window
        $prevIterStart = $iterationStart->copy()->subDays($iterationLength);
        $prevScopeInitial = Task::whereIn('project_id', $projectIds)
            ->where('created_at', '<=', $prevIterStart->copy()->endOfDay())
            ->sum(DB::raw('COALESCE(story_points,1)'));

        $totalPointsTrendPct = $this->trendPctSafe(
            $totalPoints,
            max(1, $prevScopeInitial)
        );

        // Remaining days in this 14d window
        if ($now->lte($iterationEnd)) {
            $daysRemaining = $now->startOfDay()->diffInDays(
                $iterationEnd->startOfDay()
            ) + 1;
        } else {
            $daysRemaining = 0;
        }

        // "At risk": urgent + not done
        $urgentOpenCount = Task::whereIn('project_id', $projectIds)
            ->where('priority', 'urgent')
            ->where('status', '!=', 'done')
            ->count();

        $iterationSummary = [
            'total_points'               => $totalPoints,
            'total_points_trend_class'   => $this->trendClass($totalPointsTrendPct),
            'total_points_trend_icon'    => $this->trendIcon($totalPointsTrendPct),
            'total_points_trend_text'    => $totalPointsTrendPct . ' vs prev 14d',

            'completed_points'           => $completedPointsNow,
            'completion_rate'            => $completionRate,

            'days_remaining'             => $daysRemaining,
            'days_remaining_text'        => 'of ' . $iterationLength . ' day window',

            'at_risk_tasks'              => $urgentOpenCount,
            'at_risk_text'               => 'urgent not done',
        ];

        // Build SVG chart payload
        $chart = $this->buildBurndownChartPayload($dailyFinal, $totalPoints);

        // Insights
        $onTrack      = ($lastRow['actual_remaining'] ?? 0) <= ($lastRow['ideal_remaining'] ?? 0);
        $scopeCreep   = collect($dailyFinal)->contains(fn ($r) => $r['added_today'] > 0);
        $avgPerDay    = round(collect($dailyFinal)->avg('completed_today') ?: 0, 1);

        $insights = [];

        $insights[] = [
            'type_class' => $onTrack ? 'insight-success' : 'insight-warning',
            'icon'       => $onTrack ? 'fa-check-circle' : 'fa-exclamation-circle',
            'title'      => $onTrack ? 'On Track' : 'Behind Projection',
            'desc'       => $onTrack
                ? 'Remaining work is on pace with the ideal burndown.'
                : 'Burn rate is slower than ideal. Consider descoping or reassigning work.',
        ];

        if ($scopeCreep) {
            $insights[] = [
                'type_class' => 'insight-warning',
                'icon'       => 'fa-exclamation-triangle',
                'title'      => 'Scope Creep Detected',
                'desc'       => 'New work was added during this period. Confirm that it\'s truly critical.',
            ];
        }

        $insights[] = [
            'type_class' => 'insight-info',
            'icon'       => 'fa-info-circle',
            'title'      => 'Throughput',
            'desc'       => "Team averages ~{$avgPerDay} story points/day closed.",
        ];

        // Daily progress table
        $dailyProgress = array_map(function ($row) {
            return [
                'day_index'         => $row['day_index'],
                'date_label'        => $row['date_label'],
                'ideal_remaining'   => $row['ideal_remaining'],
                'actual_remaining'  => $row['actual_remaining'],
                'completed_today'   => $row['completed_today'],
                'added_today'       => $row['added_today'],
                'status_key'        => $row['status_key'],
                'status_label'      => $row['status_label'],
            ];
        }, $dailyFinal);

        // Project filter dropdown
        $projectFilterOptions = collect([
            [
                'id'       => 'all',
                'label'    => 'All Projects',
                'selected' => !$projectFilter,
            ],
        ])->merge(
            $allProjects->map(fn ($p) => [
                'id'       => $p->id,
                'label'    => $p->name,
                'selected' => (string) $projectFilter === (string) $p->id,
            ])
        )->values()->all();

        return view('tenant.manage.projects.reports.burndown', compact(
            'username',
            'projectFilterOptions',
            'iterationSummary',
            'chart',
            'insights',
            'dailyProgress'
        ));
    }

    /**
     * TIME TRACKING / WORKLOAD REPORT
     * We don't have a time_entries table.
     * We treat Task.estimated_hours as effort,
     * and we analyze tasks touched in the selected range.
     */
    public function timeTracking(string $username, Request $request)
    {
        $owner = Auth::user();
        $projects = Project::where('user_id', $owner->id)
            ->with(['tasks', 'team'])
            ->get();

        $projectIds = $projects->pluck('id');

        $range = $request->input('range', 'this_week');
        [$rangeStart, $rangeEnd] = $this->resolveWorkRangeBounds($range);

        // All tasks touched in range
        $tasksRange = Task::whereIn('project_id', $projectIds)
            ->whereBetween('updated_at', [$rangeStart, $rangeEnd])
            ->with(['project', 'assignedTo'])
            ->orderByDesc('updated_at')
            ->get();

        // High-level stats
        $totalHours     = round($tasksRange->sum('estimated_hours'), 1);
        $billableHours  = $totalHours; // we don't distinguish billable, so treat all as allocated
        $activeMembers  = $tasksRange->pluck('assigned_to')->filter()->unique()->count();
        $allMemberCount = DB::table('project_team')
            ->whereIn('project_id', $projectIds)
            ->distinct('user_id')
            ->count('user_id');

        $numDays      = max(1, $rangeStart->diffInDays($rangeEnd) + 1);
        $avgPerDay    = round($totalHours / $numDays, 1);

        $highLevel = [
            'total_hours'         => $totalHours,
            'total_trend'         => 'Current period',
            'billable_hours'      => $billableHours,
            'billable_ratio'      => '100% allocated',
            'active_members'      => $activeMembers,
            'active_members_text' => "out of $allMemberCount total",
            'avg_hours_per_day'   => $avgPerDay,
            'avg_hours_comment'   => $avgPerDay >= 7 ? 'Above target' : 'Below target',
        ];

        /**
         * TIME BY PROJECT
         * - completed = status done
         * - in progress = not done
         */
        $projectTimeBreakdown = $tasksRange
            ->groupBy('project_id')
            ->map(function ($rows, $pid) use ($projects) {
                $project = $projects->firstWhere('id', $pid);

                $completedHours   = $rows->where('status', 'done')->sum('estimated_hours');
                $inProgressHours  = $rows->where('status', '!=', 'done')->sum('estimated_hours');
                $totalH           = max(0.1, $completedHours + $inProgressHours);
                $color            = $this->colorFromId($pid);

                return [
                    'project_name'       => $project ? $project->name : 'Unknown Project',
                    'billable_hours'     => round($completedHours, 1),   // Completed
                    'nonbillable_hours'  => round($inProgressHours, 1),  // In Progress
                    'total_hours'        => round($totalH, 1),
                    'billable_pct'       => round(($completedHours / $totalH) * 100, 1),
                    'nonbillable_pct'    => round(($inProgressHours / $totalH) * 100, 1),
                    'color'              => $color,
                ];
            })
            ->sortByDesc('total_hours')
            ->values()
            ->all();

        /**
         * TEAM TIME LOG (really: recent task activity)
         */
        $timeLogRows = $tasksRange
            ->take(20)
            ->map(function ($t) {
                $user    = $t->assignedTo;
                $project = $t->project;

                $hours      = $t->estimated_hours
                    ? round($t->estimated_hours, 1) . 'h'
                    : '—';
                $type_key   = $t->status === 'done' ? 'completed' : 'in-progress';
                $status_key = $t->status === 'done' ? 'success' : 'warning';

                return [
                    'member_name'   => $user ? $user->name : 'Unassigned',
                    'avatar_bg'     => $this->colorHexFromName($user ? $user->name : 'NA'),
                    'project_name'  => $project ? $project->name : '—',
                    'task_title'    => $t->title,
                    'date_label'    => Carbon::parse($t->updated_at)->format('M d, Y'),
                    'hours'         => $hours,
                    'type_key'      => $type_key,
                    'type_label'    => ucfirst(str_replace('-', ' ', $type_key)),
                    'status_key'    => $status_key,
                    'status_label'  => $t->status === 'done' ? 'Completed' : 'In Progress',
                ];
            })
            ->values()
            ->all();

        /**
         * TODAY'S TIMELINE
         * (tasks touched today, ordered by time)
         */
        $now = now();

        $todayTasks = Task::whereIn('project_id', $projectIds)
            ->whereDate('updated_at', $now->toDateString())
            ->with(['project', 'assignedTo'])
            ->orderBy('updated_at')
            ->get();

        $timelineEntries = $todayTasks->map(function ($t) {
            $user    = $t->assignedTo;
            $project = $t->project;

            $durationLabel = $t->estimated_hours
                ? round($t->estimated_hours, 1) . 'h est'
                : '';

            return [
                'start_time'     => Carbon::parse($t->updated_at)->format('h:i A'),
                'duration_label' => $durationLabel,
                'task_title'     => $t->title,
                'project_name'   => $project ? $project->name : '—',
                'user_short'     => $user ? Str::limit($user->name, 12, '') : '—',
            ];
        })->values()->all();

        /**
         * FILTER DROPDOWNS
         */
        $dateRangeOptionsTT = [
            [
                'value'    => 'this_week',
                'label'    => 'This Week (' . $rangeStart->format('M d') . ' - ' . $rangeEnd->format('M d, Y') . ')',
                'selected' => $range === 'this_week',
            ],
            [
                'value'    => 'last_week',
                'label'    => 'Last Week (' .
                    now()->subWeek()->startOfWeek()->format('M d') .
                    ' - ' .
                    now()->subWeek()->endOfWeek()->format('M d, Y') .
                    ')',
                'selected' => $range === 'last_week',
            ],
            [
                'value'    => 'this_month',
                'label'    => 'This Month (' .
                    now()->startOfMonth()->format('M d') .
                    ' - ' .
                    now()->format('M d, Y') .
                    ')',
                'selected' => $range === 'this_month',
            ],
            [
                'value'    => 'last_month',
                'label'    => 'Last Month',
                'selected' => $range === 'last_month',
            ],
            [
                'value'    => 'custom',
                'label'    => 'Custom Range',
                'selected' => $range === 'custom',
            ],
        ];

        $projectFilterOptions = collect([
            [
                'id'       => 'all',
                'label'    => 'All Projects',
                'selected' => true,
            ],
        ])->merge(
            $projects->map(fn ($p) => [
                'id'       => $p->id,
                'label'    => $p->name,
                'selected' => false,
            ])
        )->values()->all();

        $memberFilterOptions = collect([
            [
                'id'       => 'all',
                'label'    => 'All Members',
                'selected' => true,
            ],
        ])->merge(
            User::whereIn(
                'id',
                $tasksRange->pluck('assigned_to')->filter()->unique()
            )
                ->get()
                ->map(fn ($u) => [
                    'id'       => $u->id,
                    'label'    => $u->name,
                    'selected' => false,
                ])
        )->values()->all();

        return view('tenant.manage.projects.reports.time-tracking', compact(
            'username',
            'dateRangeOptionsTT',
            'projectFilterOptions',
            'memberFilterOptions',
            'highLevel',
            'projectTimeBreakdown',
            'timeLogRows',
            'timelineEntries'
        ));
    }

    /**
     * VELOCITY (weekly delivery)
     * We don't have Sprints.
     * We show last 10 calendar weeks of "story points completed".
     */
    public function velocity(string $username, Request $request)
    {
        $owner = Auth::user();
        $projects = Project::where('user_id', $owner->id)->get();
        $projectIds = $projects->pluck('id');

        $now   = now();
        $weeks = [];

        // oldest → newest (9 weeks ago up to this week)
        for ($i = 9; $i >= 0; $i--) {
            $startWeek = $now->copy()->startOfWeek()->subWeeks($i);
            $endWeek   = $startWeek->copy()->endOfWeek();

            $tasksWeek = Task::whereIn('project_id', $projectIds)
                ->where('status', 'done')
                ->whereBetween('updated_at', [$startWeek, $endWeek])
                ->get();

            $pointsCompleted = $tasksWeek->sum(function ($t) {
                return $t->story_points ?? 1;
            });

            $tasksCompleted = $tasksWeek->count();

            $weeks[] = [
                'sprint_name'        => 'Week ' . $startWeek->format('W'),
                'week_label'         => 'Week ' . $startWeek->format('W'),
                'date_range'         => $startWeek->format('M d') . ' - ' . $endWeek->format('M d, Y'),
                'completed_points'   => (int) $pointsCompleted,
                'tasks_completed'    => $tasksCompleted,
                'start_label'        => $startWeek->format('M d'),
            ];
        }

        $maxPoints = max(array_column($weeks, 'completed_points')) ?: 0;
        if ($maxPoints < 10) {
            $maxPoints = 10;
        }

        // scale bar heights
        $weeks = array_map(function ($w) use ($maxPoints) {
            $w['completed_pct_height'] = $maxPoints > 0
                ? ($w['completed_points'] / $maxPoints) * 100
                : 0;
            return $w;
        }, $weeks);

        // summary stats
        $avgVelocity = count($weeks) > 0
            ? round(array_sum(array_column($weeks, 'completed_points')) / count($weeks))
            : 0;

        $avgTasksPerWeek = count($weeks) > 0
            ? round(array_sum(array_column($weeks, 'tasks_completed')) / count($weeks), 1)
            : 0;

        // trend last 5 vs first 5
        $first5      = array_slice($weeks, 0, 5);
        $last5       = array_slice($weeks, 5, 5);
        $first5avg   = count($first5) > 0
            ? array_sum(array_column($first5, 'completed_points')) / count($first5)
            : 0;
        $last5avg    = count($last5) > 0
            ? array_sum(array_column($last5, 'completed_points')) / count($last5)
            : 0;
        $avgVelocityTrend = $this->trendPctSafe($last5avg, $first5avg);

        $highest = collect($weeks)
            ->sortByDesc('completed_points')
            ->first();

        $highestPoints = $highest ? $highest['completed_points'] : 0;
        $highestLabel  = $highest
            ? ($highest['week_label'] . ' ' . $highest['start_label'])
            : '—';

        $stdDev = $this->stdDev(array_column($weeks, 'completed_points'));
        $mean   = max(1, $avgVelocity);
        $cv     = ($stdDev / $mean) * 100;
        $consistencyPct = round(max(0, 100 - $cv));

        // y-axis ticks
        $yTicks = [];
        $step   = max(1, floor($maxPoints / 5));
        for ($v = $maxPoints; $v >= 0; $v -= $step) {
            $yTicks[] = $v;
        }
        $averageLinePct = $maxPoints > 0
            ? ($avgVelocity / $maxPoints) * 100
            : 0;

        $chartMeta = [
            'y_ticks'          => $yTicks,
            'average_line_pct' => $averageLinePct,
        ];

        $velocitySummary = [
            'avg_velocity'          => $avgVelocity,
            'avg_velocity_trend'    => $avgVelocityTrend,
            'avg_tasks_per_week'    => $avgTasksPerWeek,
            'highest_week_points'   => $highestPoints,
            'highest_week_label'    => $highestLabel,
            'consistency_pct'       => $consistencyPct,
            'std_dev'               => round($stdDev, 1),
        ];

        // insights
        $velocityInsights = [
            [
                'type_class' => 'insight-positive',
                'icon'       => 'fa-check-circle',
                'icon_bg'    => 'rgba(16,185,129,0.1)',
                'icon_color' => '#10b981',
                'heading'    => 'Delivery Pace',
                'text'       => 'Average weekly output is ' . $avgVelocity . ' story points.',
            ],
            [
                'type_class' => 'insight-warning',
                'icon'       => 'fa-exclamation-triangle',
                'icon_bg'    => 'rgba(245,158,11,0.1)',
                'icon_color' => '#f59e0b',
                'heading'    => 'Variability',
                'text'       => 'Some weeks dip below average. Check load balance and blockers.',
            ],
            [
                'type_class' => 'insight-info',
                'icon'       => 'fa-info-circle',
                'icon_bg'    => 'rgba(59,130,246,0.1)',
                'icon_color' => '#3b82f6',
                'heading'    => 'Most Productive Week',
                'text'       => 'Peak throughput was ' . $highestPoints . ' points in ' . $highestLabel . '.',
            ],
        ];

        // table rows w/ status (latest week = Active)
        $lastIndex   = count($weeks) - 1;
        $weeklyRows  = [];
        foreach ($weeks as $i => $w) {
            $w['status_key']   = $i === $lastIndex ? 'active' : 'completed';
            $w['status_label'] = $i === $lastIndex ? 'Active' : 'Completed';
            $weeklyRows[]      = $w;
        }

        return view('tenant.manage.projects.reports.velocity', compact(
            'username',
            'chartMeta',
            'velocitySummary',
            'weeklyRows',
            'velocityInsights'
        ));
    }

    /* =======================================================
       HELPER METHODS
    ======================================================= */

    private function trendPctSafe($current, $previous): string
    {
        $current  = (float) $current;
        $previous = (float) $previous;

        if ($previous == 0.0) {
            if ($current == 0.0) {
                return '0%';
            }
            return '+100%';
        }

        $delta = (($current - $previous) / $previous) * 100;
        $sign  = $delta >= 0 ? '+' : '';
        return $sign . round($delta, 1) . '%';
    }

    // improvement where LOWER is better (cycle time)
    private function trendImprovementPctSafe($previous, $current): string
    {
        $previous = (float) $previous;
        $current  = (float) $current;

        if ($previous == 0.0) {
            if ($current == 0.0) {
                return '0%';
            }
            // went from 0 to >0 => "worse"
            $delta = (($current - $previous) / max(1, $previous)) * 100;
            $sign  = $delta >= 0 ? '+' : '';
            return $sign . round($delta, 1) . '%';
        }

        $delta = (($previous - $current) / $previous) * 100;
        $sign  = $delta >= 0 ? '-' : '+';
        return $sign . round(abs($delta), 1) . '%';
    }

    private function trendIcon(string $trendString): string
    {
        if (Str::startsWith($trendString, '+')) {
            return 'fa-arrow-up';
        }
        if (Str::startsWith($trendString, '-')) {
            return 'fa-arrow-down';
        }
        return 'fa-minus';
    }

    private function trendClass(string $trendString): string
    {
        if (Str::startsWith($trendString, '+')) {
            return 'metric-trend-up';
        }
        if (Str::startsWith($trendString, '-')) {
            return 'metric-trend-down';
        }
        return 'metric-trend-neutral';
    }

    // For cycle time where DOWN (faster) is "good"
    private function trendIconPositiveIfLower(string $trendString): string
    {
        if (Str::startsWith($trendString, '-')) {
            return 'fa-arrow-down'; // lower is better
        }
        if (Str::startsWith($trendString, '+')) {
            return 'fa-arrow-up';
        }
        return 'fa-minus';
    }

    private function trendClassPositiveIfLower(string $trendString): string
    {
        if (Str::startsWith($trendString, '-')) {
            return 'metric-trend-up'; // improvement
        }
        if (Str::startsWith($trendString, '+')) {
            return 'metric-trend-down';
        }
        return 'metric-trend-neutral';
    }

    // For urgent tasks where UP is bad
    private function trendIconNegativeIfHigher(string $trendString): string
    {
        if (Str::startsWith($trendString, '+')) {
            return 'fa-arrow-up'; // up = worse
        }
        if (Str::startsWith($trendString, '-')) {
            return 'fa-arrow-down';
        }
        return 'fa-minus';
    }

    private function trendClassNegativeIfHigher(string $trendString): string
    {
        if (Str::startsWith($trendString, '+')) {
            return 'metric-trend-down'; // worse
        }
        if (Str::startsWith($trendString, '-')) {
            return 'metric-trend-up';
        }
        return 'metric-trend-neutral';
    }

    // sparkline <polyline> points
    private function sparklinePolyline(array $values, int $w = 200, int $h = 40): string
    {
        if (count($values) < 2) {
            return "0," . ($h - 10) . " " . $w . "," . ($h - 10);
        }

        $max = max($values);
        $min = min($values);
        if ($max == $min) {
            $max = $min + 1;
        }

        $padTopBottom = 5;
        $usableH      = $h - ($padTopBottom * 2);
        $count        = count($values);

        $points = [];
        foreach ($values as $i => $val) {
            $x      = ($i / ($count - 1)) * $w;
            $yRatio = ($val - $min) / ($max - $min);
            $y      = $h - ($yRatio * $usableH) - $padTopBottom;
            $points[] = $x . "," . $y;
        }

        return implode(' ', $points);
    }

    private function idealRemainingPointsForDay(int $totalPoints, int $totalDays, int $dayIndexZeroBased)
    {
        if ($totalDays <= 1) {
            return max(0, $totalPoints - $totalPoints);
        }
        $burnPerDay = $totalPoints / ($totalDays - 1);
        return max(0, $totalPoints - ($burnPerDay * $dayIndexZeroBased));
    }

    /**
     * Build burndown line data for SVG
     */
    private function buildBurndownChartPayload(array $dailyRows, int $totalPoints): array
    {
        // chart frame
        $xStart = 50;
        $xEnd   = 750;
        $yTop   = 50;
        $yBot   = 350;
        $chartW = $xEnd - $xStart;
        $chartH = $yBot - $yTop;

        $daysCount = count($dailyRows);
        $xStep     = $daysCount > 1 ? $chartW / ($daysCount - 1) : $chartW;

        // y scale helper
        $scaleY = function ($remaining) use ($totalPoints, $yTop, $chartH) {
            if ($totalPoints <= 0) {
                return $yTop + $chartH;
            }
            $ratio = $remaining / $totalPoints;
            return $yTop + ($ratio * $chartH);
        };

        $idealPoints   = [];
        $actualPoints  = [];
        $projPoints    = [];
        $actualCircles = [];

        // Ideal burndown
        foreach ($dailyRows as $i => $row) {
            $x      = $xStart + ($i * $xStep);
            $yIdeal = $scaleY($row['ideal_remaining']);
            $idealPoints[] = $x . ',' . $yIdeal;
        }

        // Actual burndown
        foreach ($dailyRows as $i => $row) {
            $x        = $xStart + ($i * $xStep);
            $yActual  = $scaleY($row['actual_remaining']);
            $actualPoints[] = $x . ',' . $yActual;
            $actualCircles[] = ['x' => $x, 'y' => $yActual];
        }

        // Projection (simple linear extension of last two points)
        if ($daysCount >= 2) {
            $lastIdx = $daysCount - 1;
            $prevIdx = $daysCount - 2;

            $xLast  = $xStart + ($lastIdx * $xStep);
            $yLast  = $scaleY($dailyRows[$lastIdx]['actual_remaining']);

            $xPrev  = $xStart + ($prevIdx * $xStep);
            $yPrev  = $scaleY($dailyRows[$prevIdx]['actual_remaining']);

            $slope  = ($yLast - $yPrev) / (($xLast - $xPrev) ?: 1);

            $projPoints[] = $xLast . ',' . $yLast;

            $xFuture = $xEnd;
            $yFuture = $yLast + ($slope * ($xFuture - $xLast));
            $projPoints[] = $xFuture . ',' . $yFuture;
        }

        // y-guides
        $yGuides = [];
        $steps   = 5;
        for ($idx = 0; $idx <= $steps; $idx++) {
            $remainingVal = $totalPoints - ($idx * ($totalPoints / $steps));
            $yGuides[] = [
                'y'     => $scaleY($remainingVal),
                'label' => round($remainingVal),
            ];
        }

        // x-axis labels
        $xLabels = [];
        foreach ($dailyRows as $i => $row) {
            $xLabels[] = [
                'x'    => $xStart + ($i * $xStep),
                'text' => 'Day ' . $row['day_index'],
            ];
        }

        return [
            'ideal_line'      => implode(' ', $idealPoints),
            'actual_line'     => implode(' ', $actualPoints),
            'projection_line' => implode(' ', $projPoints),
            'actual_points'   => $actualCircles,
            'y_guides'        => $yGuides,
            'x_labels'        => $xLabels,
        ];
    }

    private function resolveWorkRangeBounds(string $range): array
    {
        $now = now();

        switch ($range) {
            case 'last_week':
                $start = $now->copy()->subWeek()->startOfWeek();
                $end   = $now->copy()->subWeek()->endOfWeek();
                break;

            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end   = $now;
                break;

            case 'last_month':
                $start = $now->copy()->subMonthNoOverflow()->startOfMonth();
                $end   = $now->copy()->subMonthNoOverflow()->endOfMonth();
                break;

            case 'custom':
                $start = $now->copy()->subDays(7);
                $end   = $now;
                break;

            case 'this_week':
            default:
                $start = $now->copy()->startOfWeek();
                $end   = $now;
                break;
        }

        return [$start, $end];
    }

    private function colorFromId($id): string
    {
        $palette = ['#0052cc','#10b981','#8b5cf6','#f59e0b','#3b82f6','#ef4444'];
        if (!$id) {
            return $palette[0];
        }
        return $palette[$id % count($palette)];
    }

    private function colorHexFromName(string $name): string
    {
        $colors = ['667eea','f093fb','4facfe','43e97b','fa709a','8b5cf6','10b981','3b82f6'];
        $idx    = crc32($name) % count($colors);
        return $colors[$idx];
    }

    private function stdDev(array $values): float
    {
        $n = count($values);
        if ($n === 0) {
            return 0.0;
        }
        $mean = array_sum($values) / $n;
        $variance = array_sum(array_map(function ($v) use ($mean) {
            $diff = $v - $mean;
            return $diff * $diff;
        }, $values)) / $n;
        return sqrt($variance);
    }
}
