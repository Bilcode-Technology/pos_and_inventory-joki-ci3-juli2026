<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <!-- Purchase Cart (Left Side / Wide) -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark fs-5"><i class="bi bi-truck text-primary me-2"></i> Keranjang Pembelian
                    Stok</span>
                <span class="badge bg-secondary-subtle text-secondary border">PO Ref:
                    <strong><?= $no_referensi; ?></strong></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="min-height: 350px;">
                    <table class="table align-middle table-hover mb-0" id="purchaseTable">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>Nama Produk</th>
                                <th style="width: 150px;" class="text-end">Harga Beli Satuan</th>
                                <th style="width: 130px;" class="text-center">Kuantitas</th>
                                <th style="width: 160px;" class="text-end">Subtotal</th>
                                <th style="width: 80px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="purchaseItems">
                            <tr class="empty-row">
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                                    Keranjang pembelian kosong. Pilih supplier dan masukkan produk di sebelah kanan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Grand Total Footer -->
                <div class="bg-light p-4 border-top">
                    <div class="row align-items-center">
                        <div class="col-sm-6 text-center text-sm-start mb-3 mb-sm-0">
                            <h5 class="text-secondary mb-1">TOTAL PEMBELIAN:</h5>
                            <h2 class="fw-extrabold text-primary mb-0" id="purchaseGrandTotalDisplay">Rp0,00</h2>
                        </div>
                        <div class="col-sm-6 text-center text-sm-end">
                            <button type="button" class="btn btn-outline-secondary btn-lg me-2"
                                id="clearPurchaseCart"><i class="bi bi-trash"></i> Reset</button>
                            <button type="button" class="btn btn-primary btn-lg px-4 shadow" id="btnSubmitPurchase"><i
                                    class="bi bi-save"></i> Simpan Pembelian</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pemasok & Product Selection (Right Side / Sidebar) -->
    <div class="col-lg-4 mb-4">
        <!-- Supplier Selection -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold text-dark"><i class="bi bi-buildings text-primary me-2"></i> Pemasok /
                    Supplier</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="supplierSelect" class="form-label small fw-semibold text-secondary">Pilih
                        Supplier</label>
                    <select class="form-select" id="supplierSelect">
                        <option value="">-- Pilih Supplier --</option>
                        <?php foreach ($supplier as $sup): ?>
                            <option value="<?= $sup->id_supplier; ?>"><?= html_escape($sup->nama_supplier); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="noReferensi" class="form-label small fw-semibold text-secondary">No. Referensi (Invoice
                        Supplier)</label>
                    <input type="text" class="form-control" id="noReferensi" value="<?= $no_referensi; ?>">
                </div>
                <div class="mb-0">
                    <label for="statusPembelian" class="form-label small fw-semibold text-secondary">Status Pembelian</label>
                    <select class="form-select" id="statusPembelian">
                        <option value="pending">Pending (Belum Lunas/Terkirim)</option>
                        <option value="selesai" selected>Selesai (Masuk Gudang)</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Cart Addition -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold text-dark"><i class="bi bi-search text-primary me-2"></i> Pilih Barang Masuk</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="purchaseBarcodeScanner" class="form-label small fw-semibold text-secondary"><i class="bi bi-upc-scan"></i> Scan Barcode Produk</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-primary" id="purchaseBarcodeScanner" placeholder="Scan barcode disini (Enter)..." autocomplete="off" autofocus>
                        <button type="button" class="btn btn-primary" id="btnOpenPurchaseScanner" title="Scan dengan Kamera">
                            <i class="bi bi-camera-video"></i> Kamera
                        </button>
                    </div>
                </div>
                <div class="mb-3 text-center text-muted small fw-semibold">- ATAU PENCARIAN MANUAL -</div>

                <div class="mb-3">
                    <label for="purchaseProductSelect"
                        class="form-label small fw-semibold text-secondary">Produk</label>
                    <select class="form-select" id="purchaseProductSelect">
                        <option value="">-- Pilih produk --</option>
                        <?php foreach ($produk as $prd): ?>
                            <option value="<?= $prd->id_produk; ?>" data-code="<?= html_escape($prd->kode_produk); ?>"
                                data-name="<?= html_escape($prd->nama_produk); ?>"
                                data-sell-price="<?= $prd->harga_jual; ?>" data-stock="<?= $prd->stok; ?>">
                                <?= html_escape($prd->kode_produk); ?> - <?= html_escape($prd->nama_produk); ?>
                                (Stok: <?= $prd->stok; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <label for="purchaseQty" class="form-label small fw-semibold text-secondary">Kuantitas
                            Masuk</label>
                        <input type="number" min="1" value="1" class="form-control" id="purchaseQty">
                    </div>
                    <div class="col-md-6">
                        <label for="purchasePrice" class="form-label small fw-semibold text-secondary">Harga Beli
                            Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" min="0" step="0.01" value="0" class="form-control" id="purchasePrice">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm" id="addPurchaseToCart"><i
                        class="bi bi-plus-lg"></i> Tambahkan Ke Keranjang</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Purchase cart arrays
    var pCart = [];

    // Defined at global scope so it can be called from the camera scanner IIFE
    function addProductToPurchaseCart(id, code, name, buyPrice, qty) {
        id = parseInt(id);
        qty = parseInt(qty);
        buyPrice = parseFloat(buyPrice);

        if (qty <= 0 || isNaN(qty)) {
            Swal.fire('Jumlah Salah', 'Jumlah stok masuk minimal adalah 1.', 'warning');
            return;
        }

        if (buyPrice < 0 || isNaN(buyPrice)) {
            Swal.fire('Harga Salah', 'Harga beli satuan tidak boleh bernilai minus!', 'warning');
            return;
        }

        // Check if product already exists in purchase cart
        var existIndex = pCart.findIndex(item => item.id_produk === id);
        if (existIndex !== -1) {
            var newQty = pCart[existIndex].kuantitas + qty;
            pCart[existIndex].kuantitas = newQty;
            pCart[existIndex].harga_beli = buyPrice;
            pCart[existIndex].subtotal = newQty * buyPrice;
        } else {
            pCart.push({
                id_produk: id,
                kode_produk: code,
                nama_produk: name,
                harga_beli: buyPrice,
                kuantitas: qty,
                subtotal: qty * buyPrice
            });
        }
        renderPurchaseCart();
    }

    $(document).ready(function () {
        // Auto default buy price based on selling price or 0
        $('#purchaseProductSelect').on('change', function () {
            var selected = $(this).find('option:selected');
            if (selected.val() !== "") {
                var sellPrice = parseFloat(selected.data('sell-price'));
                var estBuyPrice = Math.round(sellPrice * 0.8);
                $('#purchasePrice').val(estBuyPrice);
                $('#purchaseQty').val(1);
            } else {
                $('#purchasePrice').val(0);
                $('#purchaseQty').val(1);
            }
        });

        // Add Product To Purchase Cart Array
        $('#addPurchaseToCart').on('click', function (e) {
            e.preventDefault();
            var select = $('#purchaseProductSelect');
            var selected = select.find('option:selected');

            if (select.val() === "") {
                Swal.fire('Perhatian', 'Silakan pilih produk terlebih dahulu!', 'warning');
                return;
            }

            var id = select.val();
            var code = selected.data('code');
            var name = selected.data('name');
            var buyPrice = $('#purchasePrice').val();
            var qty = $('#purchaseQty').val();

            addProductToPurchaseCart(id, code, name, buyPrice, qty);

            // Reset selection fields
            select.val('').trigger('change');
            $('#purchasePrice').val(0);
            $('#purchaseQty').val(1);
        });

        // Handle Barcode Scanner
        $('#purchaseBarcodeScanner').on('keypress', function (e) {
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
                            var estBuyPrice = Math.round(parseFloat(prd.harga_jual) * 0.8);
                            addProductToPurchaseCart(prd.id_produk, prd.kode_produk, prd.nama_produk, estBuyPrice, 1);
                            $('#purchaseBarcodeScanner').val(''); // clear input
                        } else {
                            Swal.fire('Tidak Ditemukan', response.message, 'warning');
                            $('#purchaseBarcodeScanner').val(''); // clear input
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal memanggil data barcode.', 'error');
                    }
                });
            }
        });

        // Remove row item from purchase cart
        $(document).on('click', '.remove-purchase-item', function (e) {
            e.preventDefault();
            var index = $(this).data('index');
            pCart.splice(index, 1);
            renderPurchaseCart();
        });

        // Quantity manual input change in cart row
        $(document).on('change', '.p-qty-input', function () {
            var index = $(this).data('index');
            var qty = parseInt($(this).val());
            var item = pCart[index];

            if (qty <= 0 || isNaN(qty)) {
                qty = 1;
                $(this).val(1);
            }

            pCart[index].kuantitas = qty;
            pCart[index].subtotal = qty * item.harga_beli;
            renderPurchaseCart();
        });

        // Buying price manual input change in cart row
        $(document).on('change', '.p-price-input', function () {
            var index = $(this).data('index');
            var price = parseFloat($(this).val());
            var item = pCart[index];

            if (price < 0 || isNaN(price)) {
                price = 0;
                $(this).val(0);
            }

            pCart[index].harga_beli = price;
            pCart[index].subtotal = item.kuantitas * price;
            renderPurchaseCart();
        });

        // Reset Purchase Cart
        $('#clearPurchaseCart').on('click', function () {
            if (pCart.length > 0) {
                Swal.fire({
                    title: 'Kosongkan Keranjang?',
                    text: "Semua antrean produk di keranjang pembelian akan dihapus!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kosongkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        pCart = [];
                        renderPurchaseCart();
                    }
                });
            }
        });

        // Submit Purchase to Server via AJAX POST Request
        $('#btnSubmitPurchase').on('click', function (e) {
            e.preventDefault();
            var supplierId = $('#supplierSelect').val();
            var refNo = $('#noReferensi').val().trim();

            if (supplierId === "") {
                Swal.fire('Supplier Belum Dipilih', 'Silakan pilih supplier/pemasok barang terlebih dahulu!', 'warning');
                return;
            }

            if (refNo === "") {
                Swal.fire('No Referensi Kosong', 'Silakan masukkan nomor referensi (faktur supplier)!', 'warning');
                return;
            }

            if (pCart.length === 0) {
                Swal.fire('Gagal', 'Keranjang pembelian masih kosong!', 'warning');
                return;
            }

            var grandTotal = calculatePurchaseGrandTotal();

            Swal.fire({
                title: 'Konfirmasi Pembelian',
                text: "Simpan pembelian ini senilai " + formatRupiah(grandTotal) + " ke gudang stok?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-save"></i> Ya, Simpan Transaksi',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url("pembelian/store"); ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_supplier: supplierId,
                            no_referensi: refNo,
                            status: $('#statusPembelian').val(),
                            total_harga: grandTotal,
                            items: pCart
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Transaksi Disimpan!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Selesai'
                                }).then(() => {
                                    window.location.href = '<?= site_url("pembelian"); ?>';
                                });
                            } else {
                                Swal.fire('Gagal Menyimpan', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Fatal Error', 'Terjadi kegagalan komunikasi server.', 'error');
                        }
                    });
                }
            });
        });
    });

    /**
     * Render the Purchase Cart Table HTML dynamically
     */
    function renderPurchaseCart() {
        var cartContainer = $('#purchaseItems');
        cartContainer.empty();

        if (pCart.length === 0) {
            var emptyRow = '<tr class="empty-row">' +
                '<td colspan="5" class="text-center text-muted py-5">' +
                '<i class="bi bi-inbox fs-1 text-secondary opacity-50 mb-3 d-block"></i>' +
                'Keranjang pembelian kosong. Pilih supplier dan masukkan produk di sebelah kanan.' +
                '</td>' +
                '</tr>';
            cartContainer.append(emptyRow);
            $('#purchaseGrandTotalDisplay').text(formatRupiah(0));
            return;
        }

        $.each(pCart, function (index, item) {
            var row = '<tr>' +
                '<td>' +
                '<strong class="text-dark d-block">' + htmlEscape(item.nama_produk) + '</strong>' +
                '<span class="text-secondary small">' + item.kode_produk + '</span>' +
                '</td>' +
                '<td class="text-end">' +
                '<div class="input-group input-group-sm ms-auto" style="max-width: 150px;">' +
                '<span class="input-group-text">Rp</span>' +
                '<input type="number" min="0" value="' + item.harga_beli + '" class="form-control text-end p-price-input" data-index="' + index + '">' +
                '</div>' +
                '</td>' +
                '<td class="text-center">' +
                '<div class="input-group input-group-sm mx-auto" style="max-width: 90px;">' +
                '<input type="number" min="1" value="' + item.kuantitas + '" class="form-control text-center p-qty-input" data-index="' + index + '">' +
                '</div>' +
                '</td>' +
                '<td class="text-end fw-semibold text-dark">' + formatRupiah(item.subtotal) + '</td>' +
                '<td class="text-center">' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-purchase-item" data-index="' + index + '"><i class="bi bi-trash"></i></button>' +
                '</td>' +
                '</tr>';
            cartContainer.append(row);
        });

        var grandTotal = calculatePurchaseGrandTotal();
        $('#purchaseGrandTotalDisplay').text(formatRupiah(grandTotal));
    }

    /**
     * Compute sum of item subtotals in purchase cart
     */
    function calculatePurchaseGrandTotal() {
        var total = 0;
        $.each(pCart, function (index, item) {
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

<!-- Modal: Barcode Camera Scanner (Pembelian) -->
<div class="modal fade" id="purchaseScannerModal" tabindex="-1" aria-labelledby="purchaseScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="purchaseScannerModalLabel">
                    <i class="bi bi-camera-video me-2"></i>Scan Barcode — Pembelian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div id="purchase-qr-reader" style="width:100%;"></div>
                <div class="p-3 text-center" id="purchaseScannerStatus">
                    <span class="text-white-50 small"><i class="bi bi-camera me-1"></i>Memuat kamera...</span>
                </div>
            </div>
            <div class="modal-footer bg-dark border-0">
                <p class="text-white-50 small mb-0 me-auto"><i class="bi bi-info-circle me-1"></i>Produk langsung ditambahkan ke keranjang saat terdeteksi</p>
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var pScanner = null;
    var pIsRunning = false;
    var pLastScanned = '';
    var pScanCooldown = false;
    var pModalEl = document.getElementById('purchaseScannerModal');

    document.getElementById('btnOpenPurchaseScanner').addEventListener('click', function () {
        new bootstrap.Modal(pModalEl).show();
    });

    pModalEl.addEventListener('shown.bs.modal', function () { startPurchaseScanner(); });
    pModalEl.addEventListener('hide.bs.modal', function () { stopPurchaseScanner(); });

    function startPurchaseScanner() {
        if (pIsRunning) return;
        pScanner = new Html5Qrcode('purchase-qr-reader');
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
        pScanner.start(
            { facingMode: 'environment' },
            config,
            function onSuccess(decodedText) {
                if (pScanCooldown || decodedText === pLastScanned) return;
                pLastScanned = decodedText;
                pScanCooldown = true;

                document.getElementById('purchaseScannerStatus').innerHTML =
                    '<span class="text-info small"><i class="bi bi-arrow-repeat me-1"></i>Mencari produk: <strong>' + decodedText + '</strong>...</span>';

                $.ajax({
                    url: '<?= site_url("produk/ajax_get_barcode"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { barcode: decodedText },
                    success: function (response) {
                        if (response.status === 'success') {
                            var prd = response.data;
                            var estBuyPrice = Math.round(parseFloat(prd.harga_jual) * 0.8);
                            addProductToPurchaseCart(prd.id_produk, prd.kode_produk, prd.nama_produk, estBuyPrice, 1);
                            document.getElementById('purchaseScannerStatus').innerHTML =
                                '<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Ditambahkan: <strong>' + prd.nama_produk + '</strong></span>';
                        } else {
                            document.getElementById('purchaseScannerStatus').innerHTML =
                                '<span class="text-warning"><i class="bi bi-exclamation-triangle-fill me-1"></i>' + response.message + '</span>';
                        }
                        setTimeout(function () {
                            pScanCooldown = false;
                            pLastScanned = '';
                            document.getElementById('purchaseScannerStatus').innerHTML =
                                '<span class="text-white-50 small"><i class="bi bi-camera-video-fill me-1"></i>Siap scan berikutnya...</span>';
                        }, 2000);
                    },
                    error: function () {
                        document.getElementById('purchaseScannerStatus').innerHTML =
                            '<span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Gagal menghubungi server.</span>';
                        setTimeout(function () { pScanCooldown = false; pLastScanned = ''; }, 2000);
                    }
                });
            },
            function onError() { }
        ).then(function () {
            pIsRunning = true;
            document.getElementById('purchaseScannerStatus').innerHTML =
                '<span class="text-white-50 small"><i class="bi bi-camera-video-fill me-1"></i>Arahkan kamera ke barcode produk...</span>';
        }).catch(function (err) {
            pIsRunning = false;
            var msg = err ? err.toString() : '';
            var info = msg.indexOf('NotAllowedError') !== -1 ? 'Izin kamera ditolak. Izinkan akses kamera di browser.' :
                       msg.indexOf('NotFoundError') !== -1  ? 'Tidak ada kamera pada perangkat ini.' :
                       'Kamera tidak dapat diakses.';
            document.getElementById('purchaseScannerStatus').innerHTML =
                '<span class="text-warning small"><i class="bi bi-exclamation-triangle-fill me-1"></i>' + info + '</span>';
        });
    }

    function stopPurchaseScanner() {
        if (pScanner && pIsRunning) {
            pScanner.stop().then(function () { pScanner.clear(); pIsRunning = false; }).catch(function () { pIsRunning = false; });
        }
    }
})();
</script>