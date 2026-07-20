<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-tags me-2 text-primary"></i> Catalog Produk & Live Stok</h5>
        <a href="<?= site_url('produk/create'); ?>" class="btn btn-primary btn-sm"><i
                class="bi bi-plus-circle me-1"></i> Tambah Produk</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th class="text-center">Gambar</th>
                        <th>Kode & Barcode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Minimal Stok</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produk)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada produk terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($produk as $prd): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td class="text-center">
                                    <?php if (!empty($prd->gambar)): ?>
                                        <img src="<?= base_url('assets/uploads/produk/' . $prd->gambar); ?>" alt="Img"
                                            style="width:40px; height:40px; object-fit:cover; border-radius:4px;">
                                    <?php else: ?>
                                        <div class="bg-light d-inline-block text-muted text-center"
                                            style="width:40px; height:40px; line-height:40px; border-radius:4px;"><i
                                                class="bi bi-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code><?= html_escape($prd->kode_produk); ?></code><br>
                                    <small class="text-muted"><i class="bi bi-upc"></i>
                                        <?= html_escape($prd->barcode); ?></small>
                                </td>
                                <td class="fw-semibold text-dark"><?= html_escape($prd->nama_produk); ?></td>
                                <td><span
                                        class="badge bg-secondary-subtle text-secondary border"><?= html_escape($prd->nama_kategori); ?></span>
                                </td>
                                <td class="text-end fw-medium"><?= number_to_currency($prd->harga_jual, 'IDR', 'id_ID'); ?></td>
                                <td class="text-center">
                                    <?= $prd->minimal_stok; ?>         <?= html_escape($prd->satuan); ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $min = $prd->minimal_stok > 0 ? $prd->minimal_stok : 10;
                                    if ($prd->stok <= $min): ?>
                                        <span
                                            class="badge bg-danger-subtle text-danger fw-bold fs-7 p-2 px-3 border border-danger border-opacity-25"
                                            title="Stok Menipis!">
                                            <?= $prd->stok; ?>             <?= html_escape($prd->satuan); ?> (Menipis)
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="badge bg-success-subtle text-success fw-bold fs-7 p-2 px-3 border border-success border-opacity-25">
                                            <?= $prd->stok; ?>             <?= html_escape($prd->satuan); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($prd->status == 'aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('produk/edit/' . $prd->id_produk); ?>"
                                        class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete('<?= site_url('produk/delete/' . $prd->id_produk); ?>', '<?= html_escape($prd->nama_produk); ?>')">
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