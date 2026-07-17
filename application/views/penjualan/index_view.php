<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i> Daftar Transaksi Penjualan</h5>
        <a href="<?= site_url('pos'); ?>" class="btn btn-primary btn-sm"><i class="bi bi-cart-plus me-1"></i> Buka Kasir POS</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th>Waktu Transaksi</th>
                        <th>No Faktur</th>
                        <th>Kasir</th>
                        <th class="text-end">Total Harga</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($penjualan)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi penjualan tercatat.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($penjualan as $row): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $no++; ?></td>
                                <td class="small"><?= date('d/m/Y H:i:s', strtotime($row->tanggal_penjualan)); ?></td>
                                <td><strong class="text-primary"><?= html_escape($row->no_faktur); ?></strong></td>
                                <td><?= html_escape($row->nama_user); ?> <span class="text-muted small">(<?= html_escape($row->username); ?>)</span></td>
                                <td class="text-end fw-bold"><?= number_to_currency($row->total_harga, 'IDR', 'id_ID'); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info" onclick="showInvoiceDetail(<?= $row->id_penjualan; ?>)">
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

<!-- Modal Detail Penjualan -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="detailModalLabel"><i class="bi bi-file-earmark-text text-primary me-2"></i> Detail Transaksi Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Header Info -->
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-sm-6 mb-2 mb-sm-0">
                        <div class="text-muted small">Nomor Faktur</div>
                        <h4 class="fw-bold text-primary mb-0" id="modalFaktur">-</h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="text-muted small">Waktu Transaksi</div>
                        <h6 class="fw-semibold text-dark mb-0" id="modalTanggal">-</h6>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="text-muted small">Nama Kasir</div>
                        <h6 class="fw-semibold text-dark mb-0" id="modalKasir">-</h6>
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
                                <th style="width: 150px;" class="text-end">Harga Satuan</th>
                                <th style="width: 180px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItems">
                            <!-- Rows injected via AJAX -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold bg-light">Total Pembayaran</td>
                                <td class="text-end fw-bold text-primary bg-light fs-5" id="modalGrandTotal">Rp0,00</td>
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
     * Fetch sale details via AJAX and display in Bootstrap Modal
     * @param {number} id
     */
    function showInvoiceDetail(id) {
        $.ajax({
            url: '<?= site_url("penjualan/detail/"); ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Populate modal headers
                    $('#modalFaktur').text(response.header.no_faktur);
                    $('#modalTanggal').text(response.header.tanggal_penjualan);
                    $('#modalKasir').text(response.header.nama_user + ' (' + response.header.username + ')');
                    
                    // Format Total
                    var total = parseFloat(response.header.total_harga);
                    $('#modalGrandTotal').text(formatRupiah(total));

                    // Populate line items
                    var html = '';
                    $.each(response.detail, function(index, item) {
                        var sub = parseFloat(item.subtotal);
                        var prc = parseFloat(item.harga_satuan);
                        html += '<tr>' +
                            '<td class="text-center text-muted">' + (index + 1) + '</td>' +
                            '<td><strong class="text-dark">' + item.nama_produk + '</strong><br><small class="text-secondary">' + item.kode_produk + '</small></td>' +
                            '<td class="text-center">' + item.kuantitas + ' Unit</td>' +
                            '<td class="text-end">' + formatRupiah(prc) + '</td>' +
                            '<td class="text-end fw-semibold text-dark">' + formatRupiah(sub) + '</td>' +
                            '</tr>';
                    });
                    $('#modalItems').html(html);

                    // Show Modal
                    var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
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
