<?php

namespace App\Http\Controllers\Tenant\Manage;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(string $username, Request $request)
    {
        /**
         * Resolve "owner" (the professional / workspace user)
         * We trust username in URL to map to that owner's workspace.
         */
        $owner = User::query()
            ->with('profile')
            ->where('username', $username)
            ->firstOrFail();

        // Security idea:
        // if (Auth::id() !== $owner->id) { abort(403); } 
        // ^ uncomment if each user can only view their own dashboard.

        /**
         * BASIC COUNTS / METRICS (real data)
         */

        // All projects that belong to this owner
        $projectsQuery = Project::where('user_id', $owner->id);

        $projectCount        = (clone $projectsQuery)->count();
        $activeProjectCount  = (clone $projectsQuery)->where('status', 'active')->count();
        $totalBudget         = (clone $projectsQuery)->sum('budget');

        // Tasks under owner's projects
        $tasksQuery = Task::whereHas('project', function ($q) use ($owner) {
            $q->where('user_id', $owner->id);
        });

        $openTasksCount = (clone $tasksQuery)->where('status', '!=', 'done')->count();
        $overdueTasksCount = (clone $tasksQuery)
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now())
            ->count();

        // Unique team members across all this owner's projects (via pivot project_team)
        $teamCount = DB::table('project_team')
            ->join('projects', 'project_team.project_id', '=', 'projects.id')
            ->where('projects.user_id', $owner->id)
            ->distinct('project_team.user_id')
            ->count('project_team.user_id');

        /**
         * "ACTIVE ORDERS" concept:
         * We'll treat active projects as open engagements.
         */
        $activeOrders = $activeProjectCount;

        /**
         * "REVENUE"
         * We'll treat sum(budget) as revenue pipeline.
         */
        $revenue = round($totalBudget ?? 0, 2);

        /**
         * BASIC TOP-CARD METRICS BLOCK
         * - in a real system you'd pull this from analytics tables.
         * - here we generate stable derived/sample values using what's in DB.
         */
        $visitors     = max(200, $projectCount * 300 + $teamCount * 75 + 1200);
        $impressions  = $visitors * 2 + 1000;
        $ctaClicks    = (int) round($visitors * 0.09);
        $growth7d     = +9.2;   // %
        $growth30d    = +5.7;   // %
        $ctaDelta     = -3.4;   // %
        $billOverdue  = $overdueTasksCount; // we don't have invoices, reuse overdue tasks idea

        /**
         * TREND DATA (for the line chart)
         * We'll generate time-series for 7d / 30d / 90d. 
         * This is computed at request time, so it's dynamic (not hardcoded in the blade).
         */
        $trend7d  = $this->generateTrendDataset(days: 7,  baseVisitors: $visitors,    baseImpressions: $impressions);
        $trend30d = $this->generateTrendDataset(days: 30, baseVisitors: $visitors*0.8,baseImpressions: $impressions*0.8);
        $trend90d = $this->generateTrendDataset(days: 90, baseVisitors: $visitors*0.6,baseImpressions: $impressions*0.65);

        /**
         * PROJECT STATUS BREAKDOWN → Donut chart
         */
        $statusCounts = (clone $projectsQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // ensure consistent keys
        $allStatuses = ['planning','active','on-hold','completed','cancelled'];
        $projectStatusData = [];
        foreach ($allStatuses as $st) {
            $projectStatusData[$st] = (int) ($statusCounts[$st] ?? 0);
        }

        /**
         * FUNNEL (quick marketing-ish funnel)
         * We'll simulate funnel ratios from "visitors" down to "orders".
         * All math is consistent so it's not static text.
         */
        $funnelStages = $this->buildFunnel(
            $visitors,
            $projectClicksPct = 0.62,
            $contactPct       = 0.28,
            $ordersCreatedPct = 0.12,
            $paidPct          = 0.09
        );

        /**
         * DUE SOON
         * Pull tasks that are due in next 7 days or already overdue, sorted by due_date.
         * Limit 4 to keep UI tight.
         */
        $dueSoonTasks = (clone $tasksQuery)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->copy()->addDays(7)->toDateString())
            ->orderBy('due_date', 'asc')
            ->with(['project', 'assignedTo'])
            ->limit(4)
            ->get()
            ->map(function ($task) {
                $dueDiffHuman = $task->due_date
                    ? Carbon::parse($task->due_date)->diffForHumans([
                        'parts' => 2,
                        'short' => true,
                        'syntax' => Carbon::DIFF_RELATIVE_TO_NOW
                    ])
                    : null;

                return [
                    'title'         => $task->title,
                    'project_name'  => optional($task->project)->name ?? '—',
                    'amount'        => optional($task->project)->budget
                        ? '$' . number_format(optional($task->project)->budget,2)
                        : null,
                    'is_overdue'    => $task->due_date && $task->due_date->isPast() && $task->status !== 'done',
                    'due_human'     => $dueDiffHuman ?? 'No due date',
                    'assignee_name' => optional($task->assignedTo)->name ?? 'Unassigned',
                ];
            })
            ->values();

        /**
         * RECENT ACTIVITY FEED
         * We'll use recent task updates as "activity".
         */
        $recentActivities = (clone $tasksQuery)
            ->with(['project', 'assignedTo'])
            ->orderBy('updated_at','desc')
            ->limit(6)
            ->get()
            ->map(function ($task) {
                $user   = $task->assignedTo;
                $avatarBg = $this->colorHexFromName($user?->name ?? 'X');
                $when   = Carbon::parse($task->updated_at)->diffForHumans();
                $action = match(true){
                    $task->status === 'done' => 'completed task',
                    $task->status === 'review' => 'sent task for review',
                    default => 'updated task',
                };

                return [
                    'user_name'     => $user?->name ?? 'Unassigned',
                    'avatar_url'    => "https://ui-avatars.com/api/?name=" . urlencode($user?->name ?? 'NA') . "&background={$avatarBg}&color=fff",
                    'when'          => $when,
                    'action'        => $action,
                    'task_title'    => $task->title,
                    'project_label' => optional($task->project)->name ?? '—',
                ];
            })
            ->values();

        /**
         * TIMELINE TODAY
         * (We show "what happened today" similar to sprint timeline)
         */
        $todayTimeline = (clone $tasksQuery)
            ->whereDate('updated_at', now()->toDateString())
            ->orderBy('updated_at', 'asc')
            ->with(['project', 'assignedTo'])
            ->limit(8)
            ->get()
            ->map(function ($task) {
                return [
                    'time'       => Carbon::parse($task->updated_at)->format('h:i A'),
                    'duration'   => $task->estimated_hours
                        ? round($task->estimated_hours,1) . 'h est'
                        : null,
                    'task'       => $task->title,
                    'project'    => optional($task->project)->name ?? '—',
                    'member'     => optional($task->assignedTo)->name
                        ? Str::limit(optional($task->assignedTo)->name, 18, '')
                        : '—',
                ];
            })
            ->values();

        /**
         * DATA PASSED TO VIEW
         */
        $metrics = [
            'visitors'        => $visitors,
            'impressions'     => $impressions,
            'cta_clicks'      => $ctaClicks,
            'growth_7d'       => $growth7d,
            'growth_30d'      => $growth30d,
            'cta_change'      => $ctaDelta,
            'active_orders'   => $activeOrders,
            'revenue'         => $revenue,
            'overdue_count'   => $billOverdue,
            'open_tasks'      => $openTasksCount,
            'overdue_tasks'   => $overdueTasksCount,
            'team_members'    => $teamCount,
            'project_count'   => $projectCount,
        ];

        // Chart datasets for JS
        $trendDataSets = [
            '7d'  => $trend7d,
            '30d' => $trend30d,
            '90d' => $trend90d,
        ];

        // donut chart data for JS
        $donutData = [
            'labels' => array_map('ucfirst', array_keys($projectStatusData)),
            'values' => array_values($projectStatusData),
        ];

        // timeline fallback if empty
        if ($todayTimeline->isEmpty()) {
            $todayTimeline = collect([[
                'time'     => now()->format('h:i A'),
                'duration' => '—',
                'task'     => 'No recent updates yet',
                'project'  => '—',
                'member'   => $owner->name,
            ]]);
        }

        // pass $owner as $user to match your layout usage
        $user = $owner;

        return view('tenant.manage.dashboard', compact(
            'username',
            'user',
            'metrics',
            'trendDataSets',
            'donutData',
            'funnelStages',
            'dueSoonTasks',
            'recentActivities',
            'todayTimeline'
        ));
    }

    /**
     * Build sparkline-ish dataset for visitors/impressions over last N days.
     */
    protected function generateTrendDataset(int $days, float $baseVisitors, float $baseImpressions): array
    {
        $labels       = [];
        $visitorsArr  = [];
        $impressArr   = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = now()->copy()->subDays($i);
            $labels[] = $day->format('M d');

            // simple fluctuation
            $v = round($baseVisitors * (0.7 + (mt_rand(0,30)/100)));
            $impr = round($baseImpressions * (0.7 + (mt_rand(0,30)/100)));

            $visitorsArr[] = $v;
            $impressArr[]  = $impr;
        }

        return [
            'labels'      => $labels,
            'visitors'    => $visitorsArr,
            'impressions' => $impressArr,
        ];
    }

    /**
     * Funnel progression builder (returns % widths for each stage).
     */
    protected function buildFunnel(int $visitors, float $projectClicksPct, float $contactPct, float $ordersPct, float $paidPct): array
    {
        $stagesRaw = [
            'Profile views'     => $visitors,
            'Project clicks'    => (int) round($visitors * $projectClicksPct),
            'Contact requests'  => (int) round($visitors * $contactPct),
            'Orders created'    => (int) round($visitors * $ordersPct),
            'Paid orders'       => (int) round($visitors * $paidPct),
        ];

        $maxVal = max($stagesRaw) ?: 1;

        $stages = [];
        foreach ($stagesRaw as $label => $val) {
            $pct = round(($val / $maxVal) * 100, 1); // for bar width
            $stages[] = [
                'label'   => $label,
                'count'   => $val,
                'percent' => $pct,
            ];
        }
        return $stages;
    }

    /**
     * generate pastel-ish hex from name for avatar backgrounds (stable & pretty)
     */
    protected function colorHexFromName(string $name): string
    {
        $hash = crc32(strtolower($name));
        // pick one of these palette keys
        $palette = [
            '3b82f6','10b981','8b5cf6','f59e0b','ef4444','14b8a6','0ea5e9','6366f1','d946ef',
            '475569','0ea5e9','2563eb','fb7185','a855f7','84cc16','06b6d4'
        ];
        return $palette[$hash % count($palette)];
    }
}
