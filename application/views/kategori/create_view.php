<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i> Tambah Kategori Baru</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('kategori/store', ['class' => 'needs-validation']); ?>
                    <div class="mb-4">
                        <label for="nama_kategori" class="form-label fw-medium">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required placeholder="Contoh: Elektronik, Pakaian, Makanan" value="<?= set_value('nama_kategori'); ?>" autofocus>
                        <?= form_error('nama_kategori', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('kategori'); ?>" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Kategori</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
