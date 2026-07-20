<?php defined('BASEPATH') OR exit('No direct script access allowed');
$role = $this->session->userdata('role');
$nama = $this->session->userdata('nama_user');
$seg1 = $this->uri->segment(1);
?>
<!-- Sidebar -->
<aside id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-box-seam me-2"></i>
        <span>
            <a href="<?= site_url('dashboard'); ?>"
                class="nav-link <?= ($seg1 == 'dashboard' || $seg1 == '') ? 'active' : ''; ?>">
                POS & Inventory
            </a>
        </span>
    </div>

    <div class="sidebar-heading">Main Navigation</div>
    <ul class="sidebar-nav">
        <li>
            <a href="<?= site_url('dashboard'); ?>"
                class="nav-link <?= ($seg1 == 'dashboard' || $seg1 == '') ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
    </ul>

    <!-- Transaction / POS Section -->
    <div class="sidebar-heading">Transactions</div>
    <ul class="sidebar-nav">
        <li>
            <a href="<?= site_url('pos'); ?>" class="nav-link <?= ($seg1 == 'pos') ? 'active' : ''; ?>">
                <i class="bi bi-cart-plus"></i> Kasir / POS
            </a>
        </li>
        <li>
            <a href="<?= site_url('penjualan'); ?>" class="nav-link <?= ($seg1 == 'penjualan') ? 'active' : ''; ?>">
                <i class="bi bi-receipt"></i> Daftar Penjualan
            </a>
        </li>
    </ul>

    <!-- Inventory / Stock Section -->
    <div class="sidebar-heading">Inventory</div>
    <ul class="sidebar-nav">
        <li>
            <a href="<?= site_url('pembelian/create'); ?>"
                class="nav-link <?= ($seg1 == 'pembelian' && $this->uri->segment(2) == 'create') ? 'active' : ''; ?>">
                <i class="bi bi-truck"></i> Pembelian Stok
            </a>
        </li>
        <li>
            <a href="<?= site_url('pembelian'); ?>"
                class="nav-link <?= ($seg1 == 'pembelian' && $this->uri->segment(2) != 'create') ? 'active' : ''; ?>">
                <i class="bi bi-clipboard-data"></i> Daftar Pembelian
            </a>
        </li>
        <li>
            <a href="<?= site_url('riwayat-stok'); ?>"
                class="nav-link <?= ($seg1 == 'riwayat-stok' || $seg1 == 'riwayat_stok') ? 'active' : ''; ?>">
                <i class="bi bi-arrow-left-right"></i> Riwayat Stok
            </a>
        </li>
    </ul>

    <!-- Master Data Section -->
    <div class="sidebar-heading">Master Data</div>
    <ul class="sidebar-nav">
        <li>
            <a href="<?= site_url('produk'); ?>" class="nav-link <?= ($seg1 == 'produk') ? 'active' : ''; ?>">
                <i class="bi bi-tags"></i> Produk
            </a>
        </li>
        <li>
            <a href="<?= site_url('kategori'); ?>" class="nav-link <?= ($seg1 == 'kategori') ? 'active' : ''; ?>">
                <i class="bi bi-grid"></i> Kategori
            </a>
        </li>
        <li>
            <a href="<?= site_url('supplier'); ?>" class="nav-link <?= ($seg1 == 'supplier') ? 'active' : ''; ?>">
                <i class="bi bi-buildings"></i> Supplier
            </a>
        </li>
        <?php if ($role === 'admin' || $role === 'owner'): ?>
            <li>
                <a href="<?= site_url('users'); ?>" class="nav-link <?= ($seg1 == 'users') ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i> Users
                </a>
            </li>
        <?php endif; ?>
    </ul>
</aside>
<!-- /#sidebar -->

<!-- Page Content Wrapper -->
<div id="page-content-wrapper">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg top-navbar">
        <div class="container-fluid px-0">
            <button class="btn btn-light border d-lg-none me-3" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="mb-0 fw-bold text-secondary d-none d-sm-block">
                <?= isset($title) ? $title : 'Overview'; ?>
            </h5>

            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center py-1 px-3 bg-light rounded-pill"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                            style="width: 32px; height: 32px; font-weight: 600;">
                            <?= strtoupper(substr($nama ?: 'U', 0, 1)); ?>
                        </div>
                        <span class="fw-medium me-1"><?= html_escape($nama); ?></span>
                        <span
                            class="badge <?= ($role === 'owner') ? 'bg-warning text-dark' : 'bg-info text-dark'; ?> ms-1 text-uppercase"
                            style="font-size: 0.65rem;">
                            <?= html_escape($role); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li>
                            <h6 class="dropdown-header">Account Role: <?= ucfirst($role); ?></h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= site_url('auth/logout'); ?>">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container-fluid py-4 flex-grow-1">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4"
                role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                <div><?= $this->session->flashdata('success'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4"
                role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
                <div><?= $this->session->flashdata('error'); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>