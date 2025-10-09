<x-modals.base-modal id="editPortfolioModal" title="Add Project" size="lg">
    <form id="portfolioForm" method="POST" action="#" enctype="multipart/form-data">
        @csrf

        <x-form.input 
            name="title"
            label="Project Title"
            placeholder="e.g., E-commerce Platform"
            required
        />

        <x-form.select 
            name="category"
            label="Category"
            :options="[
                'web' => 'Web Development',
                'mobile' => 'Mobile App',
                'design' => 'UI/UX Design',
                'other' => 'Other'
            ]"
            required
        />

        <x-form.textarea 
            name="description"
            label="Description"
            placeholder="Describe your project, what you built, technologies used, and outcomes..."
            rows="6"
            maxlength="1000"
            :showCharCount="true"
            required
        />

        <x-form.input 
            name="technologies"
            label="Technologies Used"
            placeholder="e.g., React, Laravel, PostgreSQL"
        />

        <x-form.input 
            name="url"
            type="url"
            label="Project URL (Optional)"
            placeholder="https://yourproject.com"
        />

        <div class="form-group">
            <label class="form-label">Project Image</label>
            <div class="file-upload">
                <input type="file" name="image" id="projectImage" accept="image/*" hidden>
                <label for="projectImage" class="file-upload-label">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    <span>Choose image or drag here</span>
                </label>
                <div class="file-preview" id="imagePreview"></div>
            </div>
        </div>

        <x-form.input 
            name="client"
            label="Client/Company (Optional)"
            placeholder="e.g., Acme Corp"
        />

        <div class="date-row">
            <x-form.input 
                name="start_date"
                type="date"
                label="Start Date"
            />

            <x-form.input 
                name="end_date"
                type="date"
                label="Completion Date"
            />
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editPortfolioModal')">Cancel</button>
        <button type="submit" form="portfolioForm" class="btn-modal btn-save">Save Project</button>
    </x-slot:footer>
</x-modals.base-modal>

<style>
.file-upload {
    margin-top: var(--space-sm);
}

.file-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
    padding: var(--space-xl);
    border: 2px dashed var(--border);
    border-radius: var(--radius);
    background: var(--apc-bg);
    cursor: pointer;
    transition: all 0.2s ease;
}

.file-upload-label:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.file-upload-label span {
    font-size: var(--fs-body);
    color: var(--text-muted);
}

.file-preview {
    margin-top: var(--space-md);
}

.file-preview img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius);
}
</style>

<script>
document.getElementById('projectImage')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>