<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card data-card mb-4">
    <div class="card-header">
        <h6><i class="bi bi-gear-fill me-2"></i>Pengaturan Jam Presensi</h6>
    </div>
    <div class="card-body">
        <form action="<?= site_url('admin/simpan_pengaturan') ?>" method="post">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Batas Jam Hadir</label>
                    <input type="time" name="jam_masuk" class="form-control" value="<?= $jam_masuk ?>" required>
                    <small class="text-muted" style="display:block; margin-top:8px; font-size:12px;">
                        Scan pada atau sebelum jam ini akan tercatat sebagai hadir. Scan setelah jam ini akan tercatat sebagai telat.
                    </small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Timezone Aplikasi</label>
                    <select name="timezone" class="form-select" required>
                        <?php foreach ($timezone_options as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>" <?= ($timezone === $value) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted" style="display:block; margin-top:8px; font-size:12px;">
                        Samakan dengan timezone/jam lokal laptop Anda agar status hadir dan telat akurat.
                    </small>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
