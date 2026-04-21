<div class="card data-card mb-4">
    <div class="card-body">
        <form method="get" action="<?= site_url('admin/laporan') ?>" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="<?= $dari ? $dari : date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="<?= $sampai ? $sampai : date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Kode Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kode Kelas</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class->id ?>" <?= ((string) $kelas_id === (string) $class->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($class->kelas) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Mata Kuliah</label>
                <select name="mata_kuliah" class="form-select">
                    <option value="">Semua Mata Kuliah</option>
                    <?php foreach ($mata_kuliah_options as $option): ?>
                        <option value="<?= htmlspecialchars($option) ?>" <?= ($mata_kuliah === $option) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($laporan !== null): ?>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon" style="background:#dbeafe; color:#1a56db;">
                        <i class="bi bi-collection-fill"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= $summary['total'] ?></div>
                        <div class="stat-label">Total Data Laporan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon" style="background:#dcfce7; color:#16a34a;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= $summary['hadir'] ?></div>
                        <div class="stat-label">Status Hadir</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon" style="background:#fee2e2; color:#dc2626;">
                        <i class="bi bi-alarm-fill"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= $summary['telat'] ?></div>
                        <div class="stat-label">Status Telat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card data-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6><i class="bi bi-bar-chart-fill me-2"></i>Hasil Laporan</h6>
            <span class="badge bg-primary"><?= count($laporan) ?> data</span>
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
                            <th>UID</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)): ?>
                            <tr>
                                <td colspan="11" class="empty-state">Tidak ada data pada rentang tanggal ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1;
                            foreach ($laporan as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row->nama) ?></td>
                                    <td><?= htmlspecialchars($row->kelas ? $row->kelas : '-') ?></td>
                                    <td><?= htmlspecialchars($row->mata_kuliah ? $row->mata_kuliah : '-') ?></td>
                                    <td><?= htmlspecialchars($row->ruangan ? $row->ruangan : '-') ?></td>
                                    <td><?= htmlspecialchars($row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-') ?></td>
                                    <td><code><?= htmlspecialchars($row->uid_rfid) ?></code></td>
                                    <td><?= ($row->jenis_kelamin === 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
                                    <td><?= date('d/m/Y', strtotime($row->tanggal)) ?></td>
                                    <td><?= $row->waktu ?></td>
                                    <td><span class="badge <?= ($row->status === 'hadir') ? 'badge-hadir' : 'badge-telat' ?>"><?= ucfirst($row->status) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
