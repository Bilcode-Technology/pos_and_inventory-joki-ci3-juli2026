<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <!-- Welcome Header -->
    <div class="col-12 mb-4">
        <div class="card bg-white border-0 shadow-sm" style="border-radius: 0.75rem;">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Halo, <?= html_escape($this->session->userdata('nama_user')); ?>!</h3>
                    <p class="text-muted mb-0">Selamat datang kembali di panel POS & Inventory. Hak akses Anda: <span class="badge bg-primary text-uppercase"><?= html_escape($this->session->userdata('role')); ?></span></p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="text-muted small fw-medium"><i class="bi bi-clock me-1"></i> Terakhir Login: <?= date('d M Y, H:i'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <?php if ($role === 'owner'): ?>
        <!-- Owner Summary Cards -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-primary text-white h-100 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded p-3 me-3 text-white">
                        <i class="bi bi-wallet2 fs-2"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 small mb-1">Penjualan Bulan Ini</h6>
                        <h3 class="fw-bold mb-0"><?= number_to_currency($total_penjualan_bulan, 'IDR', 'id_ID'); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-danger text-white h-100 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded p-3 me-3 text-white">
                        <i class="bi bi-cart-check fs-2"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 small mb-1">Pembelian Bulan Ini</h6>
                        <h3 class="fw-bold mb-0"><?= number_to_currency($total_pembelian_bulan, 'IDR', 'id_ID'); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-success text-white h-100 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded p-3 me-3 text-white">
                        <i class="bi bi-box-seam fs-2"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 small mb-1">Total Produk Aktif</h6>
                        <h3 class="fw-bold mb-0"><?= $total_active_products; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 bg-warning text-dark h-100 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-black bg-opacity-10 rounded p-3 me-3 text-dark">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                    <div>
                        <h6 class="text-dark-50 small mb-1">Mitra / Supplier</h6>
                        <h3 class="fw-bold mb-0"><?= $total_suppliers; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Admin Summary Cards -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 bg-primary text-white h-100 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-cart-plus me-1"></i> Operasional POS</h5>
                    <p class="text-white-50 small">Buka layar kasir utama untuk melayani transaksi pembayaran pelanggan.</p>
                    <a href="<?= site_url('pos'); ?>" class="btn btn-white text-primary fw-bold mt-2 bg-white"><i class="bi bi-arrow-right-circle me-1"></i> Buka Kasir</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 bg-info text-white h-100 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-boxes me-1"></i> Update Katalog</h5>
                    <p class="text-white-50 small">Kelola informasi produk, harga jual, barcode, dan data kategori barang dagang.</p>
                    <a href="<?= site_url('produk'); ?>" class="btn btn-white text-info fw-bold mt-2 bg-white"><i class="bi bi-arrow-right-circle me-1"></i> Kelola Produk</a>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-4 mb-4">
            <div class="card border-0 bg-success text-white h-100 shadow-sm" style="border-radius: 0.75rem;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-truck me-1"></i> Pembelian Stok</h5>
                    <p class="text-white-50 small">Catat barang masuk dari pihak supplier untuk memperbaharui jumlah persediaan stok.</p>
                    <a href="<?= site_url('pembelian/create'); ?>" class="btn btn-white text-success fw-bold mt-2 bg-white"><i class="bi bi-arrow-right-circle me-1"></i> Catat Pembelian</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <!-- Low Stock Alert Warning -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-dark fw-bold"><i class="bi bi-exclamation-triangle text-warning me-2"></i> Peringatan Stok Menipis (Limit &le; 15)</span>
                <span class="badge bg-danger"><?= count($low_stock_alerts); ?> Produk</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-center">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($low_stock_alerts)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Semua stok produk aman dan tercukupi.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($low_stock_alerts as $alert): ?>
                                    <tr>
                                        <td><code><?= html_escape($alert->kode_produk); ?></code></td>
                                        <td class="fw-medium"><?= html_escape($alert->nama_produk); ?></td>
                                        <td><span class="badge bg-secondary"><?= html_escape($alert->nama_kategori); ?></span></td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-danger fw-bold fs-7 p-2 px-3">
                                                <?= $alert->stok; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales Transactions -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-dark fw-bold"><i class="bi bi-clock-history text-primary me-2"></i> Transaksi Penjualan Terakhir</span>
                <a href="<?= site_url('penjualan'); ?>" class="btn btn-sm btn-link text-decoration-none fw-semibold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>No Faktur</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_sales)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi penjualan hari ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_sales as $sale): ?>
                                    <tr>
                                        <td><strong class="text-primary"><?= html_escape($sale->kode_transaksi); ?></strong></td>
                                        <td class="small"><?= date('d/m/Y H:i', strtotime($sale->tanggal_penjualan)); ?></td>
                                        <td><?= html_escape($sale->nama_user); ?></td>
                                        <td class="fw-bold"><?= number_to_currency($sale->total_harga, 'IDR', 'id_ID'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
