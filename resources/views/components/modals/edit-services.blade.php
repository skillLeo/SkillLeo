<x-modals.base-modal id="editServicesModal" title="Services I Offer" size="md">
    <form id="servicesForm" method="POST" action="#">
        @csrf
        @method('PUT')

        <div class="service-input-section">
            <div class="input-row">
                <input 
                    type="text" 
                    id="serviceInput" 
                    class="form-input" 
                    placeholder="e.g., Web Development, UI/UX Design..."
                    maxlength="60"
                >
                <button type="button" class="btn-add-service" onclick="addService()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Add
                </button>
            </div>
            <p class="form-hint">List services you provide to clients</p>
        </div>

        <div class="services-display" id="servicesDisplay">
            <div class="services-empty" id="servicesEmpty">No services added yet</div>
            <div class="services-list" id="servicesList"></div>
        </div>

        <input type="hidden" name="services" id="servicesData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editServicesModal')">Cancel</button>
        <button type="submit" form="servicesForm" class="btn-modal btn-save">Save</button>
    </x-slot:footer>
</x-modals.base-modal>

<style>
.service-input-section { margin-bottom: var(--space-lg); }

.input-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 12px;
    margin-bottom: 8px;
}

.btn-add-service {
    padding: 10px 24px;
    background: var(--accent);
    color: var(--btn-text-primary);
    border: none;
    border-radius: var(--radius);
    font-weight: var(--fw-semibold);
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-add-service:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

.services-display {
    min-height: 160px;
    padding: var(--space-md);
    border: 1px dashed var(--border);
    border-radius: var(--radius);
    background: var(--apc-bg);
}

.services-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 140px;
    color: var(--text-muted);
    font-size: var(--fs-subtle);
}

.services-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.service-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    transition: all 0.2s ease;
}

.service-item:hover {
    border-color: var(--accent);
}

.service-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    color: var(--success);
}

.service-text {
    flex: 1;
    color: var(--text-body);
    font-size: var(--fs-body);
}

.service-remove {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: 50%;
    cursor: pointer;
    font-size: 20px;
    transition: all 0.2s ease;
}

.service-remove:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
}

@media (max-width: 640px) {
    .input-row {
        grid-template-columns: 1fr;
    }
    .btn-add-service {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
let services = [];

function addService() {
    const input = document.getElementById('serviceInput');
    const text = input.value.trim();
    
    if (!text) {
        input.focus();
        return;
    }
    
    if (services.includes(text)) {
        alert('Service already added');
        input.value = '';
        input.focus();
        return;
    }
    
    services.push(text);
    input.value = '';
    input.focus();
    renderServices();
}

function removeService(index) {
    services.splice(index, 1);
    renderServices();
}

function renderServices() {
    const empty = document.getElementById('servicesEmpty');
    const list = document.getElementById('servicesList');
    const data = document.getElementById('servicesData');
    
    if (services.length === 0) {
        empty.style.display = 'flex';
        list.style.display = 'none';
    } else {
        empty.style.display = 'none';
        list.style.display = 'flex';
        list.innerHTML = services.map((service, i) => `
            <div class="service-item">
                <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
                <span class="service-text">${service}</span>
                <button type="button" class="service-remove" onclick="removeService(${i})">×</button>
            </div>
        `).join('');
    }
    
    data.value = JSON.stringify(services);
}

document.getElementById('serviceInput')?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        addService();
    }
});

renderServices();
</script>