document.addEventListener('DOMContentLoaded', function () {
    // --- Système de tabs (files.php) ---
    const tabButtons = document.querySelectorAll('[data-target]');

    if (tabButtons.length) {
        const allPanels = Array.from(tabButtons)
            .map(btn => document.getElementById(btn.dataset.target))
            .filter(Boolean);

        tabButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const targetPanel = document.getElementById(targetId);
                if (!targetPanel) return;

                // Cacher tous les panels
                allPanels.forEach(function (panel) {
                    panel.classList.add('hidden');
                });

                // Afficher le panel cible
                targetPanel.classList.remove('hidden');

                // Mettre à jour l'état actif des boutons
                tabButtons.forEach(function (b) {
                    b.classList.remove('button-actif');
                });
                this.classList.add('button-actif');
            });
        });
    }


    // --- Burger boutique (baluch-dokka) ---
    const shopHamburger = document.getElementById('shop-hamburger');
    const shopSidebar   = document.getElementById('shop-sidebar');
    const shopOverlay   = document.getElementById('shop-sidebar-overlay');

    if (shopHamburger && shopSidebar && shopOverlay) {
        shopHamburger.addEventListener('click', function () {
            const isOpen = shopSidebar.classList.contains('open');
            shopSidebar.classList.toggle('open', !isOpen);
            shopOverlay.classList.toggle('active', !isOpen);
            shopHamburger.classList.toggle('open', !isOpen);
        });
        shopOverlay.addEventListener('click', function () {
            shopSidebar.classList.remove('open');
            shopOverlay.classList.remove('active');
            shopHamburger.classList.remove('open');
        });
    }

    const hamburger = document.getElementById('nav-hamburger');
    const sidebar   = document.querySelector('.left-menu');
    const overlay   = document.getElementById('sidebar-overlay');

    if (!hamburger || !sidebar || !overlay) return;

    function openSidebar() {
        sidebar.classList.add('sidebar-open');
        overlay.classList.add('active');
        hamburger.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('sidebar-open');
        overlay.classList.remove('active');
        hamburger.classList.remove('open');
        document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', function () {
        sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
});
