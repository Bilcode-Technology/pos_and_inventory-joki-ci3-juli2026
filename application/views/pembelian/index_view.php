<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-data me-2 text-primary"></i> Daftar Transaksi Pembelian Stok</h5>
        <a href="<?= site_url('pembelian/create'); ?>" class="btn btn-primary btn-sm"><i class="bi me-1 bi-truck"></i> Catat Pembelian</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th>Waktu Transaksi</th>
                        <th>No Referensi</th>
                        <th>Supplier</th>
                        <th>Operator</th>
                        <th class="text-end">Total Pembelian</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembelian)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada transaksi pembelian tercatat.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($pembelian as $row): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td class="small"><?= date('d/m/Y H:i:s', strtotime($row->tanggal_pembelian)); ?></td>
                                <td><strong class="text-primary"><?= html_escape($row->no_referensi); ?></strong></td>
                                <td><span class="badge bg-secondary"><?= html_escape($row->nama_supplier); ?></span></td>
                                <td><?= html_escape($row->nama_user); ?></td>
                                <td class="text-end fw-bold"><?= number_to_currency($row->total_harga, 'IDR', 'id_ID'); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info" onclick="showPurchaseDetail(<?= $row->id_pembelian; ?>)">
                                        <i class="bi bi-eye"></i> Detail
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

<!-- Modal Detail Pembelian -->
<div class="modal fade" id="detailPembelianModal" tabindex="-1" aria-labelledby="detailPembelianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="detailPembelianModalLabel"><i class="bi bi-file-earmark-text text-primary me-2"></i> Detail Transaksi Pembelian Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Header Info -->
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-sm-6 mb-2 mb-sm-0">
                        <div class="text-muted small">Nomor Referensi (Invoice PO)</div>
                        <h4 class="fw-bold text-primary mb-0" id="modalPurchaseRef">-</h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="text-muted small">Waktu Transaksi</div>
                        <h6 class="fw-semibold text-dark mb-0" id="modalPurchaseTanggal">-</h6>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-sm-6 mb-2 mb-sm-0">
                        <div class="text-muted small">Supplier / Pemasok</div>
                        <h6 class="fw-semibold text-dark mb-0" id="modalPurchaseSupplier">-</h6>
                        <span class="text-secondary small" id="modalPurchaseSupplierContact">-</span>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="text-muted small">Diterima Oleh</div>
                        <h6 class="fw-semibold text-dark mb-0" id="modalPurchaseOperator">-</h6>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Produk</th>
                                <th style="width: 100px;" class="text-center">Kuantitas</th>
                                <th style="width: 150px;" class="text-end">Harga Beli</th>
                                <th style="width: 180px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalPurchaseItems">
                            <!-- Rows injected via AJAX -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold bg-light">Total Pembelian</td>
                                <td class="text-end fw-bold text-primary bg-light fs-5" id="modalPurchaseGrandTotal">Rp0,00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Fetch purchase details via AJAX and display in Bootstrap Modal
     * @param {number} id
     */
    function showPurchaseDetail(id) {
        $.ajax({
            url: '<?= site_url("pembelian/detail/"); ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Populate modal headers
                    $('#modalPurchaseRef').text(response.header.no_referensi);
                    $('#modalPurchaseTanggal').text(response.header.tanggal_pembelian);
                    $('#modalPurchaseSupplier').text(response.header.nama_supplier);
                    $('#modalPurchaseSupplierContact').text(response.header.no_telp + ' | ' + response.header.alamat);
                    $('#modalPurchaseOperator').text(response.header.nama_user + ' (' + response.header.username + ')');
                    
                    // Format Total
                    var total = parseFloat(response.header.total_harga);
                    $('#modalPurchaseGrandTotal').text(formatRupiah(total));

                    // Populate line items
                    var html = '';
                    $.each(response.detail, function(index, item) {
                        var sub = parseFloat(item.subtotal);
                        var buy = parseFloat(item.harga_beli);
                        html += '<tr>' +
                            '<td class="text-center text-muted">' + (index + 1) + '</td>' +
                            '<td><strong class="text-dark">' + item.nama_produk + '</strong><br><small class="text-secondary">' + item.kode_produk + '</small></td>' +
                            '<td class="text-center">' + item.kuantitas + ' Unit</td>' +
                            '<td class="text-end">' + formatRupiah(buy) + '</td>' +
                            '<td class="text-end fw-semibold text-dark">' + formatRupiah(sub) + '</td>' +
                            '</tr>';
                    });
                    $('#modalPurchaseItems').html(html);

                    // Show Modal
                    var myModal = new bootstrap.Modal(document.getElementById('detailPembelianModal'));
                    myModal.show();
                } else {
                    Swal.fire('Gagal', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal memproses data dari server.', 'error');
            }
        });
    }

    /**
     * Helper currency formatter
     */
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(amount);
    }
</script>
