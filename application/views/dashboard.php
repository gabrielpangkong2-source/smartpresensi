<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon" style="background:#dbeafe; color:#1a56db;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $total_murid ?></div>
                    <div class="stat-label">Total User Terdaftar</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon" style="background:#dcfce7; color:#16a34a;">
                    <i class="bi bi-clipboard-check-fill"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $total_absensi ?></div>
                    <div class="stat-label">Presensi Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon" style="background:#fee2e2; color:#dc2626;">
                    <i class="bi bi-alarm-fill"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $total_telat ?></div>
                    <div class="stat-label">User Telat Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon" style="background:#fef3c7; color:#d97706;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $total_invalid ?></div>
                    <div class="stat-label">Kartu Invalid Pending</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card data-card h-100">
            <div class="card-header">
                <h6><i class="bi bi-clock-history me-2"></i>Aturan Jam Presensi</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <div style="font-size:13px; color:#6b7280; font-weight:600;"><?= $active_class ? 'Jam masuk kelas aktif hari ini' : 'Jam presensi default' ?></div>
                        <div style="font-size:28px; font-weight:800; color:#111827;"><?= htmlspecialchars($jam_masuk ? $jam_masuk : '07:00') ?></div>
                        <?php if ($active_class): ?>
                            <div style="margin-top:8px; font-size:13px; font-weight:600; color:#475569;">
                                <?= htmlspecialchars($active_class->kelas) ?> | <?= htmlspecialchars($active_class->mata_kuliah) ?> | <?= htmlspecialchars($active_class->ruangan) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-primary" style="font-size:12px; padding:8px 12px;"><?= $active_class ? 'Kode Kelas aktif' : 'Pengaturan aktif' ?></span>
                </div>
                <hr>
                <p class="mb-2" style="font-size:13px; font-weight:600; color:#475569;">
                    Scan RFID pada atau sebelum jam tersebut akan dicatat sebagai <strong>hadir</strong>.
                </p>
                <p class="mb-0" style="font-size:13px; font-weight:600; color:#475569;">
                    <?= $active_class ? 'Selama kelas aktif dipilih di menu Kelola Kelas, presensi hari ini akan mengikuti jam kelas tersebut.' : 'Jika belum ada kelas aktif yang dipilih, sistem memakai pengaturan default ini untuk menentukan hadir dan telat.' ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card data-card h-100">
            <div class="card-header">
                <h6><i class="bi bi-graph-up-arrow me-2"></i>Ringkasan Hari Ini</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px; font-weight:600; color:#475569;">Status hadir</span>
                    <span class="badge badge-hadir"><?= $total_hadir ?> user</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px; font-weight:600; color:#475569;">Status telat</span>
                    <span class="badge badge-telat"><?= $total_telat ?> user</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span style="font-size:13px; font-weight:600; color:#475569;">Kartu belum terdaftar</span>
                    <span class="badge badge-invalid"><?= $total_invalid ?> data</span>
                </div>
                <a href="<?= site_url('admin/absensi') ?>" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-clipboard-check-fill me-1"></i>Kelola User dan Presensi
                </a>
                <a href="<?= site_url('admin/invalid_user') ?>" class="btn btn-outline-warning w-100">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Lihat Kartu Invalid
                </a>
            </div>
        </div>
    </div>
</div>
