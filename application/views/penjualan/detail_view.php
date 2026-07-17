<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i> Detail Penjualan: <?= html_escape($header->no_faktur); ?></h5>
                <a href="<?= site_url('penjualan'); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span class="text-muted small d-block">Nomor Faktur</span>
                        <h3 class="fw-bold text-primary mb-0"><?= html_escape($header->no_faktur); ?></h3>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted small d-block">Tanggal Transaksi</span>
                        <h6 class="fw-semibold mb-0"><?= date('d F Y, H:i', strtotime($header->tanggal_penjualan)); ?></h6>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <span class="text-muted small d-block">Kasir Pembayar</span>
                        <h6 class="fw-semibold mb-0"><?= html_escape($header->nama_user); ?> (<?= html_escape($header->username); ?>)</h6>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;" class="text-center">No</th>
                                <th>Deskripsi Produk</th>
                                <th style="width: 120px;" class="text-center">Kuantitas</th>
                                <th style="width: 180px;" class="text-end">Harga Satuan</th>
                                <th style="width: 200px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($detail as $row): ?>
                                <tr>
                                    <td class="text-center text-muted"><?= $no++; ?></td>
                                    <td>
                                        <strong class="text-dark"><?= html_escape($row->nama_produk); ?></strong><br>
                                        <code class="small text-secondary"><?= html_escape($row->kode_produk); ?></code>
                                    </td>
                                    <td class="text-center"><?= $row->kuantitas; ?> Unit</td>
                                    <td class="text-end"><?= number_to_currency($row->harga_satuan, 'IDR', 'id_ID'); ?></td>
                                    <td class="text-end fw-bold text-dark"><?= number_to_currency($row->subtotal, 'IDR', 'id_ID'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold bg-light py-3">Total Belanja</td>
                                <td class="text-end fw-extrabold text-primary bg-light fs-5 py-3"><?= number_to_currency($header->total_harga, 'IDR', 'id_ID'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center d-print-none pt-2">
                    <button type="button" class="btn btn-outline-primary px-4 me-2" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Struk</button>
                </div>
            </div>
        </div>
    </div>
</div>
