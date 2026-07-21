<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <!-- POS Cart Column (Left Side / Wide) -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark fs-5"><i class="bi bi-cart3 text-primary me-2"></i> Keranjang Kasir</span>
                <span class="badge bg-secondary-subtle text-secondary border">Faktur: <strong><?= $kode_transaksi; ?></strong></span>
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
                <!-- Cart Options -->
                <div class="p-3 border-top bg-white">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold">Nama Pembeli (Opsional)</label>
                            <input type="text" class="form-control form-control-sm" id="namaPembeli" placeholder="Umum">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold">Metode Pembayaran</label>
                            <select class="form-select form-select-sm" id="metodeTransaksi">
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS / E-Wallet</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold">Diskon (Rp)</label>
                            <input type="number" class="form-control form-control-sm" id="diskonPos" value="0" min="0">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold">Pajak (Rp)</label>
                            <input type="number" class="form-control form-control-sm" id="pajakPos" value="0" min="0">
                        </div>
                    </div>
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
                    <label for="posBarcodeScanner" class="form-label small fw-semibold text-secondary"><i class="bi bi-upc-scan"></i> Scan Barcode Produk</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-primary" id="posBarcodeScanner" placeholder="Scan barcode disini (Enter)..." autocomplete="off" autofocus>
                        <button type="button" class="btn btn-primary" id="btnOpenPosScanner" title="Scan dengan Kamera">
                            <i class="bi bi-camera-video"></i> Kamera
                        </button>
                    </div>
                </div>
                <div class="mb-3 text-center text-muted small fw-semibold">- ATAU PENCARIAN MANUAL -</div>

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
    var noFaktur = '<?= $kode_transaksi; ?>';

    // Defined at global scope so it can be called from the camera scanner IIFE
    function addProductToCart(id, code, name, price, stock, qty) {
        id = parseInt(id);
        qty = parseInt(qty);
        price = parseFloat(price);
        stock = parseInt(stock);

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
        renderCartTable();
    }

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

            var id = select.val();
            var code = selected.data('code');
            var name = selected.data('name');
            var price = selected.data('price');
            var stock = selected.data('stock');
            var qty = $('#productQty').val();

            addProductToCart(id, code, name, price, stock, qty);

            // Reset selection fields
            select.val('').trigger('change');
            $('#productQty').val(1);
            $('#productStockDisplay').val(0);
        });

        // Handle Barcode Scanner
        $('#posBarcodeScanner').on('keypress', function (e) {
            if (e.which == 13) { // Enter key pressed
                e.preventDefault();
                var barcode = $(this).val().trim();
                if (barcode === "") return;

                $.ajax({
                    url: '<?= site_url("produk/ajax_get_barcode"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { barcode: barcode },
                    success: function (response) {
                        if (response.status === 'success') {
                            var prd = response.data;
                            if (parseInt(prd.stok) <= 0) {
                                Swal.fire('Stok Kosong', 'Stok produk "' + prd.nama_produk + '" habis!', 'error');
                            } else {
                                addProductToCart(prd.id_produk, prd.kode_produk, prd.nama_produk, prd.harga_jual, prd.stok, 1);
                            }
                            $('#posBarcodeScanner').val(''); // clear input
                        } else {
                            Swal.fire('Tidak Ditemukan', response.message, 'warning');
                            $('#posBarcodeScanner').val(''); // clear input
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal memanggil data barcode.', 'error');
                    }
                });
            }
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
                            kode_transaksi: noFaktur,
                            total_harga: calculateSubTotal(),
                            grand_total: grandTotal,
                            diskon: parseFloat($('#diskonPos').val()) || 0,
                            pajak: parseFloat($('#pajakPos').val()) || 0,
                            nama_pembeli: $('#namaPembeli').val(),
                            metode_transaksi: $('#metodeTransaksi').val(),
                            status: 'selesai',
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
    function calculateSubTotal() {
        var total = 0;
        $.each(cart, function(index, item) {
            total += item.subtotal;
        });
        return total;
    }

    function calculateGrandTotal() {
        var sub = calculateSubTotal();
        var diskon = parseFloat($('#diskonPos').val()) || 0;
        var pajak = parseFloat($('#pajakPos').val()) || 0;
        return sub - diskon + pajak;
    }

    // Attach listener to diskon and pajak inputs to rerender grand total
    $('#diskonPos, #pajakPos').on('input change', function() {
        var gt = calculateGrandTotal();
        $('#grandTotalDisplay').text(formatRupiah(gt));
    });

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

<!-- Modal: Barcode Camera Scanner (POS Kasir) -->
<div class="modal fade" id="posScannerModal" tabindex="-1" aria-labelledby="posScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="posScannerModalLabel">
                    <i class="bi bi-camera-video me-2"></i>Scan Barcode — Kasir POS
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div id="pos-qr-reader" style="width:100%;"></div>
                <div class="p-3 text-center" id="posScannerStatus">
                    <span class="text-white-50 small"><i class="bi bi-camera me-1"></i>Memuat kamera...</span>
                </div>
            </div>
            <div class="modal-footer bg-dark border-0">
                <p class="text-white-50 small mb-0 me-auto"><i class="bi bi-info-circle me-1"></i>Produk langsung masuk keranjang saat barcode terdeteksi</p>
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup Scanner
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var posScanner = null;
    var posIsRunning = false;
    var posLastScanned = '';
    var posScanCooldown = false;
    var posModalEl = document.getElementById('posScannerModal');

    document.getElementById('btnOpenPosScanner').addEventListener('click', function () {
        new bootstrap.Modal(posModalEl).show();
    });

    posModalEl.addEventListener('shown.bs.modal', function () { startPosScanner(); });
    posModalEl.addEventListener('hide.bs.modal', function () { stopPosScanner(); });

    function startPosScanner() {
        if (posIsRunning) return;
        posScanner = new Html5Qrcode('pos-qr-reader');
        var config = {
            fps: 12,
            qrbox: { width: 260, height: 140 },
            aspectRatio: 1.6,
            formatsToSupport: [
                Html5QrcodeSupportedFormats.EAN_13, Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.CODE_128, Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.CODE_93, Html5QrcodeSupportedFormats.QR_CODE,
                Html5QrcodeSupportedFormats.UPC_A, Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.ITF
            ]
        };
        posScanner.start(
            { facingMode: 'environment' },
            config,
            function onSuccess(decodedText) {
                if (posScanCooldown || decodedText === posLastScanned) return;
                posLastScanned = decodedText;
                posScanCooldown = true;

                document.getElementById('posScannerStatus').innerHTML =
                    '<span class="text-info small"><i class="bi bi-arrow-repeat me-1"></i>Mencari produk: <strong>' + decodedText + '</strong>...</span>';

                $.ajax({
                    url: '<?= site_url("produk/ajax_get_barcode"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { barcode: decodedText },
                    success: function (response) {
                        if (response.status === 'success') {
                            var prd = response.data;
                            if (parseInt(prd.stok) <= 0) {
                                document.getElementById('posScannerStatus').innerHTML =
                                    '<span class="text-warning fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Stok "' + prd.nama_produk + '" habis!</span>';
                            } else {
                                addProductToCart(prd.id_produk, prd.kode_produk, prd.nama_produk, prd.harga_jual, prd.stok, 1);
                                document.getElementById('posScannerStatus').innerHTML =
                                    '<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Ditambahkan: <strong>' + prd.nama_produk + '</strong></span>';
                            }
                        } else {
                            document.getElementById('posScannerStatus').innerHTML =
                                '<span class="text-warning"><i class="bi bi-exclamation-triangle-fill me-1"></i>' + response.message + '</span>';
                        }
                        setTimeout(function () {
                            posScanCooldown = false;
                            posLastScanned = '';
                            document.getElementById('posScannerStatus').innerHTML =
                                '<span class="text-white-50 small"><i class="bi bi-camera-video-fill me-1"></i>Siap scan berikutnya...</span>';
                        }, 2000);
                    },
                    error: function () {
                        document.getElementById('posScannerStatus').innerHTML =
                            '<span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Gagal menghubungi server.</span>';
                        setTimeout(function () { posScanCooldown = false; posLastScanned = ''; }, 2000);
                    }
                });
            },
            function onError() { }
        ).then(function () {
            posIsRunning = true;
            document.getElementById('posScannerStatus').innerHTML =
                '<span class="text-white-50 small"><i class="bi bi-camera-video-fill me-1"></i>Arahkan kamera ke barcode produk...</span>';
        }).catch(function (err) {
            posIsRunning = false;
            var msg = err ? err.toString() : '';
            var info = msg.indexOf('NotAllowedError') !== -1 ? 'Izin kamera ditolak. Izinkan akses kamera di browser.' :
                       msg.indexOf('NotFoundError') !== -1  ? 'Tidak ada kamera pada perangkat ini.' :
                       'Kamera tidak dapat diakses.';
            document.getElementById('posScannerStatus').innerHTML =
                '<span class="text-warning small"><i class="bi bi-exclamation-triangle-fill me-1"></i>' + info + '</span>';
        });
    }

    function stopPosScanner() {
        if (posScanner && posIsRunning) {
            posScanner.stop().then(function () { posScanner.clear(); posIsRunning = false; }).catch(function () { posIsRunning = false; });
        }
    }
})();
</script>
