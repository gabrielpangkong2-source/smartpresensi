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

<?php if (empty($classes)): ?>
    <div class="alert alert-warning" style="background:#fff7ed; color:#9a3412; border:1px solid #fdba74;">
        Belum ada data kelas. Tambahkan dulu di menu <strong>Kelola Kelas</strong> sebelum mendaftarkan user dari kartu invalid.
    </div>
<?php endif; ?>

<div class="card data-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6><i class="bi bi-exclamation-triangle-fill me-2"></i>Riwayat Kartu Belum Terdaftar</h6>
        <span class="badge bg-warning text-dark"><?= count($invalid_cards) ?> data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>UID</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invalid_cards)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Tidak ada kartu invalid.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($invalid_cards as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y', strtotime($row->tanggal)) ?></td>
                                <td><?= $row->waktu ?></td>
                                <td><code><?= htmlspecialchars($row->uid_rfid) ?></code></td>
                                <td><span class="badge badge-invalid">Invalid</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#modalTambah" data-id="<?= $row->id ?>" data-uid="<?= htmlspecialchars($row->uid_rfid) ?>" <?= empty($classes) ? 'disabled' : '' ?>>
                                        <i class="bi bi-person-plus-fill"></i> Tambah
                                    </button>
                                    <a href="<?= site_url('admin/hapus_invalid/' . $row->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus log ini?')">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('admin/register_user') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Daftarkan Sebagai User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="invalid_id" id="reg_invalid_id">
                    <div class="mb-3">
                        <label class="form-label">UID RFID</label>
                        <input type="text" class="form-control" id="reg_uid" readonly style="background:#f3f4f6;">
                    </div>
                    <div class="alert alert-info" style="background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;">
                        Setelah user didaftarkan, seluruh log invalid dengan UID yang sama akan otomatis dihapus dari daftar ini.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                        <div style="max-height:180px; overflow:auto; border:1.5px solid #d1d5db; border-radius:8px; padding:10px 12px;">
                            <?php foreach ($mata_kuliah_options as $index => $option): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="mata_kuliah[]" value="<?= htmlspecialchars($option) ?>" id="reg_mata_kuliah_<?= $index ?>">
                                    <label class="form-check-label" for="reg_mata_kuliah_<?= $index ?>">
                                        <?= htmlspecialchars($option) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="text-muted" style="display:block; margin-top:8px; font-size:12px;">
                            Pilih 1 atau lebih mata kuliah sesuai data di Kelola Kelas.
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('modalTambah').addEventListener('show.bs.modal', function(e) {
        var btn = e.relatedTarget;
        document.getElementById('reg_invalid_id').value = btn.getAttribute('data-id');
        document.getElementById('reg_uid').value = btn.getAttribute('data-uid');
    });
</script>
