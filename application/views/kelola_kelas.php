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

<?php if ($active_class): ?>
    <div class="alert alert-success" style="background:#ecfdf5; color:#166534; border:1px solid #86efac;">
        Kode kelas yang dicentang sebagai <strong>Presensi Hari Ini</strong> saat ini adalah
        <strong><?= htmlspecialchars($active_class->kelas) ?></strong> |
        <?= htmlspecialchars($active_class->mata_kuliah) ?> |
        <?= htmlspecialchars($active_class->ruangan) ?> |
        Jam Masuk <strong><?= htmlspecialchars(substr($active_class->jam_masuk, 0, 5)) ?></strong>.
    </div>
<?php else: ?>
    <div class="alert alert-warning" style="background:#fff7ed; color:#9a3412; border:1px solid #fdba74;">
        Belum ada kelas yang dicentang untuk menjadi <strong>Presensi Hari Ini</strong>.
    </div>
<?php endif; ?>

<div class="card data-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6><i class="bi bi-journal-bookmark-fill me-2"></i>Data Kelas</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary"><?= count($classes) ?> kelas</span>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateKelas">
                <i class="bi bi-plus-lg me-1"></i>Tambah Kelas
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Kelas</th>
                        <th>Mata Kuliah</th>
                        <th>Ruangan</th>
                        <th>Jam Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classes)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Belum ada data kelas.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($classes as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row->kelas) ?></td>
                                <td><?= htmlspecialchars($row->mata_kuliah) ?></td>
                                <td><?= htmlspecialchars($row->ruangan) ?></td>
                                <td><?= htmlspecialchars(substr($row->jam_masuk, 0, 5)) ?></td>
                                <td>
                                    <div class="action-group">
                                        <?php if ((int) $active_kelas_id === (int) $row->id): ?>
                                            <span class="btn btn-sm btn-success disabled">
                                                <i class="bi bi-check2-circle me-1"></i>Aktif
                                            </span>
                                        <?php else: ?>
                                            <a href="<?= site_url('admin/aktifkan_kelas/' . $row->id) ?>" class="btn btn-sm btn-success" onclick="return confirm('Jadikan kelas ini sebagai presensi hari ini?')">
                                                <i class="bi bi-check2-circle me-1"></i>Centang
                                            </a>
                                        <?php endif; ?>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditKelas"
                                            data-id="<?= $row->id ?>"
                                            data-kelas="<?= htmlspecialchars($row->kelas, ENT_QUOTES, 'UTF-8') ?>"
                                            data-mata-kuliah="<?= htmlspecialchars($row->mata_kuliah, ENT_QUOTES, 'UTF-8') ?>"
                                            data-ruangan="<?= htmlspecialchars($row->ruangan, ENT_QUOTES, 'UTF-8') ?>"
                                            data-jam-masuk="<?= htmlspecialchars(substr($row->jam_masuk, 0, 5), ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= site_url('admin/hapus_kelas/' . $row->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data kelas ini? Presensi hari ini untuk kelas ini juga akan dibersihkan.')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateKelas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('admin/create_kelas') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="kelas" class="form-control" placeholder="Contoh: TI-1A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                        <input type="text" name="mata_kuliah" class="form-control" placeholder="Contoh: Pemrograman Web" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="ruangan" class="form-control" placeholder="Contoh: Lab 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                        <input type="time" name="jam_masuk" class="form-control" required>
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

<div class="modal fade" id="modalEditKelas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditKelas" action="<?= site_url('admin/update_kelas/0') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="kelas" id="edit_kelas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                        <input type="text" name="mata_kuliah" id="edit_mata_kuliah" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="ruangan" id="edit_ruangan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                        <input type="time" name="jam_masuk" id="edit_jam_masuk" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('modalEditKelas').addEventListener('show.bs.modal', function(e) {
        var btn = e.relatedTarget;
        var id = btn.getAttribute('data-id');

        document.getElementById('formEditKelas').action = "<?= site_url('admin/update_kelas') ?>/" + id;
        document.getElementById('edit_kelas').value = btn.getAttribute('data-kelas');
        document.getElementById('edit_mata_kuliah').value = btn.getAttribute('data-mata-kuliah');
        document.getElementById('edit_ruangan').value = btn.getAttribute('data-ruangan');
        document.getElementById('edit_jam_masuk').value = btn.getAttribute('data-jam-masuk');
    });
</script>
