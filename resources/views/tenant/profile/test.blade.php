{{-- but due. to thsi. js. sorkign. working oaky. but filter. not. working on. all --}}
      <script>
                    (function() {
                        'use strict';

                        // All user skills for mapping
                        const allSkills = @json($userSkillsForFilters ?? []);
                        const currentSortOrder = @json($sortOrder ?? 'position');

                        console.log('📊 Skills loaded:', allSkills);
                        console.log('📊 Current sort order:', currentSortOrder);

                        // ============================================
                        // ✅ FILTER PORTFOLIOS BY SKILL
                        // ============================================
                        window.filterPortfoliosBySkill = function(skillSlug, button) {
                            console.log('🔍 Filtering by skill:', skillSlug);

                            const items = document.querySelectorAll('.pps-project-item');
                            const buttons = document.querySelectorAll('.pps-filter-chip');

                            console.log('📦 Found', items.length, 'projects');

                            // Update active state
                            buttons.forEach(btn => btn.classList.remove('active'));
                            if (button) button.classList.add('active');

                            // Filter items
                            let visibleCount = 0;
                            items.forEach(item => {
                                const itemSkills = item.dataset.skills ? item.dataset.skills.split(',').filter(s => s
                                    .trim()) : [];
                                console.log('Project skills:', itemSkills);

                                const shouldShow = skillSlug === 'all' || itemSkills.includes(skillSlug);

                                if (shouldShow) {
                                    item.style.display = '';
                                    visibleCount++;
                                } else {
                                    item.style.display = 'none';
                                }
                            });

                            console.log('✅ Visible projects:', visibleCount);
                        };

                        // ============================================
                        // ✅ OPEN OPTIONS MODAL
                        // ============================================
                        window.openPortfolioOptions = function() {
                            const modal = document.getElementById('portfolioOptionsModal');
                            if (modal) {
                                modal.classList.add('active');
                                document.body.style.overflow = 'hidden';
                            }
                        };

                        // ============================================
                        // ✅ CLOSE OPTIONS MODAL
                        // ============================================
                        window.closePortfolioOptions = function(event) {
                            // Don't close if clicking inside modal content
                            if (event && event.target.classList.contains('pps-modal')) return;

                            const modal = document.getElementById('portfolioOptionsModal');
                            if (modal) {
                                modal.classList.remove('active');
                                document.body.style.overflow = '';
                            }
                        };

                        // ============================================
                        // ✅ TOGGLE SKILL SELECTION IN MODAL
                        // ============================================
                        window.toggleSkillSelection = function(skillSlug) {
                            const selectedList = document.getElementById('selectedSkillsList');
                            const availableList = document.getElementById('availableSkillsList');
                            const countEl = document.getElementById('selectedSkillCount');

                            if (!selectedList || !availableList) {
                                console.error('Lists not found');
                                return;
                            }

                            // Find the skill element
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
                                // Move to available
                                availableList.appendChild(skillElement);
                                skillElement.classList.remove('selected');
                                skillElement.querySelector('button').innerHTML = `
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                       `;
                            } else {
                                // Check max limit
                                if (currentCount >= 6) {
                                    alert('You can select maximum 6 skills for quick filtering');
                                    return;
                                }

                                // Move to selected
                                selectedList.appendChild(skillElement);
                                skillElement.classList.add('selected');
                                skillElement.querySelector('button').innerHTML = `
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                `;
                            }

                            // Update count
                            if (countEl) {
                                countEl.textContent = selectedList.querySelectorAll('.pps-skill-chip').length;
                            }
                        };

                        // ============================================
                        // ✅ SAVE PORTFOLIO OPTIONS (SKILLS + SORT)
                        // ============================================
                        window.savePortfolioOptions = async function() {
                            const selectedList = document.getElementById('selectedSkillsList');
                            const sortOrder = document.querySelector('input[name="sortOrder"]:checked')?.value ||
                                'position';

                            let selectedSkills = [];
                            if (selectedList) {
                                selectedSkills = Array.from(selectedList.querySelectorAll('.pps-skill-chip'))
                                    .map(el => el.dataset.skill)
                                    .filter(s => s); // Remove empty values
                            }

                            console.log('💾 Saving preferences...');
                            console.log('Selected skills:', selectedSkills);
                            console.log('Sort order:', sortOrder);

                            // Validation
                            if (allSkills.length > 0 && selectedSkills.length === 0) {
                                alert('Please select at least one skill for filtering');
                                return;
                            }

                            // Show loading state
                            const saveBtn = document.querySelector('.pps-btn-primary');
                            const originalText = saveBtn.innerHTML;
                            saveBtn.disabled = true;
                            saveBtn.innerHTML = 'Saving...';

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
                                    // Success - reload page to apply new sorting
                                    window.location.reload();
                                } else {
                                    alert('Failed to save preferences: ' + (data.message || 'Unknown error'));
                                    saveBtn.disabled = false;
                                    saveBtn.innerHTML = originalText;
                                }
                            } catch (error) {
                                console.error('❌ Error saving preferences:', error);
                                alert('Failed to save preferences. Please check console for details.');
                                saveBtn.disabled = false;
                                saveBtn.innerHTML = originalText;
                            }
                        };

                        // ============================================
                        // ✅ SHOW ALL PROJECTS
                        // ============================================
                        window.showAllPortfolioProjects = function() {
                            const items = document.querySelectorAll('.pps-project-item');
                            items.forEach(item => {
                                item.style.display = '';
                            });

                            // Reset active button
                            const buttons = document.querySelectorAll('.pps-filter-chip');
                            buttons.forEach(btn => btn.classList.remove('active'));

                            const allBtn = document.querySelector('.pps-filter-chip[data-skill="all"]');
                            if (allBtn) allBtn.classList.add('active');
                        };

                        // ============================================
                        // ✅ CLOSE MODAL ON ESC KEY
                        // ============================================
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') {
                                closePortfolioOptions();
                            }
                        });

                        // ============================================
                        // ✅ INITIALIZE: Show all projects on page load
                        // ============================================
                        document.addEventListener('DOMContentLoaded', function() {
                            console.log('🚀 Initializing portfolio section...');

                            // Ensure all projects are visible initially
                            const items = document.querySelectorAll('.pps-project-item');
                            console.log('📦 Found', items.length, 'projects');

                            items.forEach((item, index) => {
                                item.style.display = '';
                                console.log(`Project ${index + 1} data-skills:`, item.dataset.skills);
                            });

                            console.log('✅ Portfolio section initialized');
                        });
                    })();
                </script>