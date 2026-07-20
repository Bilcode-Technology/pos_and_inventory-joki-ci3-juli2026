<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container d-flex flex-column align-items-center justify-content-center min-vh-100" style="margin-top: -10px; margin-bottom: 20px;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Flash Message Alerts -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-plus fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-1">Registrasi Akun</h4>
                        <p class="text-muted small">Lengkapi formulir untuk membuat akun baru</p>
                    </div>

                    <?= form_open('auth/process_register', ['class' => 'needs-validation']); ?>
                        <div class="mb-3">
                            <label for="nama_user" class="form-label small fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" id="nama_user" name="nama_user" placeholder="Nama Lengkap Anda" required value="<?= set_value('nama_user'); ?>">
                            </div>
                            <?= form_error('nama_user', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label small fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" id="username" name="username" placeholder="Buat username unik" required value="<?= set_value('username'); ?>">
                            </div>
                            <?= form_error('username', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label small fw-semibold">Role / Izin Akses</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-lock"></i></span>
                                <select class="form-select bg-light border-start-0" id="role" name="role" required>
                                    <option value="admin" <?= set_select('role', 'admin', TRUE); ?>>Admin (Operasional Kasir)</option>
                                    <option value="owner" <?= set_select('role', 'owner'); ?>>Owner (Pemilik Toko)</option>
                                </select>
                            </div>
                            <?= form_error('role', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control bg-light border-start-0" id="password" name="password" placeholder="Minimal 6 karakter" required>
                            </div>
                            <?= form_error('password', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>

                        <div class="mb-4">
                            <label for="passconf" class="form-label small fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control bg-light border-start-0" id="passconf" name="passconf" placeholder="Ulangi password" required>
                            </div>
                            <?= form_error('passconf', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm" style="border-radius: 0.5rem;">
                            <i class="bi bi-check-circle me-1"></i> REGISTRASI SEKARANG
                        </button>
                    <?= form_close(); ?>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="text-muted small mb-0">Sudah memiliki akun? <a href="<?= site_url('auth/login'); ?>" class="fw-bold text-decoration-none">Masuk ke Sistem</a></p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">&copy; <?= date('Y'); ?> <strong>POS & Inventory System</strong>. All rights reserved.</p>
                <p class="text-muted small mb-0">Built with <span class="text-danger"><i class="bi bi-heart-fill"></i></span> using CodeIgniter 3 & Bootstrap 5</p>
            </div>
        </div>
    </div>
</div>
