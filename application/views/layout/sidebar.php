<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<div class="sidebar" id="appSidebar">
    <div class="brand">

        SMART PRESENSI KELAS
    </div>
    <nav>
        <a href="<?= site_url('admin') ?>" class="nav-link <?= ($active == 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="<?= site_url('admin/absensi') ?>" class="nav-link <?= ($active == 'absensi') ? 'active' : '' ?>">
            <i class="bi bi-clipboard-check-fill"></i> Presensi
        </a>
        <a href="<?= site_url('admin/kelola_kelas') ?>" class="nav-link <?= ($active == 'kelas') ? 'active' : '' ?>">
            <i class="bi bi-journal-bookmark-fill"></i> Kelola Kelas
        </a>
        <a href="<?= site_url('admin/invalid_user') ?>" class="nav-link <?= ($active == 'invalid') ? 'active' : '' ?>">
            <i class="bi bi-exclamation-triangle-fill"></i> Invalid User
        </a>
        <a href="<?= site_url('admin/laporan') ?>" class="nav-link <?= ($active == 'laporan') ? 'active' : '' ?>">
            <i class="bi bi-bar-chart-fill"></i> Laporan
        </a>
        <a href="<?= site_url('admin/pengaturan') ?>" class="nav-link <?= ($active == 'pengaturan') ? 'active' : '' ?>">
            <i class="bi bi-gear-fill"></i> Pengaturan
        </a>
        <a href="<?= site_url('auth/logout') ?>" class="nav-link" onclick="return confirm('Yakin ingin logout?')">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </nav>
</div>

<div class="main-content">
    <div class="top-navbar">
        <div class="top-navbar-left">
            <button type="button" class="mobile-menu-btn" id="mobileMenuToggle" aria-label="Buka menu">
                <i class="bi bi-list"></i>
            </button>
            <h5><?= $title ?></h5>
        </div>
        <span class="date-info"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y') ?></span>
    </div>
    <div class="content-area">