<aside class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <!-- Profile Card -->
        <x-dashboard.cards.profile-card />

        <!-- Manage Profile Section -->
        <x-dashboard.cards.nav-section 
            title="Manage Profile"
            :items="[
                ['icon' => 'fa-user', 'label' => 'Personal Info', 'active' => true],
                ['icon' => 'fa-code', 'label' => 'Skills'],
                ['icon' => 'fa-briefcase', 'label' => 'Portfolio'],
                ['icon' => 'fa-history', 'label' => 'Experience'],
                ['icon' => 'fa-graduation-cap', 'label' => 'Education'],
            ]"
        />

        <!-- Manage Projects Section -->
        <x-dashboard.cards.nav-section 
            title="Manage Projects"
            :items="[
                ['icon' => 'fa-project-diagram', 'label' => 'Projects'],
                ['icon' => 'fa-clipboard-list', 'label' => 'Orders'],
                ['icon' => 'fa-users', 'label' => 'Team Members'],
                ['icon' => 'fa-user-tie', 'label' => 'Clients'],
            ]"
        />

        <!-- Legal & Finance Section -->
        <x-dashboard.cards.nav-section 
            title="Legal & Finance"
            :items="[
                ['icon' => 'fa-file-invoice', 'label' => 'Invoices'],
                ['icon' => 'fa-file-contract', 'label' => 'Contracts'],
                ['icon' => 'fa-balance-scale', 'label' => 'Legal Docs'],
            ]"
        />

        <!-- Account Section -->
        <x-dashboard.cards.nav-section 
            title="Account"
            :items="[
                ['icon' => 'fa-cog', 'label' => 'Settings'],
                ['icon' => 'fa-user-circle', 'label' => 'Profile'],
                ['icon' => 'fa-sign-out-alt', 'label' => 'Logout'],
            ]"
        />

        <!-- Upgrade Card -->
        <x-dashboard.cards.upgrade-card />
    </div>
</aside>