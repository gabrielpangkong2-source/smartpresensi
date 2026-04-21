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
        Belum ada data kelas. Tambahkan dulu di menu <strong>Kelola Kelas</strong> agar user bisa didaftarkan dan presensi bisa berjalan.
    </div>
<?php endif; ?>

<?php if ($active_class): ?>
    <div class="alert alert-success" style="background:#ecfdf5; color:#166534; border:1px solid #86efac;">
        Presensi hari ini memakai kelas aktif <strong><?= htmlspecialchars($active_class->kelas) ?></strong> |
        <?= htmlspecialchars($active_class->mata_kuliah) ?> |
        <?= htmlspecialchars($active_class->ruangan) ?> |
        Jam Masuk <strong><?= htmlspecialchars(substr($active_class->jam_masuk, 0, 5)) ?></strong>.
    </div>
<?php else: ?>
    <div class="alert alert-warning" style="background:#fff7ed; color:#9a3412; border:1px solid #fdba74;">
        Belum ada kelas yang dicentang sebagai <strong>Presensi Hari Ini</strong>. Pilih dulu di menu <strong>Kelola Kelas</strong>.
    </div>
<?php endif; ?>

<div class="card data-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6><i class="bi bi-people-fill me-2"></i>Data User Terdaftar</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary"><?= count($users) ?> user</span>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate" <?= empty($classes) ? 'disabled' : '' ?>>
                <i class="bi bi-plus-lg me-1"></i>Tambah User
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Mata Kuliah</th>
                        <th>No HP</th>
                        <th>UID RFID</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="empty-state">Belum ada user terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($users as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row->nama) ?></td>
                                <td style="white-space:normal; min-width:220px;"><?= htmlspecialchars($row->mata_kuliah_list) ?></td>
                                <td><?= htmlspecialchars($row->no_hp) ?></td>
                                <td><code><?= htmlspecialchars($row->uid_rfid) ?></code></td>
                                <td><?= ($row->jenis_kelamin === 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit"
                                        data-id="<?= $row->id ?>"
                                        data-nama="<?= htmlspecialchars($row->nama, ENT_QUOTES, 'UTF-8') ?>"
                                        data-nohp="<?= htmlspecialchars($row->no_hp, ENT_QUOTES, 'UTF-8') ?>"
                                        data-uid="<?= htmlspecialchars($row->uid_rfid, ENT_QUOTES, 'UTF-8') ?>"
                                        data-jk="<?= htmlspecialchars($row->jenis_kelamin, ENT_QUOTES, 'UTF-8') ?>"
                                        data-mata-kuliah="<?= htmlspecialchars(json_encode($row->mata_kuliah_items), ENT_QUOTES, 'UTF-8') ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="<?= site_url('admin/hapus_user/' . $row->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus user ini? Semua data absensi miliknya juga akan terhapus.')">
                                        <i class="bi bi-trash3"></i>
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

<div class="card data-card" id="presensi-hari-ini">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6><i class="bi bi-clipboard-check-fill me-2"></i>Presensi Hari Ini - <?= date('d F Y') ?></h6>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <?php if ($active_class): ?>
                <span class="badge bg-success"><?= htmlspecialchars($active_class->kelas) ?></span>
            <?php else: ?>
                <span class="badge bg-warning text-dark">Belum pilih kelas</span>
            <?php endif; ?>
            <span class="badge bg-primary"><?= count($active_absensi) ?> data</span>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if ($active_class): ?>
            <div style="padding:16px 20px; border-bottom:1px solid #e5e9f0; background:#f8fafc;">
                <div style="font-size:13px; font-weight:700; color:#111827;"><?= htmlspecialchars($active_class->mata_kuliah) ?></div>
                <div style="margin-top:4px; font-size:12.5px; color:#64748b;">
                    Ruangan <?= htmlspecialchars($active_class->ruangan) ?> | Jam Masuk <?= htmlspecialchars(substr($active_class->jam_masuk, 0, 5)) ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kode Kelas</th>
                        <th>Mata Kuliah</th>
                        <th>Ruangan</th>
                        <th>Jam Masuk</th>
                        <th>No HP</th>
                        <th>UID</th>
                        <th>Jenis Kelamin</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($active_absensi)): ?>
                        <tr>
                            <td colspan="12" class="empty-state">Belum ada data presensi untuk kelas aktif hari ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($active_absensi as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row->nama) ?></td>
                                <td><?= htmlspecialchars($row->kelas ? $row->kelas : '-') ?></td>
                                <td><?= htmlspecialchars($row->mata_kuliah ? $row->mata_kuliah : '-') ?></td>
                                <td><?= htmlspecialchars($row->ruangan ? $row->ruangan : '-') ?></td>
                                <td><?= htmlspecialchars($row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-') ?></td>
                                <td><?= htmlspecialchars($row->no_hp) ?></td>
                                <td><code><?= htmlspecialchars($row->uid_rfid) ?></code></td>
                                <td><?= ($row->jenis_kelamin === 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
                                <td><?= htmlspecialchars($row->waktu) ?></td>
                                <td><span class="badge <?= ($row->status === 'hadir') ? 'badge-hadir' : 'badge-telat' ?>"><?= ucfirst($row->status) ?></span></td>
                                <td>
                                    <a href="<?= site_url('admin/hapus_absensi/' . $row->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data presensi ini?')">
                                        <i class="bi bi-trash3"></i>
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

<?php if (!empty($other_absensi_groups)): ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 mb-3">
        <h6 class="mb-0" style="font-weight:800; color:#111827;">
            <i class="bi bi-collection-fill me-2"></i>Presensi Mata Kuliah / Kelas Lain Hari Ini
        </h6>
        <span class="badge bg-secondary"><?= count($other_absensi_groups) ?> tabel</span>
    </div>

    <?php foreach ($other_absensi_groups as $group): ?>
        <div class="card data-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6><i class="bi bi-journal-text me-2"></i><?= htmlspecialchars($group['kode_kelas']) ?></h6>
                    <div style="margin-top:4px; font-size:12.5px; color:#64748b; font-weight:600;">
                        <?= htmlspecialchars($group['mata_kuliah']) ?> | <?= htmlspecialchars($group['ruangan']) ?> | Jam Masuk <?= htmlspecialchars($group['jam_masuk']) ?>
                    </div>
                </div>
                <span class="badge bg-secondary"><?= count($group['items']) ?> data</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kode Kelas</th>
                                <th>Mata Kuliah</th>
                                <th>Ruangan</th>
                                <th>Jam Masuk</th>
                                <th>No HP</th>
                                <th>UID</th>
                                <th>Jenis Kelamin</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($group['items'] as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row->nama) ?></td>
                                    <td><?= htmlspecialchars($row->kelas ? $row->kelas : '-') ?></td>
                                    <td><?= htmlspecialchars($row->mata_kuliah ? $row->mata_kuliah : '-') ?></td>
                                    <td><?= htmlspecialchars($row->ruangan ? $row->ruangan : '-') ?></td>
                                    <td><?= htmlspecialchars($row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-') ?></td>
                                    <td><?= htmlspecialchars($row->no_hp) ?></td>
                                    <td><code><?= htmlspecialchars($row->uid_rfid) ?></code></td>
                                    <td><?= ($row->jenis_kelamin === 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
                                    <td><?= htmlspecialchars($row->waktu) ?></td>
                                    <td><span class="badge <?= ($row->status === 'hadir') ? 'badge-hadir' : 'badge-telat' ?>"><?= ucfirst($row->status) ?></span></td>
                                    <td>
                                        <a href="<?= site_url('admin/hapus_absensi/' . $row->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data presensi ini?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="modal fade" id="modalCreate" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= site_url('admin/create_user') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                        <div style="max-height:180px; overflow:auto; border:1.5px solid #d1d5db; border-radius:8px; padding:10px 12px;">
                            <?php foreach ($mata_kuliah_options as $index => $option): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="mata_kuliah[]" value="<?= htmlspecialchars($option) ?>" id="create_mata_kuliah_<?= $index ?>">
                                    <label class="form-check-label" for="create_mata_kuliah_<?= $index ?>">
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
                        <label class="form-label">UID RFID Card <span class="text-danger">*</span></label>
                        <input type="text" name="uid_rfid" class="form-control" placeholder="Masukkan UID kartu RFID" required>
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

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditUser" action="<?= site_url('admin/update_user/0') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                        <div style="max-height:180px; overflow:auto; border:1.5px solid #d1d5db; border-radius:8px; padding:10px 12px;">
                            <?php foreach ($mata_kuliah_options as $index => $option): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input edit-mata-kuliah" type="checkbox" name="mata_kuliah[]" value="<?= htmlspecialchars($option) ?>" id="edit_mata_kuliah_<?= $index ?>">
                                    <label class="form-check-label" for="edit_mata_kuliah_<?= $index ?>">
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
                        <input type="text" name="no_hp" id="edit_no_hp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">UID RFID Card <span class="text-danger">*</span></label>
                        <input type="text" name="uid_rfid" id="edit_uid_rfid" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="edit_jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
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
    document.getElementById('modalEdit').addEventListener('show.bs.modal', function(e) {
        var btn = e.relatedTarget;
        var id = btn.getAttribute('data-id');
        var selectedMataKuliah = [];

        try {
            selectedMataKuliah = JSON.parse(btn.getAttribute('data-mata-kuliah') || '[]');
        } catch (error) {
            selectedMataKuliah = [];
        }

        document.getElementById('formEditUser').action = "<?= site_url('admin/update_user') ?>/" + id;
        document.getElementById('edit_nama').value = btn.getAttribute('data-nama');
        document.getElementById('edit_no_hp').value = btn.getAttribute('data-nohp');
        document.getElementById('edit_uid_rfid').value = btn.getAttribute('data-uid');
        document.getElementById('edit_jenis_kelamin').value = btn.getAttribute('data-jk');

        document.querySelectorAll('.edit-mata-kuliah').forEach(function(input) {
            input.checked = selectedMataKuliah.indexOf(input.value) !== -1;
        });
    });
</script>
