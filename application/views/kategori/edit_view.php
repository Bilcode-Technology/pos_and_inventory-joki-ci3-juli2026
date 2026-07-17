<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Kategori</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('kategori/update/'.$kategori->id_kategori, ['class' => 'needs-validation']); ?>
                    <div class="mb-4">
                        <label for="nama_kategori" class="form-label fw-medium">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required placeholder="Nama Kategori" value="<?= set_value('nama_kategori', $kategori->nama_kategori); ?>" autofocus>
                        <?= form_error('nama_kategori', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('kategori'); ?>" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Perbarui Kategori</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
