<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-buildings me-2 text-primary"></i> Daftar Supplier</h5>
        <a href="<?= site_url('supplier/create'); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Tambah Supplier</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th>Nama Supplier</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Tanggal Terdaftar</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($supplier)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada supplier terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($supplier as $sup): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td class="fw-semibold text-dark"><?= html_escape($sup->nama_supplier); ?></td>
                                <td><i class="bi bi-telephone text-muted me-1"></i> <?= html_escape($sup->no_telp ?: '-'); ?></td>
                                <td class="small text-secondary"><?= html_escape($sup->alamat ?: '-'); ?></td>
                                <td class="small"><?= date('d M Y', strtotime($sup->created_at)); ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('supplier/edit/'.$sup->id_supplier); ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= site_url('supplier/delete/'.$sup->id_supplier); ?>', '<?= html_escape($sup->nama_supplier); ?>')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
