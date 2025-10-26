<!DOCTYPE html>
<html lang="en" style="font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;">
<head>
    <meta charset="UTF-8" />
    <title>Your Task Summary</title>
</head>
<body style="background-color:#f5f6f8; padding:24px; color:#1f2937; font-size:14px; line-height:1.5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:8px; border:1px solid #e5e7eb; overflow:hidden;">
        <tr>
            <td style="padding:24px 24px 16px 24px; background:#1f2937; color:#fff;">
                <div style="font-size:16px; font-weight:600; margin-bottom:4px;">
                    Hi {{ $user->name }},
                </div>
                <div style="font-size:13px; color:#d1d5db;">
                    Here's your current task summary.
                </div>
            </td>
        </tr>

        @if($customMessage)
        <tr>
            <td style="padding:16px 24px; background:#fffbea; border-bottom:1px solid #facc15;">
                <div style="font-size:13px; font-weight:600; color:#92400e; margin-bottom:4px;">
                    Message from your lead:
                </div>
                <div style="font-size:13px; color:#78350f;">
                    {{ $customMessage }}
                </div>
            </td>
        </tr>
        @endif

        {{-- Tasks assigned TO this user --}}
        <tr>
            <td style="padding:24px;">
                <div style="font-size:15px; font-weight:600; color:#111827; margin-bottom:8px;">
                    Tasks Assigned To You
                </div>

                @if($assignedTasks->count() === 0)
                    <div style="font-size:13px; color:#6b7280;">Nothing pending 🎉</div>
                @else
                    @foreach($assignedTasks->groupBy('project_id') as $projectId => $list)
                        @php $project = $list->first()->project; @endphp
                        <div style="border:1px solid #e5e7eb; border-radius:6px; margin-bottom:16px;">
                            <div style="padding:12px 16px; background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                <div style="font-size:13px; font-weight:600; color:#111827;">
                                    {{ $project?->name ?? 'Project' }}
                                </div>
                                <div style="font-size:12px; color:#6b7280;">
                                    {{ $list->count() }} task(s)
                                </div>
                            </div>

                            @foreach($list as $task)
                                <div style="padding:12px 16px; border-bottom:1px solid #f1f5f9;">
                                    <div style="font-size:13px; font-weight:500; color:#111827; margin-bottom:4px;">
                                        {{ $task->title }}
                                    </div>
                                    <div style="font-size:12px; color:#6b7280;">
                                        Status:
                                        <strong style="color:#111827;">{{ ucfirst(str_replace('_',' ', $task->status)) }}</strong>
                                        @if($task->due_date)
                                            • Due: <span style="color:#dc2626; font-weight:500;">
                                                {{ $task->due_date->format('M d, Y') }}
                                                @if($task->due_date->isPast() && $task->status !== 'done')
                                                    (Overdue)
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size:12px; color:#6b7280; margin-top:4px;">
                                        Assigned by:
                                        <strong style="color:#111827;">
                                            {{ $task->reporter?->name ?? '—' }}
                                        </strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>

        {{-- Tasks CREATED BY this user (you assigned these to other people) --}}
        <tr>
            <td style="padding:24px; border-top:1px solid #e5e7eb;">
                <div style="font-size:15px; font-weight:600; color:#111827; margin-bottom:8px;">
                    Tasks You Assigned To Other People
                </div>

                @if($createdTasks->count() === 0)
                    <div style="font-size:13px; color:#6b7280;">You haven't assigned any open tasks to others.</div>
                @else
                    @foreach($createdTasks->groupBy('project_id') as $projectId => $list)
                        @php $project = $list->first()->project; @endphp
                        <div style="border:1px solid #e5e7eb; border-radius:6px; margin-bottom:16px;">
                            <div style="padding:12px 16px; background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                <div style="font-size:13px; font-weight:600; color:#111827;">
                                    {{ $project?->name ?? 'Project' }}
                                </div>
                                <div style="font-size:12px; color:#6b7280;">
                                    {{ $list->count() }} task(s)
                                </div>
                            </div>

                            @foreach($list as $task)
                                <div style="padding:12px 16px; border-bottom:1px solid #f1f5f9;">
                                    <div style="font-size:13px; font-weight:500; color:#111827; margin-bottom:4px;">
                                        {{ $task->title }}
                                    </div>
                                    <div style="font-size:12px; color:#6b7280;">
                                        Owner:
                                        <strong style="color:#111827;">
                                            {{ $task->assignee?->name ?? 'Unassigned' }}
                                        </strong>
                                        • Status:
                                        <strong style="color:#111827;">
                                            {{ ucfirst(str_replace('_',' ', $task->status)) }}
                                        </strong>
                                        @if($task->due_date)
                                            • Due: <span style="color:#111827;">
                                                {{ $task->due_date->format('M d, Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size:12px; color:#6b7280; margin-top:4px;">
                                        Last update:
                                        <strong style="color:#111827;">
                                            {{ $task->updated_at->diffForHumans() }}
                                        </strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>

        <tr>
            <td style="padding:24px; background:#f9fafb; font-size:12px; color:#9ca3af; text-align:center;">
                You’re receiving this summary so you can stay unblocked and on schedule.
            </td>
        </tr>
    </table>
</body>
</html>
