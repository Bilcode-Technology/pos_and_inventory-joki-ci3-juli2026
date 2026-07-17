<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?= site_url('riwayat-stok'); ?>" class="row align-items-end g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label small fw-semibold text-secondary">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= html_escape($start_date); ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label small fw-semibold text-secondary">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= html_escape($end_date); ?>">
            </div>
            <div class="col-md-3">
                <label for="jenis_pergerakan" class="form-label small fw-semibold text-secondary">Jenis Gerakan</label>
                <select class="form-select" id="jenis_pergerakan" name="jenis_pergerakan">
                    <option value="">Semua Gerakan</option>
                    <option value="masuk" <?= ($jenis_pergerakan === 'masuk') ? 'selected' : ''; ?>>Stok Masuk (+)</option>
                    <option value="keluar" <?= ($jenis_pergerakan === 'keluar') ? 'selected' : ''; ?>>Stok Keluar (-)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Saring</button>
                <a href="<?= site_url('riwayat-stok'); ?>" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- History Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right text-primary me-2"></i> Audit & Riwayat Pergerakan Stok</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th>Waktu / Tanggal</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Jenis Pergerakan</th>
                        <th class="text-end">Jumlah</th>
                        <th>Referensi / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat pergerakan stok tercatat.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($riwayat as $row): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td class="small"><?= date('d/m/Y H:i:s', strtotime($row->tanggal)); ?></td>
                                <td><code><?= html_escape($row->kode_produk); ?></code></td>
                                <td class="fw-semibold text-dark"><?= html_escape($row->nama_produk); ?></td>
                                <td class="text-center">
                                    <?php if ($row->jenis_pergerakan === 'masuk'): ?>
                                        <span class="badge bg-success text-white"><i class="bi bi-arrow-down-left me-1"></i> MASUK</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white"><i class="bi bi-arrow-up-right me-1"></i> KELUAR</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end fw-bold <?= ($row->jenis_pergerakan === 'masuk') ? 'text-success' : 'text-danger'; ?>">
                                    <?= ($row->jenis_pergerakan === 'masuk') ? '+' : '-'; ?><?= $row->kuantitas; ?> Unit
                                </td>
                                <td class="small text-secondary">
                                    <?= html_escape($row->keterangan); ?>
                                    <?php if ($row->referensi_id): ?>
                                        <span class="badge bg-light text-secondary border ms-1">ID: #<?= $row->referensi_id; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
