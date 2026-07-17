<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container d-flex align-items-center justify-content-center min-vh-100" style="margin-top: -50px;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <!-- Flash Message Alerts -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
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
                            <i class="bi bi-box-seam fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-1">POS & Inventory</h4>
                        <p class="text-muted small">Silakan masuk menggunakan akun Anda</p>
                    </div>

                    <?= form_open('auth/process_login', ['class' => 'needs-validation']); ?>
                        <div class="mb-3">
                            <label for="username" class="form-label small fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" id="username" name="username" placeholder="Masukkan username" required autofocus value="<?= set_value('username'); ?>">
                            </div>
                            <?= form_error('username', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label small fw-semibold">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control bg-light border-start-0" id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                            <?= form_error('password', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm" style="border-radius: 0.5rem;">
                            <i class="bi bi-box-arrow-in-right me-1"></i> MASUK
                        </button>
                    <?= form_close(); ?>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="text-muted small mb-0">Belum punya akun? <a href="<?= site_url('auth/register'); ?>" class="fw-bold text-decoration-none">Daftar Akun Baru</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
