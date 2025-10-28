<?php

namespace App\Services\Projects;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\ProjectNote;
use App\Models\ProjectMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreateProjectService
{
    /**
     * @param  array $data validated request data
     * @param  User  $workspaceOwner tenant owner
     * @param  User  $actor          user creating the project
     * @return Project
     * @throws Throwable
     */
    public function execute(array $data, User $workspaceOwner, User $actor): Project
    {
        return DB::transaction(function () use ($data, $workspaceOwner, $actor) {
            
            Log::info('CreateProjectService: Starting project creation', [
                'workspace_owner_id' => $workspaceOwner->id,
                'actor_id' => $actor->id,
                'project_name' => $data['name'] ?? 'N/A',
            ]);

            // 1) Optional client attach/create
            $clientId = null;
            if (!empty($data['client_user_id'])) {
                try {
                    /** @var User $clientUser */
                    $clientUser = User::findOrFail($data['client_user_id']);

                    if ($clientUser->account_status !== 'client') {
                        throw ValidationException::withMessages([
                            'client_user_id' => ['Selected user is not registered as a client.'],
                        ]);
                    }

                    $client = Client::create([
                        'user_id'              => $workspaceOwner->id,
                        'client_user_id'       => $data['client_user_id'],
                        'company'              => $data['client_company']         ?? null,
                        'phone'                => $data['client_phone']           ?? null,
                        'order_value'          => $data['order_value']            ?? null,
                        'currency'             => $data['currency']               ?? 'PKR',
                        'payment_terms'        => $data['payment_terms']          ?? 'milestone',
                        'portal_access'        => isset($data['portal_access']) ? (bool)$data['portal_access'] : true,
                        'can_comment'          => isset($data['can_comment'])  ? (bool)$data['can_comment']  : false,
                        'special_requirements' => $data['special_requirements']  ?? null,
                    ]);

                    $clientId = $client->id;
                    Log::info('CreateProjectService: Client created', ['client_id' => $clientId]);
                    
                } catch (Throwable $e) {
                    Log::error('CreateProjectService: Client creation failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // 2) Create Project
            try {
                $project = new Project();
                $project->user_id       = $workspaceOwner->id;
                $project->name          = $data['name'];
                $project->key           = $data['key'];
                $project->type          = $data['type'];
                $project->category      = $data['category']        ?? null;
                $project->priority      = $data['priority'];
                $project->description   = $data['description']     ?? null;
                $project->start_date    = $data['start_date'];
                $project->due_date      = $data['due_date'];
                $project->status        = 'active';
                $project->budget        = $data['budget']          ?? null;
                $project->currency      = $data['currency']        ?? 'PKR';
                $project->estimated_hours = $data['estimated_hours'] ?? null;
                
                // If your Project has a json `flags` column:
                if (isset($data['flags']) && is_array($data['flags'])) {
                    $project->flags = json_encode($data['flags']);
                }
                
                if ($clientId) {
                    $project->client_id = $clientId;
                }

                $project->save();
                
                Log::info('CreateProjectService: Project created', ['project_id' => $project->id]);
                
            } catch (Throwable $e) {
                Log::error('CreateProjectService: Project creation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            // 3) Tasks + Subtasks + assignment
            $taskIdMap = []; // index => created Task id
            if (!empty($data['tasks']) && is_array($data['tasks'])) {
                try {
                    foreach ($data['tasks'] as $idx => $t) {
                        $task = new Task();
                        $task->project_id      = $project->id;
                        $task->title           = $t['title'];
                        $task->notes           = $t['notes']           ?? null;
                        $task->priority        = $t['priority']        ?? 'medium';
                        $task->due_date        = $t['due_date']        ?? null;
                        $task->estimated_hours = $t['estimated_hours'] ?? null;
                        $task->story_points    = $t['story_points']    ?? 0;
                        $task->reporter_id     = $actor->id;
                        $task->assigned_to     = $t['assigned_to']     ?? null;
                        $task->status          = 'todo';
                        $task->save();
                        
                        

                        $taskIdMap[$idx] = $task->id;

                        // Subtasks
                        if (!empty($t['subtasks']) && is_array($t['subtasks'])) {
                            foreach ($t['subtasks'] as $st) {
                                $sub = new Subtask();
                                $sub->task_id   = $task->id;
                                $sub->title     = $st['title'];
                                $sub->completed = !empty($st['completed']) ? (bool)$st['completed'] : false;
                                $sub->save();
                            }
                        }
                    }
                    
                    Log::info('CreateProjectService: Tasks created', ['count' => count($taskIdMap)]);
                    
                } catch (Throwable $e) {
                    Log::error('CreateProjectService: Task creation failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // 4) Team pivot (if your Project has team() belongsToMany relation)
            if (!empty($data['team']) && is_array($data['team'])) {
                try {
                    if (method_exists($project, 'team')) {
                        $syncData = [];
                        foreach ($data['team'] as $row) {
                            if (!empty($row['user_id'])) {
                                $syncData[$row['user_id']] = [
                                    'role'       => $row['role']       ?? null,
                                    'tech_stack' => $row['tech_stack'] ?? null,
                                    'position'   => $row['position']   ?? null,
                                ];
                            }
                        }
                        if (!empty($syncData)) {
                            $project->team()->syncWithoutDetaching($syncData);
                        }
                    }
                    
                    Log::info('CreateProjectService: Team members attached', ['count' => count($data['team'])]);
                    
                } catch (Throwable $e) {
                    Log::error('CreateProjectService: Team attachment failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // 5) Notes
            if (!empty($data['notes']) && is_array($data['notes'])) {
                try {
                    foreach ($data['notes'] as $n) {
                        if (!empty($n['body'])) {
                            $note = new ProjectNote();
                            $note->project_id  = $project->id;
                            $note->user_id     = $actor->id;
                            $note->body        = $n['body'];
                            $note->is_internal = !empty($n['is_internal']) ? (bool)$n['is_internal'] : false;
                            $note->pinned      = !empty($n['pinned'])      ? (bool)$n['pinned']      : false;
                            $note->save();
                        }
                    }
                    
                    Log::info('CreateProjectService: Notes created', ['count' => count($data['notes'])]);
                    
                } catch (Throwable $e) {
                    Log::error('CreateProjectService: Note creation failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // 6) Media uploads
         // 6) Media uploads
// 6) Media uploads
if (!empty($data['media']) && is_array($data['media'])) {
    try {
        foreach ($data['media'] as $m) {
            $file = $m['file'] ?? null;

            if ($file instanceof \Illuminate\Http\UploadedFile) {

                $dir  = "projects/{$project->id}/media";
                $ext  = $file->getClientOriginalExtension();
                $name = uniqid('media_') . '.' . $ext;

                // store the physical file
                $path = $file->storeAs($dir, $name, ['disk' => 'public']);

                // figure out "type" for DB
                $mime = $file->getClientMimeType();    // e.g. "image/jpeg"
                $top  = explode('/', $mime)[0] ?? '';  // "image", "video", "application", ...

                // map mime -> type column
                switch ($top) {
                    case 'image':
                        $detectedType = 'image';
                        break;
                    case 'video':
                        $detectedType = 'video';
                        break;
                    case 'audio':
                        $detectedType = 'audio';
                        break;
                    default:
                        // PDFs, docs, zips, etc end up here
                        $detectedType = 'file';
                        break;
                }

                $media = new \App\Models\ProjectMedia();
                $media->project_id    = $project->id;
                $media->uploaded_by   = $actor->id;
                $media->type          = $detectedType; // ✅ NOT NULL anymore
                $media->file_path     = $path;
                $media->original_name = $file->getClientOriginalName();
                $media->mime_type     = $mime;
                $media->size_bytes    = $file->getSize();
                $media->note          = $m['note']        ?? null;
                $media->visibility    = $m['visibility']  ?? 'internal';
                $media->sort_order    = (int)($m['sort_order'] ?? 0);

                $media->save();
            }
        }

        Log::info('CreateProjectService: Media files uploaded', [
            'count' => count($data['media'])
        ]);

    } catch (\Throwable $e) {
        Log::error('CreateProjectService: Media upload failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
}



            Log::info('CreateProjectService: Project creation completed successfully', [
                'project_id' => $project->id
            ]);

            return $project;
        });
    }
}