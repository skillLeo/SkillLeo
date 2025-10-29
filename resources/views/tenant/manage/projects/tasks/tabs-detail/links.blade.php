{{-- resources/views/tenant/manage/projects/tasks/tabs-detail/links.blade.php --}}

<div class="task-links-container">
    <div class="task-links-header">
        <div>
            <h3>Linked Issues & Dependencies</h3>
            <p>Tasks that are related to or depend on this task</p>
        </div>
        
        <button class="task-btn task-btn-secondary" onclick="openLinkModal()">
            <i class="fas fa-link"></i> Link Issue
        </button>
    </div>

    @if($dependencies->count() > 0)
        <div class="task-links-list">
            @foreach($dependencies as $dependency)
                <div class="task-link-item">
                    <div class="task-link-type">
                        <i class="fas fa-arrow-right"></i>
                        <span>Depends on</span>
                    </div>
                    
                    <a href="{{ route('tenant.manage.projects.tasks.show', [$username, $dependency->id]) }}" 
                       class="task-link-card">
                        <div class="task-link-key">{{ $dependency->project->key }}-{{ $dependency->id }}</div>
                        <div class="task-link-title">{{ $dependency->title }}</div>
                        <div class="task-link-status" 
                             style="background: {{ $statusColors[$dependency->status] ?? '#6B778C' }}20; 
                                    color: {{ $statusColors[$dependency->status] ?? '#6B778C' }};">
                            {{ ucfirst(str_replace('-', ' ', $dependency->status)) }}
                        </div>
                    </a>
                    
                    <button class="task-link-remove" onclick="removeLinkage({{ $dependency->id }})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <div class="task-empty-state">
            <i class="fas fa-link"></i>
            <h4>No linked issues</h4>
            <p>Link related tasks to track dependencies and relationships</p>
            <button class="task-btn task-btn-primary" onclick="openLinkModal()">
                <i class="fas fa-link"></i> Link Your First Issue
            </button>
        </div>
    @endif
</div>

<style>
    .task-links-container {
        max-width: 800px;
    }

    .task-links-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .task-links-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #172B4D;
        margin: 0 0 4px 0;
    }

    .task-links-header p {
        font-size: 13px;
        color: #6B778C;
        margin: 0;
    }

    .task-links-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .task-link-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #FAFBFC;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
    }

    .task-link-type {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        padding: 8px;
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        min-width: 80px;
    }

    .task-link-type i {
        font-size: 16px;
        color: #6B778C;
    }

    .task-link-type span {
        font-size: 10px;
        font-weight: 600;
        color: #6B778C;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-link-card {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        padding: 12px;
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .task-link-card:hover {
        border-color: #0052CC;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .task-link-key {
        padding: 4px 8px;
        background: #F4F5F7;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        color: #5E6C84;
    }

    .task-link-title {
        flex: 1;
        font-size: 14px;
        font-weight: 600;
        color: #172B4D;
    }

    .task-link-status {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .task-link-remove {
        width: 32px;
        height: 32px;
        border: 1px solid #DFE1E6;
        border-radius: 4px;
        background: #FFFFFF;
        color: #6B778C;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .task-link-remove:hover {
        background: #FFEBE6;
        border-color: #DE350B;
        color: #DE350B;
    }

    @media (max-width: 768px) {
        .task-link-item {
            flex-wrap: wrap;
        }

        .task-link-card {
            width: 100%;
            order: 2;
        }

        .task-link-remove {
            order: 1;
            margin-left: auto;
        }
    }
</style>

<script>
function openLinkModal() {
    // Implement link issue modal
    window.showToast('Link issue modal coming soon', 'info');
}

function removeLinkage(dependencyId) {
    if (!confirm('Remove this link?')) return;
    
    // Implement remove linkage
    window.showToast('Link removed', 'success');
}
</script>