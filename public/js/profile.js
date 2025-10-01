// Enhanced Portfolio Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Filter tabs functionality
    document.querySelectorAll(".filter-tab").forEach((tab) => {
        tab.addEventListener("click", function () {
            // Remove active class from all tabs
            document.querySelectorAll(".filter-tab").forEach((t) => t.classList.remove("active"));
            
            // Add active class to clicked tab
            this.classList.add("active");
            
            // Get filter value
            const filter = this.getAttribute('data-filter');
            
            // Filter portfolio cards (if you have category data)
            filterPortfolioCards(filter);
        });
    });

    // Skills progress bar animations
    function animateProgressBars() {
        const progressBars = document.querySelectorAll(".progress-bar-fill");
        progressBars.forEach((bar) => {
            const rect = bar.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                const width = bar.getAttribute('data-width') || 
                            bar.parentElement.parentElement.querySelector(".skill-percentage")?.textContent;
                if (width) {
                    bar.style.width = width;
                }
            }
        });
    }

    // Skills hover effects
    document.querySelectorAll(".skill-progress").forEach((item) => {
        item.addEventListener("mouseenter", function () {
            const icon = this.querySelector(".skill-progress-icon");
            if (icon) {
                icon.style.transform = "translateY(-50%) scale(1.1)";
            }
        });
        
        item.addEventListener("mouseleave", function () {
            const icon = this.querySelector(".skill-progress-icon");
            if (icon) {
                icon.style.transform = "translateY(-50%) scale(1)";
            }
        });
    });

    // Initialize animations
    window.addEventListener("scroll", animateProgressBars);
    window.addEventListener("load", animateProgressBars);
    
    // Trigger animations on page load
    setTimeout(animateProgressBars, 500);
});

// Portfolio filtering function
function filterPortfolioCards(filter) {
    const cards = document.querySelectorAll('.portfolio-card');
    
    cards.forEach(card => {
        if (filter === 'all') {
            card.style.display = 'block';
        } else {
            // You can add data attributes to cards for filtering
            // For now, show all cards
            card.style.display = 'block';
        }
    });
}

// Portfolio view details function
function viewProject(id) {
    // You can implement modal or redirect logic here
    console.log('Viewing project:', id);
    // Example: window.location.href = `/portfolio/${id}`;
}

// AI Profile Creator functionality
document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.querySelector('.generate-profile-btn');
    const uploadBtn = document.querySelector('.upload-btn');
    const textarea = document.querySelector('.describe-textarea');

    if (generateBtn) {
        generateBtn.addEventListener('click', function() {
            const description = textarea?.value.trim();
            if (description) {
                // Simulate AI generation
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = 'Generate Profile';
                    this.disabled = false;
                    alert('AI Profile generation feature coming soon!');
                }, 2000);
            } else {
                alert('Please describe yourself or upload a CV first.');
            }
        });
    }

    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            // Create file input
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.pdf,.doc,.docx';
            input.click();
            
            input.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    alert(`File "${file.name}" selected. AI processing feature coming soon!`);
                }
            };
        });
    }
});