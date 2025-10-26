<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Subtask;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClientInvitation;
use App\Mail\ClientInvitationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Tenant\BaseTenantController;

class ProjectController extends BaseTenantController
{
    /**
     * GET /{username}/manage/projects
     * List all projects
     */
    public function index(string $username)
    {
        $projects = Project::with([
                'client.clientUser',
                'tasks',
                'team',
            ])
            ->where('user_id', $this->workspaceOwner->id)
            ->latest()
            ->paginate(12);

        $stats = [
            'total' => Project::where('user_id', $this->workspaceOwner->id)->count(),

            'active' => Project::where('user_id', $this->workspaceOwner->id)
                ->where('status', 'active')
                ->count(),

            'orders' => Project::where('user_id', $this->workspaceOwner->id)
                ->whereHas('client')
                ->count(),

            'hours_this_month' => Task::whereHas('project', function ($q) {
                    $q->where('user_id', $this->workspaceOwner->id);
                })
                ->whereMonth('created_at', now()->month)
                ->sum('estimated_hours') ?? 0,
        ];

        return view('tenant.manage.projects.list', [
            'username' => $username,
            'projects' => $projects,
            'stats' => $stats,
        ]);
    }

    /**
     * GET /{username}/manage/projects/{project}
     * Show single project detail page
     * 
     * FIXED: Changed parameter type to Project (model binding)
     */
    public function show(string $username, Project $project)
    {
        // Security check: ensure project belongs to workspace owner
        if ($project->user_id !== $this->workspaceOwner->id) {
            abort(403, 'Unauthorized access to this project.');
        }

        // Load relationships
        $project->load([
            'client.clientUser',
            'tasks' => function ($query) {
                $query->with(['subtasks', 'assignee', 'dependencies']);
            },
            'team',
            'user',
        ]);

        return view('tenant.manage.projects.show', [
            'username' => $username,
            'project'  => $project,
        ]);
    }

