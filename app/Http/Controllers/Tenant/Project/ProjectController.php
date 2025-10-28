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
use App\Services\Projects\CreateProjectService;
use App\Http\Controllers\Tenant\BaseTenantController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        // Load all necessary relationships for comprehensive display
        $project->load([
            'client.clientUser',
            'tasks' => function ($query) {
                $query->with(['subtasks', 'assignee', 'dependencies'])
                      ->orderBy('order')
                      ->orderBy('id');
            },
            'team',
            'user',
            'media' => function ($query) {
                $query->orderBy('sort_order')->orderBy('id');
            },
            'notes' => function ($query) {
                $query->with('author')->latest();
            }
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
   
     public function store(Request $request, string $username, CreateProjectService $creator)
     {
         Log::info('ProjectController@store: Starting', [
             'workspace_owner_id' => $this->workspaceOwner->id,
             'viewer_id' => $this->viewer->id,
             'request_data_keys' => array_keys($request->all()),
         ]);
     
         // Normalize nullable fields
         $request->merge([
             'budget'          => $request->filled('budget') ? $request->input('budget') : null,
             'estimated_hours' => $request->filled('estimated_hours') ? $request->input('estimated_hours') : null,
             'order_value'     => $request->filled('order_value') ? $request->input('order_value') : null,
         ]);
     
         // Normalize task fields
         if ($request->has('tasks')) {
             $tasks = $request->input('tasks');
             foreach ($tasks as $i => $t) {
                 $tasks[$i]['due_date'] = !empty($t['due_date']) ? $t['due_date'] : null;
                 $tasks[$i]['estimated_hours'] = !empty($t['estimated_hours']) ? $t['estimated_hours'] : null;
                 $tasks[$i]['story_points'] = isset($t['story_points']) ? (int)$t['story_points'] : 0;
             }
             $request->merge(['tasks' => $tasks]);
         }
     
         // Validation rules
         $validator = Validator::make($request->all(), [
             'name'        => ['required','string','max:255'],
             'key'         => ['required','string','max:10','unique:projects,key'],
             'type'        => ['required', Rule::in(['scrum','kanban','waterfall','custom'])],
             'category'    => ['nullable','string','max:255'],
             'priority'    => ['required', Rule::in(['low','medium','high','urgent'])],
             'description' => ['nullable','string','max:5000'],
             'start_date'  => ['required','date'],
             'due_date'    => ['required','date','after_or_equal:start_date'],
             'budget'      => ['nullable','numeric','min:0'],
             'currency'    => ['nullable','string','max:3'],
             'estimated_hours' => ['nullable','numeric','min:0'],
     
             'flags' => ['nullable','array'],
     
             'tasks'                     => ['nullable','array'],
             'tasks.*.title'             => ['required_with:tasks','string','max:255'],
             'tasks.*.notes'             => ['nullable','string'],
             'tasks.*.priority'          => ['required_with:tasks', Rule::in(['low','medium','high','urgent'])],
             'tasks.*.due_date'          => ['nullable','date'],
             'tasks.*.estimated_hours'   => ['nullable','numeric','min:0'],
             'tasks.*.story_points'      => ['nullable','integer','min:0'],
             'tasks.*.assigned_to'       => ['nullable','exists:users,id'],
             'tasks.*.subtasks'          => ['nullable','array'],
             'tasks.*.subtasks.*.title'  => ['required_with:tasks.*.subtasks','string','max:255'],
             'tasks.*.subtasks.*.completed' => ['nullable','boolean'],
     
             'team'              => ['nullable','array'],
             'team.*.user_id'    => ['nullable','exists:users,id'],
             'team.*.role'       => ['nullable','string','max:255'],
             'team.*.tech_stack' => ['nullable','string','max:255'],
             'team.*.position'   => ['nullable','string','max:255'],
     
             'client_user_id'        => ['nullable','exists:users,id'],
             'client_company'        => ['nullable','string','max:255'],
             'client_phone'          => ['nullable','string','max:50'],
             'order_value'           => ['nullable','numeric','min:0'],
             'payment_terms'         => ['nullable', Rule::in(['milestone','upfront50','monthly','completion'])],
             'special_requirements'  => ['nullable','string','max:2000'],
             'portal_access'         => ['nullable','boolean'],
             'can_comment'           => ['nullable','boolean'],
     
             'notes'                 => ['nullable','array'],
             'notes.*.body'          => ['required_with:notes','string','max:5000'],
             'notes.*.is_internal'   => ['nullable','boolean'],
             'notes.*.pinned'        => ['nullable','boolean'],
     
             'media'                 => ['nullable','array'],
             'media.*.file'          => ['nullable','file','max:51200'],
             'media.*.visibility'    => ['nullable', Rule::in(['internal','client'])],
             'media.*.note'          => ['nullable','string','max:2000'],
             'media.*.sort_order'    => ['nullable','integer','min:0'],
         ]);
     
         if ($validator->fails()) {
             Log::warning('ProjectController@store: Validation failed', [
                 'errors' => $validator->errors()->toArray()
             ]);
             
             return response()->json([
                 'success' => false,
                 'message' => 'Validation failed',
                 'errors'  => $validator->errors(),
             ], 422);
         }
     
         $validated = $validator->validated();
     
         // Rebuild media array with actual file objects
         $validated['media'] = [];
         if ($request->has('media')) {
             foreach ($request->input('media') as $i => $mediaData) {
                 if ($request->hasFile("media.$i.file")) {
                     $validated['media'][$i] = [
                         'file'       => $request->file("media.$i.file"),
                         'visibility' => $mediaData['visibility'] ?? 'internal',
                         'note'       => $mediaData['note'] ?? null,
                         'sort_order' => (int) ($mediaData['sort_order'] ?? $i),
                     ];
                 }
             }
         }
     
         Log::info('ProjectController@store: Validation passed', [
             'validated_keys' => array_keys($validated),
             'tasks_count' => count($validated['tasks'] ?? []),
             'team_count' => count($validated['team'] ?? []),
             'media_count' => count($validated['media'] ?? []),
         ]);
     
         try {
             $project = $creator->execute($validated, $this->workspaceOwner, $this->viewer);
     
             Log::info('ProjectController@store: Success', [
                 'project_id' => $project->id,
                 'project_name' => $project->name,
             ]);
     
             return response()->json([
                 'success'     => true,
                 'project_id'  => $project->id,
                 'redirect_to' => route('tenant.manage.projects.project.show', [$username, $project->id]),
                 'message'     => '✅ Project created successfully!',
             ], 201);
     
         } catch (\Illuminate\Validation\ValidationException $e) {
             Log::error('ProjectController@store: Validation Exception', [
                 'errors' => $e->errors(),
             ]);
     
             return response()->json([
                 'success' => false,
                 'message' => 'Validation error',
                 'errors'  => $e->errors(),
             ], 422);
     
         } catch (\Throwable $e) {
             Log::error('ProjectController@store: Exception', [
                 'error' => $e->getMessage(),
                 'file' => $e->getFile(),
                 'line' => $e->getLine(),
                 'trace' => $e->getTraceAsString(),
             ]);
     
             return response()->json([
                 'success' => false,
                 'message' => 'Failed to create project: ' . $e->getMessage(),
                 'errors'  => ['server' => [$e->getMessage()]],
             ], 500);
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



