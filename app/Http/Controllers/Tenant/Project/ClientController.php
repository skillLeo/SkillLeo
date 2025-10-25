<?php
// app/Http/Controllers/Tenant/Project/ClientController.php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Show Clients page (list + grid)
     */
    public function index(string $username)
    {
        $owner = Auth::user();

        // Base query for this tenant's clients
        $baseClientQuery = Client::with([
                'clientUser',   // linked user (contact person)
                'projects',     // projects for this client
            ])
            ->where('user_id', $owner->id);

        // Paginated list for display
        $clients = (clone $baseClientQuery)
            ->orderByDesc('created_at')
            ->paginate(12);

        // ===== Stats cards =====

        // Total number of clients
        $totalClients = (clone $baseClientQuery)->count();

        // Projects that are not cancelled/completed
        $activeProjects = Project::where('user_id', $owner->id)
            ->whereIn('status', ['planning', 'active', 'on-hold'])
            ->count();

        // Total order value across all clients
        $totalOrderValue = (clone $baseClientQuery)->sum('order_value');
        $totalOrderValue = $totalOrderValue ?? 0;

        // % of clients fully paid
        $fullyPaidCount = (clone $baseClientQuery)
            ->where('payment_status', 'paid')
            ->count();

        $paymentCompletionPct = $totalClients > 0
            ? round(($fullyPaidCount / $totalClients) * 100)
            : 0;

        $stats = [
            'total_clients'          => $totalClients,
            'active_projects'        => $activeProjects,
            'total_order_value'      => $totalOrderValue,
            'payment_completion_pct' => $paymentCompletionPct,
        ];

        return view('tenant.manage.projects.clients.index', compact(
            'username',
            'clients',
            'stats'
        ));
    }
}