    /**
     * POST /{username}/manage/projects
     * Create new project
     */
    public function store(Request $request, string $username)
    {
        Log::info('Project Create Request - START', [
            'user_id' => $this->workspaceOwner->id,
            'request_data' => $request->except(['tasks', 'team']),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:10|unique:projects,key',
            'type' => 'required|in:scrum,kanban,waterfall,custom',
            'category' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'estimated_hours' => 'nullable|numeric|min:0',
            'flags' => 'nullable|array',

            'tasks' => 'nullable|array',
            'tasks.*.title' => 'required_with:tasks|string|max:255',
            'tasks.*.notes' => 'nullable|string',
            'tasks.*.priority' => 'required_with:tasks|in:low,medium,high,urgent',
            'tasks.*.due_date' => 'nullable|date',
            'tasks.*.estimated_hours' => 'nullable|numeric|min:0',
            'tasks.*.story_points' => 'nullable|integer|min:0',
            'tasks.*.flags' => 'nullable|array',
            'tasks.*.subtasks' => 'nullable|array',
            'tasks.*.subtasks.*.title' => 'required_with:tasks.*.subtasks|string|max:255',
            'tasks.*.subtasks.*.completed' => 'nullable|boolean',
            'tasks.*.dependencies' => 'nullable|array',
            'tasks.*.assigned_to' => 'nullable|exists:users,id',

            'team' => 'nullable|array',
            'team.*.user_id' => 'nullable|exists:users,id',
            'team.*.role' => 'nullable|string',
            'team.*.tech_stack' => 'nullable|string',
            'team.*.position' => 'nullable|string',

            'client_user_id' => 'nullable|exists:users,id',
            'client_company' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'order_value' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|in:milestone,upfront50,monthly,completion',
            'special_requirements' => 'nullable|string|max:2000',
            'portal_access' => 'nullable|boolean',
            'can_comment' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Handle client creation if provided
            $clientId = null;
            if (!empty($validated['client_user_id'])) {
                $clientUser = User::findOrFail($validated['client_user_id']);

                if ($clientUser->account_status !== 'client') {
                    throw new \Exception('Selected user is not registered as a client.');
                }

                $client = Client::create([
                    'user_id' => $this->workspaceOwner->id,
                    'client_user_id' => $validated['client_user_id'],
                    'company' => $validated['client_company'] ?? null,
                    'phone' => $validated['client_phone'] ?? null,
                    'order_value' => $validated['order_value'] ?? null,
                    'currency' => $validated['currency'] ?? 'PKR',
                    'payment_terms' => $validated['payment_terms'] ?? 'milestone',
                    'portal_access' => $validated['portal_access'] ?? true,
                    'can_comment' => $validated['can_comment'] ?? false,
                    'special_requirements' => $validated['special_requirements'] ?? null,
                ]);

                $clientId = $client->id;
                Log::info('Client created', ['client_id' => $clientId]);
            }

            // Create project
            $project = Project::create([
                'user_id' => $this->workspaceOwner->id,
                'client_id' => $clientId,
                'name' => $validated['name'],
                'key' => strtoupper($validated['key']),
                'type' => $validated['type'],
                'category' => $validated['category'] ?? null,
                'priority' => $validated['priority'],
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'],
                'due_date' => $validated['due_date'],
                'budget' => $validated['budget'] ?? null,
                'currency' => $validated['currency'] ?? 'PKR',
                'estimated_hours' => $validated['estimated_hours'] ?? null,
                'flags' => $validated['flags'] ?? [],
                'status' => 'planning',
            ]);

            Log::info('Project created', [
                'project_id' => $project->id,
                'project_name' => $project->name
            ]);

            // Create tasks with mapping for dependencies
            $taskIdMapping = [];

            if (!empty($validated['tasks'])) {
                foreach ($validated['tasks'] as $originalTaskId => $taskData) {
                    $task = $project->tasks()->create([
                        'title' => $taskData['title'],
                        'notes' => $taskData['notes'] ?? null,
                        'priority' => $taskData['priority'],
                        'due_date' => $taskData['due_date'] ?? null,
                        'estimated_hours' => $taskData['estimated_hours'] ?? null,
                        'story_points' => $taskData['story_points'] ?? 0,
                        'flags' => $taskData['flags'] ?? [],
                        'order' => count($taskIdMapping),
                        'assigned_to' => $taskData['assigned_to'] ?? null,
                        'reporter_id' => $this->workspaceOwner->id,
                        'status' => Task::STATUS_TODO,
                    ]);

                    $taskIdMapping[$originalTaskId] = $task->id;

                    // Create subtasks
                    if (!empty($taskData['subtasks'])) {
                        foreach ($taskData['subtasks'] as $subtaskIndex => $subtaskData) {
                            $task->subtasks()->create([
                                'title' => $subtaskData['title'],
                                'completed' => $subtaskData['completed'] ?? false,
                                'order' => $subtaskIndex,
                            ]);
                        }
                    }
                }

                // Attach task dependencies
                foreach ($validated['tasks'] as $originalTaskId => $taskData) {
                    if (!empty($taskData['dependencies']) && isset($taskIdMapping[$originalTaskId])) {
                        $taskModel = Task::find($taskIdMapping[$originalTaskId]);

                        foreach ($taskData['dependencies'] as $dependencyOriginalId) {
                            if (isset($taskIdMapping[$dependencyOriginalId])) {
                                $taskModel->dependencies()->attach(
                                    $taskIdMapping[$dependencyOriginalId]
                                );
                            }
                        }
                    }
                }
            }

            // Add team members
            if (!empty($validated['team'])) {
                foreach ($validated['team'] as $member) {
                    if (!empty($member['user_id'])) {
                        $teamUser = User::find($member['user_id']);

                        if ($teamUser && $teamUser->account_status === 'professional') {
                            $project->team()->attach($member['user_id'], [
                                'role' => $member['role'] ?? '',
                                'tech_stack' => $member['tech_stack'] ?? '',
                                'position' => $member['position'] ?? '',
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            Log::info('Project creation SUCCESS', [
                'project_id' => $project->id,
                'total_tasks' => count($taskIdMapping),
            ]);

            return redirect()
                ->route('tenant.manage.projects.show', [$username, $project->id])
                ->with('success', '✅ Project created successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Project creation FAILED', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create project: ' . $e->getMessage());
        }
    }

    /**
     * Search users for team assignment
     */
    public function searchUsers(Request $request, string $username)
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', $this->workspaceOwner->id)
            ->where('account_status', 'professional')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('username', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'email', 'avatar_url')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar_url ?? "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=0052CC&color=fff"
                ];
            });

        return response()->json($users);
    }

    /**
     * Search clients
     */
    public function searchClients(Request $request, string $username)
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $clients = User::where('id', '!=', $this->workspaceOwner->id)
            ->where('account_status', 'client')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('username', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'email', 'avatar_url')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                $previousOrder = Client::where('user_id', $this->workspaceOwner->id)
                    ->where('client_user_id', $user->id)
                    ->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar_url ?? "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=0052CC&color=fff",
                    'company' => $previousOrder->company ?? null,
                    'phone' => $previousOrder->phone ?? null,
                    'has_previous_order' => (bool) $previousOrder,
                ];
            });

        return response()->json($clients);
    }

