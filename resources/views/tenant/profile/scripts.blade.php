











































































































































































































































(function() {
    'use strict';

    // ============================================
    // CONFIGURATION
    // ============================================
    const LIMIT = {{ $LIMITS['projects'] ?? 2 }};
    const allSkills = @json($userSkillsForFilters ?? []);
    const currentSortOrder = @json($sortOrder ?? 'position');

    // DOM References
    const grid = document.getElementById('portfolioGrid');
    let store = document.getElementById('allPortfolioStore');

    // State
    let currentFilter = 'all';
    let allProjects = [];

    console.log('📊 Skills loaded:', allSkills);
    console.log('📊 Current sort order:', currentSortOrder);
    console.log('📦 Display limit:', LIMIT);

    // ============================================
    // UTILITY FUNCTIONS
    // ============================================

    function ensureStore() {
        if (!store) {
            store = document.createElement('div');
            store.id = 'allPortfolioStore';
            store.style.display = 'none';
            if (grid && grid.parentNode) {
                grid.parentNode.insertBefore(store, grid.nextSibling);
            }
        }
    }

    function getAllProjects() {
        // Collect from both grid and store
        const gridItems = Array.from(grid?.querySelectorAll('.pps-project-item') || []);
        const storeItems = Array.from(store?.querySelectorAll('.pps-project-item') || []);

        // Combine and deduplicate
        const allItems = [...gridItems, ...storeItems];
        const seen = new Set();
        return allItems.filter(el => {
            const id = el.dataset.projectId || el.innerHTML;
            if (seen.has(id)) return false;
            seen.add(id);
            return true;
        });
    }

    function moveAllToStore() {
        if (!store || !grid) return;
        allProjects.forEach(el => {
            if (el.parentNode !== store) {
                store.appendChild(el);
            }
        });
    }

    function filterProjects(skillSlug) {
        const slug = (skillSlug || 'all').trim();

        if (slug === 'all') {
            return [...allProjects];
        }

        return allProjects.filter(el => {
            const skills = (el.dataset.skills || '')
                .split(',')
                .map(s => s.trim())
                .filter(Boolean);
            return skills.includes(slug);
        });
    }

    function renderProjects(projects, limit = LIMIT) {
        if (!grid) return;

        grid.innerHTML = '';

        if (projects.length === 0) {
            grid.innerHTML = `
                <div class="pps-empty" style="padding:40px;text-align:center;grid-column:1/-1;">
                    <h3>No projects match this filter</h3>
                    <p>Try selecting a different skill.</p>
                </div>
            `;
            return;
        }

        const toShow = projects.slice(0, limit);
        toShow.forEach(el => {
            el.style.display = '';
            grid.appendChild(el);
        });

        // Keep remaining in store
        projects.slice(limit).forEach(el => {
            if (store) store.appendChild(el);
        });

        console.log(
        `✅ Rendered ${toShow.length} of ${projects.length} projects for filter: "${currentFilter}"`);
    }

    function updateActiveChip(button) {
        const buttons = document.querySelectorAll('.pps-filter-chip');
        buttons.forEach(btn => btn.classList.remove('active'));
        if (button) {
            button.classList.add('active');
        }
    }

    // ============================================
    // PUBLIC API FUNCTIONS
    // ============================================

    window.filterPortfoliosBySkill = function(skillSlug, button) {
        console.log('🔍 Filtering by skill:', skillSlug);

        currentFilter = skillSlug || 'all';
        updateActiveChip(button);

        // Move all to store first
        moveAllToStore();

        // Filter and render
        const filtered = filterProjects(currentFilter);
        renderProjects(filtered, LIMIT);
    };

    window.openPortfolioOptions = function() {
        const modal = document.getElementById('portfolioOptionsModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closePortfolioOptions = function(event) {
        if (event && event.target.classList.contains('pps-modal')) return;

        const modal = document.getElementById('portfolioOptionsModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    window.toggleSkillSelection = function(skillSlug) {
        const selectedList = document.getElementById('selectedSkillsList');
        const availableList = document.getElementById('availableSkillsList');
        const countEl = document.getElementById('selectedSkillCount');

        if (!selectedList || !availableList) {
            console.error('Lists not found');
            return;
        }

        const skillElements = document.querySelectorAll(`[data-skill="${skillSlug}"]`);
        let skillElement = null;

        skillElements.forEach(el => {
            if (el.classList.contains('pps-skill-chip')) {
                skillElement = el;
            }
        });

        if (!skillElement) {
            console.error('Skill element not found:', skillSlug);
            return;
        }

        const isSelected = skillElement.classList.contains('selected');
        const currentCount = selectedList.querySelectorAll('.pps-skill-chip').length;

        if (isSelected) {
            availableList.appendChild(skillElement);
            skillElement.classList.remove('selected');
            skillElement.querySelector('button').innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            `;
        } else {
            if (currentCount >= 6) {
                alert('You can select maximum 6 skills for quick filtering');
                return;
            }
            selectedList.appendChild(skillElement);
            skillElement.classList.add('selected');
            skillElement.querySelector('button').innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            `;
        }

        if (countEl) {
            countEl.textContent = selectedList.querySelectorAll('.pps-skill-chip').length;
        }
    };

    window.savePortfolioOptions = async function() {
        const selectedList = document.getElementById('selectedSkillsList');
        const sortOrder = document.querySelector('input[name="sortOrder"]:checked')?.value ||
        'position';

        let selectedSkills = [];
        if (selectedList) {
            selectedSkills = Array.from(selectedList.querySelectorAll('.pps-skill-chip'))
                .map(el => el.dataset.skill)
                .filter(s => s);
        }

        console.log('💾 Saving preferences...');
        console.log('Selected skills:', selectedSkills);
        console.log('Sort order:', sortOrder);

        if (allSkills.length > 0 && selectedSkills.length === 0) {
            alert('Please select at least one skill for filtering');
            return;
        }

        const saveBtn = document.querySelector('.pps-btn-primary');
        const originalText = saveBtn?.innerHTML;
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = 'Saving...';
        }

        try {
            const response = await fetch('{{ route('tenant.filter-preferences') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        ?.content || ''
                },
                body: JSON.stringify({
                    visible_skills: selectedSkills,
                    sort_order: sortOrder
                })
            });

            const data = await response.json();
            console.log('✅ Response:', data);

            if (data.success) {
                // Reload page to apply new sorting from backend
                window.location.reload();
            } else {
                alert('Failed to save preferences: ' + (data.message || 'Unknown error'));
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                }
            }
        } catch (error) {
            console.error('❌ Error saving preferences:', error);
            alert('Failed to save preferences. Please check console for details.');
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
        }
    };

    window.showAllPortfolioProjects = function() {
        // Check if modal exists and should be opened
        if (typeof openModal === 'function') {
            const modalEl = document.getElementById('seeAllProjectsModal');
            if (modalEl) {
                openModal('seeAllProjectsModal');
                return;
            }
        }

        // Otherwise show all in grid
        currentFilter = 'all';
        const allBtn = document.querySelector('.pps-filter-chip[data-skill="all"]');
        updateActiveChip(allBtn);

        moveAllToStore();
        const filtered = filterProjects('all');
        renderProjects(filtered, filtered.length); // Show all, no limit
    };

    // ============================================
    // EVENT LISTENERS
    // ============================================

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closePortfolioOptions();
        }
    });

    // ============================================
    // INITIALIZATION
    // ============================================

    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Initializing portfolio section...');

        if (!grid) {
            console.error('❌ Portfolio grid not found');
            return;
        }

        // Setup store
        ensureStore();

        // Collect all projects
        allProjects = getAllProjects();
        console.log('📦 Total projects found:', allProjects.length);

        // Move all to store
        moveAllToStore();

        // Render initial view (respects backend sorting)
        const filtered = filterProjects('all');
        renderProjects(filtered, LIMIT);

        // Set active chip
        const allBtn = document.querySelector('.pps-filter-chip[data-skill="all"]');
        if (allBtn) allBtn.classList.add('active');

        console.log('✅ Portfolio section initialized');
    });

})();

















        // Global modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Close modal on overlay click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                closeModal(e.target.id);
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal-overlay[style*="display: flex"]');
                openModals.forEach(modal => closeModal(modal.id));
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Handle all edit buttons
            document.querySelectorAll('.edit-card').forEach(button => {
                // Skip buttons that already have specific handlers
                if (button.id === 'desktopMenuBtn' || button.classList.contains('edit-profile-btn')) {
                    return;
                }

                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    const section = this.closest('section');
                    const card = this.closest('.card');

                    // ✅ Main content sections
                    if (section && section.classList.contains('hero-merged')) {
                        openModal('editProfileModal');
                    } else if (section && section.classList.contains('experience-section')) {
                        openModal('editExperienceModal');
                    } else if (section && section.classList.contains('portfolios-section')) {
                        openModal('editPortfolioModal');
                    } else if (section && section.classList.contains('skills-showcase')) {
                        // ✅ Skills Showcase - Opens FULL modal with both skills
                        openModal('editSkillsModal');
                    } else if (card) {
                        // ✅ Sidebar cards - Opens SPECIFIC modals
                        const titleEl = card.querySelector('.section-title, .portfolios-title');
                        const title = titleEl ? titleEl.textContent.trim() : '';

                        if (title === 'Top Skills') {
                            // ✅ Sidebar Top Skills - Opens technical skills ONLY
                            openModal('editTopSkillsModal');
                        } else if (title === 'Soft Skills') {
                            // ✅ Sidebar Soft Skills - Opens soft skills ONLY
                            openModal('editSoftSkillsModal');
                        } else if (title === 'Education') {
                            openModal('editEducationModal');
                        } else if (title === 'Language' || title === 'Languages') {
                            openModal('editLanguagesModal');
                        } else if (title === 'Why Choose Me?') {
                            openModal('editWhyChooseModal');
                        } else if (title === 'Services') {
                            openModal('editServicesModal');
                        }
                    }
                });
            });
        });
 








































