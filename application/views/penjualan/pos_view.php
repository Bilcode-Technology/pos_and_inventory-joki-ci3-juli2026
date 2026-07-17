<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <!-- POS Cart Column (Left Side / Wide) -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark fs-5"><i class="bi bi-cart3 text-primary me-2"></i> Keranjang Kasir</span>
                <span class="badge bg-secondary-subtle text-secondary border">Faktur: <strong><?= $no_faktur; ?></strong></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="min-height: 350px;">
                    <table class="table align-middle table-hover mb-0" id="cartTable">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>Nama Produk</th>
                                <th style="width: 150px;" class="text-end">Harga Satuan</th>
                                <th style="width: 130px;" class="text-center">Kuantitas</th>
                                <th style="width: 160px;" class="text-end">Subtotal</th>
                                <th style="width: 80px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartItems">
                            <tr class="empty-row">
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-cart-x fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                                    Keranjang belanja kosong. Pilih produk di sebelah kanan untuk menambahkan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Grand Total Footer -->
                <div class="bg-light p-4 border-top">
                    <div class="row align-items-center">
                        <div class="col-sm-6 text-center text-sm-start mb-3 mb-sm-0">
                            <h5 class="text-secondary mb-1">TOTAL BAYAR:</h5>
                            <h2 class="fw-extrabold text-primary mb-0" id="grandTotalDisplay">Rp0,00</h2>
                        </div>
                        <div class="col-sm-6 text-center text-sm-end">
                            <button type="button" class="btn btn-outline-secondary btn-lg me-2" id="clearCart"><i class="bi bi-trash"></i> Reset</button>
                            <button type="button" class="btn btn-primary btn-lg px-4 shadow" id="btnCheckout"><i class="bi bi-credit-card"></i> Bayar Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Column (Right Side / Sidebar) -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold text-dark"><i class="bi bi-search text-primary me-2"></i> Cari & Tambah Produk</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="productSelect" class="form-label small fw-semibold text-secondary">Pilih Produk</label>
                    <select class="form-select" id="productSelect">
                        <option value="">-- Cari atau pilih produk --</option>
                        <?php foreach ($produk as $prd): ?>
                            <option value="<?= $prd->id_produk; ?>" 
                                    data-code="<?= html_escape($prd->kode_produk); ?>"
                                    data-name="<?= html_escape($prd->nama_produk); ?>" 
                                    data-price="<?= $prd->harga_jual; ?>"
                                    data-stock="<?= $prd->stok; ?>"
                                    <?= ($prd->stok <= 0) ? 'disabled class="text-danger"' : ''; ?>>
                                <?= html_escape($prd->kode_produk); ?> - <?= html_escape($prd->nama_produk); ?> 
                                (Stok: <?= $prd->stok; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <label for="productQty" class="form-label small fw-semibold text-secondary">Jumlah</label>
                        <input type="number" min="1" value="1" class="form-control" id="productQty">
                    </div>
                    <div class="col-md-6">
                        <label for="productStockDisplay" class="form-label small fw-semibold text-secondary">Stok Tersedia</label>
                        <input type="text" class="form-control bg-light" id="productStockDisplay" readonly value="0">
                    </div>
                </div>

                <button type="button" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm" id="addToCart"><i class="bi bi-plus-lg"></i> Tambahkan Ke Keranjang</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Cart JSON array structure holding added line items
    var cart = [];
    var noFaktur = '<?= $no_faktur; ?>';

    $(document).ready(function() {
        // Dropdown selection updates stock display
        $('#productSelect').on('change', function() {
            var selected = $(this).find('option:selected');
            if (selected.val() !== "") {
                var stock = parseInt(selected.data('stock'));
                $('#productStockDisplay').val(stock);
                $('#productQty').attr('max', stock).val(1);
            } else {
                $('#productStockDisplay').val(0);
                $('#productQty').removeAttr('max').val(1);
            }
        });

        // Add Product To Cart Button Click Handler
        $('#addToCart').on('click', function(e) {
            e.preventDefault();
            var select = $('#productSelect');
            var selected = select.find('option:selected');
            
            if (select.val() === "") {
                Swal.fire('Perhatian', 'Silakan pilih produk terlebih dahulu!', 'warning');
                return;
            }

            var id = parseInt(select.val());
            var code = selected.data('code');
            var name = selected.data('name');
            var price = parseFloat(selected.data('price'));
            var stock = parseInt(selected.data('stock'));
            var qty = parseInt($('#productQty').val());

            if (qty <= 0 || isNaN(qty)) {
                Swal.fire('Jumlah Tidak Valid', 'Jumlah minimal pembelian adalah 1.', 'warning');
                return;
            }

            if (qty > stock) {
                Swal.fire('Stok Kurang', 'Jumlah diminta melebihi stok yang tersedia!', 'error');
                return;
            }

            // Check if product already exists in cart array
            var existIndex = cart.findIndex(item => item.id_produk === id);
            if (existIndex !== -1) {
                var newQty = cart[existIndex].kuantitas + qty;
                if (newQty > stock) {
                    Swal.fire('Stok Kurang', 'Gagal update keranjang! Jumlah total (' + newQty + ') melebihi stok (' + stock + ').', 'error');
                    return;
                }
                cart[existIndex].kuantitas = newQty;
                cart[existIndex].subtotal = newQty * price;
            } else {
                // Insert new row into cart array
                cart.push({
                    id_produk: id,
                    kode_produk: code,
                    nama_produk: name,
                    harga_satuan: price,
                    kuantitas: qty,
                    subtotal: qty * price,
                    max_stock: stock
                });
            }

            // Reset selection fields
            select.val('').trigger('change');
            renderCartTable();
        });

        // Delete Row Item from Cart
        $(document).on('click', '.remove-item', function(e) {
            e.preventDefault();
            var index = $(this).data('index');
            cart.splice(index, 1);
            renderCartTable();
        });

        // On Change Quantity Input within Cart Table Row
        $(document).on('change', '.cart-qty-input', function() {
            var index = $(this).data('index');
            var qty = parseInt($(this).val());
            var item = cart[index];

            if (qty <= 0 || isNaN(qty)) {
                qty = 1;
                $(this).val(1);
            }

            if (qty > item.max_stock) {
                Swal.fire('Stok Terbatas', 'Stok maks "' + item.nama_produk + '" adalah ' + item.max_stock + '.', 'warning');
                qty = item.max_stock;
                $(this).val(qty);
            }

            cart[index].kuantitas = qty;
            cart[index].subtotal = qty * item.harga_satuan;
            renderCartTable();
        });

        // Clear Cart Button Click Handler
        $('#clearCart').on('click', function() {
            if (cart.length > 0) {
                Swal.fire({
                    title: 'Kosongkan Keranjang?',
                    text: "Semua antrean produk di keranjang belanja akan dihapus!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kosongkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        renderCartTable();
                    }
                });
            }
        });

        // Checkout Cart to Server via AJAX POST Request
        $('#btnCheckout').on('click', function(e) {
            e.preventDefault();
            if (cart.length === 0) {
                Swal.fire('Gagal', 'Keranjang kasir masih kosong! Tambahkan produk dahulu.', 'warning');
                return;
            }

            var grandTotal = calculateGrandTotal();

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah total belanja " + formatRupiah(grandTotal) + " sudah benar?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-check-lg"></i> Proses Pembayaran',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url("penjualan/store"); ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            no_faktur: noFaktur,
                            total_harga: grandTotal,
                            items: cart
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Transaksi Sukses!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Cetak / Lihat Faktur'
                                }).then(() => {
                                    // Redirect to sales history or invoice detail
                                    window.location.href = '<?= site_url("penjualan"); ?>';
                                });
                            } else {
                                Swal.fire('Gagal Checkout', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Fatal Error', 'Terjadi kegagalan komunikasi server.', 'error');
                        }
                    });
                }
            });
        });
    });

    /**
     * Render the Cart HTML table dynamically from the cart array
     */
    function renderCartTable() {
        var cartContainer = $('#cartItems');
        cartContainer.empty();

        if (cart.length === 0) {
            var emptyRow = '<tr class="empty-row">' +
                '<td colspan="5" class="text-center text-muted py-5">' +
                '<i class="bi bi-cart-x fs-1 text-secondary opacity-50 mb-3 d-block"></i>' +
                'Keranjang belanja kosong. Pilih produk di sebelah kanan untuk menambahkan.' +
                '</td>' +
                '</tr>';
            cartContainer.append(emptyRow);
            $('#grandTotalDisplay').text(formatRupiah(0));
            return;
        }

        $.each(cart, function(index, item) {
            var row = '<tr>' +
                '<td>' +
                '<strong class="text-dark d-block">' + htmlEscape(item.nama_produk) + '</strong>' +
                '<span class="text-secondary small">' + item.kode_produk + '</span>' +
                '</td>' +
                '<td class="text-end text-muted">' + formatRupiah(item.harga_satuan) + '</td>' +
                '<td class="text-center">' +
                '<div class="input-group input-group-sm mx-auto" style="max-width: 100px;">' +
                '<input type="number" min="1" max="' + item.max_stock + '" value="' + item.kuantitas + '" class="form-control text-center cart-qty-input" data-index="' + index + '">' +
                '</div>' +
                '</td>' +
                '<td class="text-end fw-semibold text-dark">' + formatRupiah(item.subtotal) + '</td>' +
                '<td class="text-center">' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-item" data-index="' + index + '"><i class="bi bi-trash"></i></button>' +
                '</td>' +
                '</tr>';
            cartContainer.append(row);
        });

        // Update Grand Total
        var grandTotal = calculateGrandTotal();
        $('#grandTotalDisplay').text(formatRupiah(grandTotal));
    }

    /**
     * Compute sum of item subtotals in cart
     */
    function calculateGrandTotal() {
        var total = 0;
        $.each(cart, function(index, item) {
            total += item.subtotal;
        });
        return total;
    }

    /**
     * Formatting helper for Indonesian currency IDR
     */
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(amount);
    }

    /**
     * Helper to escape HTML characters in dynamic strings
     */
    function htmlEscape(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }
</script>
