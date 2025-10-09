<x-modals.base-modal id="editExperienceModal" title="Add Experience" size="md">
    <form id="experienceForm" method="POST" action="#">
        @csrf

        <x-form.input 
            name="title"
            label="Title"
            placeholder="e.g., Full Stack Developer"
            required
        />

        <x-form.input 
            name="company"
            label="Company"
            placeholder="e.g., Acme Inc."
            required
        />

        <x-form.input 
            name="location"
            label="Location"
            placeholder="e.g., New York, USA"
        />

        <div class="date-row">
            <x-form.select 
                name="start_month"
                label="Start Date"
                :options="[
                    '01' => 'January', '02' => 'February', '03' => 'March',
                    '04' => 'April', '05' => 'May', '06' => 'June',
                    '07' => 'July', '08' => 'August', '09' => 'September',
                    '10' => 'October', '11' => 'November', '12' => 'December'
                ]"
                required
            />

            <x-form.select 
                name="start_year"
                label="Year"
                :options="array_combine(range(date('Y'), date('Y')-50), range(date('Y'), date('Y')-50))"
                required
            />
        </div>

        <div class="checkbox-row">
            <label class="checkbox-label">
                <input type="checkbox" name="current" id="currentlyWorking" onchange="toggleEndDate(this)">
                <span>I currently work here</span>
            </label>
        </div>

        <div class="date-row" id="endDateSection">
            <x-form.select 
                name="end_month"
                label="End Date"
                :options="[
                    '01' => 'January', '02' => 'February', '03' => 'March',
                    '04' => 'April', '05' => 'May', '06' => 'June',
                    '07' => 'July', '08' => 'August', '09' => 'September',
                    '10' => 'October', '11' => 'November', '12' => 'December'
                ]"
            />

            <x-form.select 
                name="end_year"
                label="Year"
                :options="array_combine(range(date('Y'), date('Y')-50), range(date('Y'), date('Y')-50))"
            />
        </div>

        <x-form.textarea 
            name="description"
            label="Description"
            placeholder="Describe your key responsibilities and achievements..."
            rows="6"
            maxlength="2000"
            :showCharCount="true"
        />
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editExperienceModal')">Cancel</button>
        <button type="submit" form="experienceForm" class="btn-modal btn-save">Save</button>
    </x-slot:footer>
</x-modals.base-modal>

<style>
.date-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
}

.checkbox-row {
    margin: var(--space-md) 0;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: var(--fs-body);
    color: var(--text-body);
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
    cursor: pointer;
}

@media (max-width: 640px) {
    .date-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function toggleEndDate(checkbox) {
    const endDateSection = document.getElementById('endDateSection');
    if (checkbox.checked) {
        endDateSection.style.display = 'none';
        endDateSection.querySelectorAll('select').forEach(select => {
            select.removeAttribute('required');
        });
    } else {
        endDateSection.style.display = 'grid';
        endDateSection.querySelectorAll('select').forEach(select => {
            select.setAttribute('required', 'required');
        });
    }
}
</script>