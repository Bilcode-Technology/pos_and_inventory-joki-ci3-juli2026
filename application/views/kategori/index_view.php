<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-grid me-2 text-primary"></i> Kategori Produk</h5>
        <a href="<?= site_url('kategori/create'); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Tambah Kategori</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th>Nama Kategori</th>
                        <th>Tanggal Dibuat</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kategori)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada kategori terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($kategori as $kat): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td class="fw-semibold text-dark"><?= html_escape($kat->nama_kategori); ?></td>
                                <td class="small"><?= date('d M Y, H:i', strtotime($kat->created_at)); ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('kategori/edit/'.$kat->id_kategori); ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= site_url('kategori/delete/'.$kat->id_kategori); ?>', '<?= html_escape($kat->nama_kategori); ?>')">
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
