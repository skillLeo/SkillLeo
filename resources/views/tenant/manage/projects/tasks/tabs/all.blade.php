{{-- resources/views/tenant/manage/projects/tasks/tabs/all.blade.php --}}

 

<div class="tasks-grid-container">
    <div class="tasks-grid">
        @foreach($tasks as $task)
            @include('tenant.manage.projects.tasks.components.task-grid-card', ['task' => $task])
        @endforeach
    </div>
</div>

<style>
.tasks-grid-container {
    width: 100%;
}

.tasks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
    width: 100%;
}

@media (max-width: 768px) {
    .tasks-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

@media (min-width: 1400px) {
    .tasks-grid {
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    }
}
</style>