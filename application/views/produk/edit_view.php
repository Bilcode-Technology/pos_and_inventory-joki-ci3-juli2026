<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Produk</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('produk/update/'.$produk->id_produk, ['class' => 'needs-validation']); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_produk" class="form-label fw-medium">Kode Produk</label>
                            <input type="text" class="form-control" id="kode_produk" name="kode_produk" required value="<?= set_value('kode_produk', $produk->kode_produk); ?>">
                            <?= form_error('kode_produk', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_kategori" class="form-label fw-medium">Kategori Kategori</label>
                            <select class="form-select" id="id_kategori" name="id_kategori" required>
                                <?php foreach ($kategori as $kat): ?>
                                    <option value="<?= $kat->id_kategori; ?>" <?= set_select('id_kategori', $kat->id_kategori, $kat->id_kategori === $produk->id_kategori); ?>><?= html_escape($kat->nama_kategori); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('id_kategori', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_produk" class="form-label fw-medium">Nama Produk</label>
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" required value="<?= set_value('nama_produk', $produk->nama_produk); ?>">
                        <?= form_error('nama_produk', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="harga_jual" class="form-label fw-medium">Harga Jual (Rupiah)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="harga_jual" name="harga_jual" required value="<?= set_value('harga_jual', $produk->harga_jual); ?>">
                            </div>
                            <?= form_error('harga_jual', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="stok_readonly" class="form-label fw-medium">Persediaan Stok <span class="text-muted small fw-normal">(Kunci / Read-Only)</span></label>
                            <input type="text" class="form-control bg-light" id="stok_readonly" disabled value="<?= $produk->stok; ?> Unit">
                            <div class="form-text text-info small mt-1"><i class="bi bi-info-circle"></i> Stok hanya dapat disesuaikan melalui transaksi pembelian atau kasir POS.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="<?= site_url('produk'); ?>" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Perbarui Produk</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