    /**
     * Invite client
     */
    public function inviteClient(Request $request, string $username)
    {
        try {
            $validated = $request->validate([
                'email'   => ['required', 'email', 'max:255'],
                'name'    => ['required', 'string', 'max:255'],
                'message' => ['nullable', 'string', 'max:1000'],
            ]);

            $existingUser = User::where('email', $validated['email'])->first();

            if ($existingUser) {
                if ($existingUser->account_status === 'client') {
                    return response()->json([
                        'success' => false,
                        'message' => 'This user is already a client. Please search for them instead.',
                    ], 400);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'This user already has a professional account. They cannot be invited as a client.',
                ], 400);
            }

            $existingInvitation = ClientInvitation::where('email', $validated['email'])
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->first();

            if ($existingInvitation) {
                return response()->json([
                    'success' => false,
                    'message' => 'There is already a pending invitation for this email address.',
                ], 400);
            }

            $invitation = ClientInvitation::create([
                'inviter_id'  => $this->workspaceOwner->id,
                'email'       => $validated['email'],
                'name'        => $validated['name'],
                'message'     => $validated['message'] ?? null,
                'token'       => Str::random(64),
                'status'      => 'pending',
                'expires_at'  => now()->addDays(7),
            ]);

            try {
                Mail::to($validated['email'])->send(new ClientInvitationMail($invitation));

                return response()->json([
                    'success'    => true,
                    'email_sent' => true,
                    'message'    => '✅ Invitation sent successfully!',
                    'invitation' => [
                        'id'        => $invitation->id,
                        'email'     => $invitation->email,
                        'name'      => $invitation->name,
                        'expires_at' => $invitation->expires_at->format('Y-m-d H:i:s'),
                    ],
                ], 200);

            } catch (\Throwable $mailError) {
                Log::error('Client invitation email failed', [
                    'error' => $mailError->getMessage(),
                ]);

                $invitation->delete();

                return response()->json([
                    'success'    => false,
                    'message'    => '❌ Failed to send invitation email.',
                ], 500);
            }

        } catch (\Throwable $e) {
            Log::error('Failed to create client invitation', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Accept invitation
     */
    public function acceptInvitation(Request $request, string $token)
    {
        $invitation = ClientInvitation::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired invitation link.');
        }

        if ($invitation->expires_at < now()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('login')
                ->with('error', 'This invitation has expired.');
        }

        $existingUser = User::where('email', $invitation->email)->first();

        if ($existingUser) {
            if ($existingUser->account_status !== 'client') {
                $existingUser->update(['account_status' => 'client']);
            }

            $invitation->update([
                'status' => 'accepted',
                'accepted_at' => now()
            ]);

            Auth::login($existingUser);

            return redirect()->route('dashboard')
                ->with('success', 'Welcome! You can now access your client projects.');
        }

        session([
            'invitation_token' => $token,
            'invitation_email' => $invitation->email,
            'invitation_name' => $invitation->name,
            'account_type' => 'client'
        ]);

        return redirect()->route('register')
            ->with('info', 'Please complete your registration to accept the invitation.');
    }
}