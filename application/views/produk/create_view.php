<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i> Tambah Produk Baru</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('produk/store', ['class' => 'needs-validation']); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_produk" class="form-label fw-medium">Kode Produk (Barcode)</label>
                            <input type="text" class="form-control" id="kode_produk" name="kode_produk" required placeholder="Contoh: PRD-001" value="<?= set_value('kode_produk'); ?>" autofocus>
                            <?= form_error('kode_produk', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_kategori" class="form-label fw-medium">Kategori Kategori</label>
                            <select class="form-select" id="id_kategori" name="id_kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategori as $kat): ?>
                                    <option value="<?= $kat->id_kategori; ?>" <?= set_select('id_kategori', $kat->id_kategori); ?>><?= html_escape($kat->nama_kategori); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('id_kategori', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_produk" class="form-label fw-medium">Nama Produk</label>
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" required placeholder="Nama lengkap barang" value="<?= set_value('nama_produk'); ?>">
                        <?= form_error('nama_produk', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="harga_jual" class="form-label fw-medium">Harga Jual (Rupiah)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="harga_jual" name="harga_jual" required placeholder="Contoh: 15000" value="<?= set_value('harga_jual'); ?>">
                            </div>
                            <?= form_error('harga_jual', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="stok" class="form-label fw-medium">Stok Awal Sistem <span class="text-muted small fw-normal">(Opsional)</span></label>
                            <input type="number" min="0" class="form-control" id="stok" name="stok" placeholder="Contoh: 50 (Opsional)" value="<?= set_value('stok', 0); ?>">
                            <?= form_error('stok', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="<?= site_url('produk'); ?>" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Produk</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
