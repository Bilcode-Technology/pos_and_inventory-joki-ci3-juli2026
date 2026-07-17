<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Data User</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('users/update/'.$user->id_user, ['class' => 'needs-validation']); ?>
                    <div class="mb-3">
                        <label for="nama_user" class="form-label fw-medium">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_user" name="nama_user" required placeholder="Nama Lengkap" value="<?= set_value('nama_user', $user->nama_user); ?>">
                        <?= form_error('nama_user', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-medium">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Username" value="<?= set_value('username', $user->username); ?>">
                        <?= form_error('username', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-medium">Role Akses</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin" <?= set_select('role', 'admin', $user->role === 'admin'); ?>>Admin (Kasir/Operasional)</option>
                            <option value="owner" <?= set_select('role', 'owner', $user->role === 'owner'); ?>>Owner (Pemilik Toko)</option>
                        </select>
                        <?= form_error('role', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium">Password Baru <span class="text-muted small fw-normal">(Kosongkan jika tidak diganti)</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru">
                        <?= form_error('password', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('users'); ?>" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Perbarui User</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
