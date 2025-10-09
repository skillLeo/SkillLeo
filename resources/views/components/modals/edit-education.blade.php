<x-modals.base-modal id="editEducationModal" title="Add Education" size="md">
    <form id="educationForm" method="POST" action="#">
        @csrf

        <x-form.input 
            name="degree"
            label="Degree"
            placeholder="e.g., Bachelor of Science"
            required
        />

        <x-form.input 
            name="field"
            label="Field of Study"
            placeholder="e.g., Computer Science"
            required
        />

        <x-form.input 
            name="institution"
            label="School/University"
            placeholder="e.g., Stanford University"
            required
        />

        <x-form.input 
            name="location"
            label="Location"
            placeholder="e.g., Stanford, CA"
        />

        <div class="date-row">
            <x-form.input 
                name="start_year"
                type="number"
                label="Start Year"
                placeholder="2018"
                min="1950"
                :max="date('Y')"
                required
            />

            <x-form.input 
                name="end_year"
                type="number"
                label="End Year (or expected)"
                placeholder="2022"
                min="1950"
                :max="date('Y')+10"
                required
            />
        </div>

        <x-form.input 
            name="grade"
            label="Grade (Optional)"
            placeholder="e.g., 3.8 GPA or First Class"
        />

        <x-form.textarea 
            name="description"
            label="Description (Optional)"
            placeholder="Describe activities, awards, and relevant coursework..."
            rows="4"
            maxlength="1000"
        />
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editEducationModal')">Cancel</button>
        <button type="submit" form="educationForm" class="btn-modal btn-save">Save</button>
    </x-slot:footer>
</x-modals.base-modal>