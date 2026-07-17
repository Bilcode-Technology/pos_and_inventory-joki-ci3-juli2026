<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i> Daftar Pengguna / Users</h5>
        <a href="<?= site_url('users/create'); ?>" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i> Tambah User</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role Akses</th>
                        <th>Tanggal Terdaftar</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data user.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($users as $user): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td class="fw-semibold text-dark"><?= html_escape($user->nama_user); ?></td>
                                <td><code><?= html_escape($user->username); ?></code></td>
                                <td>
                                    <span class="badge <?= ($user->role === 'owner') ? 'bg-warning text-dark' : 'bg-info text-dark'; ?> text-uppercase">
                                        <?= html_escape($user->role); ?>
                                    </span>
                                </td>
                                <td class="small"><?= date('d M Y, H:i', strtotime($user->created_at)); ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('users/edit/'.$user->id_user); ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= site_url('users/delete/'.$user->id_user); ?>', '<?= html_escape($user->username); ?>')">
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
