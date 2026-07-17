<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i> Tambah Supplier Baru</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('supplier/store', ['class' => 'needs-validation']); ?>
                    <div class="mb-3">
                        <label for="nama_supplier" class="form-label fw-medium">Nama Supplier</label>
                        <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" required placeholder="Contoh: PT. Sumber Makmur" value="<?= set_value('nama_supplier'); ?>" autofocus>
                        <?= form_error('nama_supplier', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="mb-3">
                        <label for="no_telp" class="form-label fw-medium">No. Telepon / WhatsApp</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Contoh: 0812345678" value="<?= set_value('no_telp'); ?>">
                        <?= form_error('no_telp', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="mb-4">
                        <label for="alamat" class="form-label fw-medium">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap supplier..."><?= set_value('alamat'); ?></textarea>
                        <?= form_error('alamat', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="d-flex justify-content-between pt-2 border-top">
                        <a href="<?= site_url('supplier'); ?>" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Supplier</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
