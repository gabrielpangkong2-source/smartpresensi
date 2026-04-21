</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.getElementById('appSidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        var toggle = document.getElementById('mobileMenuToggle');

        if (!sidebar || !backdrop || !toggle) {
            return;
        }

        function closeSidebar() {
            sidebar.classList.remove('mobile-open');
            backdrop.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        }

        function openSidebar() {
            sidebar.classList.add('mobile-open');
            backdrop.classList.add('show');
            document.body.classList.add('sidebar-open');
        }

        toggle.addEventListener('click', function() {
            if (sidebar.classList.contains('mobile-open')) {
                closeSidebar();
                return;
            }

            openSidebar();
        });

        backdrop.addEventListener('click', closeSidebar);

        Array.prototype.forEach.call(document.querySelectorAll('#appSidebar .nav-link'), function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    });
</script>
</body>
</html>
