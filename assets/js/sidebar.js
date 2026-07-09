document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');

    if (!sidebar || !toggleBtn) return;

    let overlay = document.querySelector('.sidebar-overlay');

    if (!overlay) {

        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);

    }

    toggleBtn.addEventListener('click', function () {

        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');

    });

    overlay.addEventListener('click', function () {

        sidebar.classList.remove('show');
        overlay.classList.remove('show');

    });

});