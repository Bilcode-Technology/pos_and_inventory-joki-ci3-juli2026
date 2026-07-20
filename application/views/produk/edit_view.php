<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Produk</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open_multipart('produk/update/'.$produk->id_produk, ['class' => 'needs-validation']); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_produk" class="form-label fw-medium">Kode Produk (SKU)</label>
                            <input type="text" class="form-control" id="kode_produk" name="kode_produk" required value="<?= set_value('kode_produk', $produk->kode_produk); ?>">
                            <?= form_error('kode_produk', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="barcode" class="form-label fw-medium">Barcode (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Scan atau ketik barcode" value="<?= set_value('barcode', $produk->barcode); ?>">
                                <button type="button" class="btn btn-outline-primary" id="btnOpenScanner" title="Scan dengan Kamera">
                                    <i class="bi bi-camera-video"></i>
                                </button>
                            </div>
                            <?= form_error('barcode', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_kategori" class="form-label fw-medium">Kategori</label>
                            <select class="form-select" id="id_kategori" name="id_kategori" required>
                                <?php foreach ($kategori as $kat): ?>
                                    <option value="<?= $kat->id_kategori; ?>" <?= set_select('id_kategori', $kat->id_kategori, $kat->id_kategori == $produk->id_kategori); ?>><?= html_escape($kat->nama_kategori); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= form_error('id_kategori', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label fw-medium">Satuan (Pcs, Kg, dll)</label>
                            <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Contoh: Pcs" value="<?= set_value('satuan', $produk->satuan); ?>">
                            <?= form_error('satuan', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_produk" class="form-label fw-medium">Nama Produk</label>
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" required value="<?= set_value('nama_produk', $produk->nama_produk); ?>">
                        <?= form_error('nama_produk', '<div class="text-danger small mt-1">', '</div>'); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_jual" class="form-label fw-medium">Harga Jual (Rupiah)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="harga_jual" name="harga_jual" required value="<?= set_value('harga_jual', $produk->harga_jual); ?>">
                            </div>
                            <?= form_error('harga_jual', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stok_readonly" class="form-label fw-medium">Persediaan Stok <span class="text-muted small fw-normal">(Kunci / Read-Only)</span></label>
                            <input type="text" class="form-control bg-light" id="stok_readonly" disabled value="<?= $produk->stok; ?> Unit">
                            <div class="form-text text-info small mt-1"><i class="bi bi-info-circle"></i> Stok disesuaikan via transaksi.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="minimal_stok" class="form-label fw-medium">Minimal Stok</label>
                            <input type="number" min="0" class="form-control" id="minimal_stok" name="minimal_stok" value="<?= set_value('minimal_stok', $produk->minimal_stok); ?>">
                            <?= form_error('minimal_stok', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-medium">Status Produk</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif" <?= set_select('status', 'aktif', $produk->status == 'aktif'); ?>>Aktif</option>
                                <option value="nonaktif" <?= set_select('status', 'nonaktif', $produk->status == 'nonaktif'); ?>>Nonaktif</option>
                            </select>
                            <?= form_error('status', '<div class="text-danger small mt-1">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="gambar" class="form-label fw-medium">Gambar Produk <span class="text-muted small fw-normal">(Biarkan kosong jika tidak diganti)</span></label>
                        <?php if(!empty($produk->gambar)): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('assets/uploads/produk/'.$produk->gambar); ?>" alt="Gambar" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                        <input class="form-control" type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png">
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="<?= site_url('produk'); ?>" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Perbarui Produk</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Barcode Camera Scanner -->
<div class="modal fade" id="barcodeScannerModal" tabindex="-1" aria-labelledby="barcodeScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="barcodeScannerModalLabel">
                    <i class="bi bi-camera-video me-2"></i>Scan Barcode dengan Kamera
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div id="qr-reader" style="width:100%;"></div>
                <div class="p-3 text-center" id="scannerStatus">
                    <span class="text-white-50 small"><i class="bi bi-camera me-1"></i>Memuat kamera...</span>
                </div>
            </div>
            <div class="modal-footer justify-content-center bg-dark border-0">
                <p class="text-white-50 small mb-0 me-auto"><i class="bi bi-lightbulb me-1"></i>Pastikan cahaya cukup &amp; barcode terlihat jelas</p>
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- html5-qrcode Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var scanner = null;
    var isRunning = false;
    var modalEl = document.getElementById('barcodeScannerModal');

    document.getElementById('btnOpenScanner').addEventListener('click', function () {
        new bootstrap.Modal(modalEl).show();
    });

    modalEl.addEventListener('shown.bs.modal', function () {
        startScanner();
    });

    modalEl.addEventListener('hide.bs.modal', function () {
        stopScanner();
    });

    function startScanner() {
        if (isRunning) return;
        scanner = new Html5Qrcode('qr-reader');
        var config = {
            fps: 12,
            qrbox: { width: 260, height: 140 },
            aspectRatio: 1.6,
            formatsToSupport: [
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.CODE_93,
                Html5QrcodeSupportedFormats.QR_CODE,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.ITF
            ]
        };
        scanner.start(
            { facingMode: 'environment' },
            config,
            function onSuccess(decodedText) {
                document.getElementById('barcode').value = decodedText;
                document.getElementById('scannerStatus').innerHTML =
                    '<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Berhasil dibaca: <strong>' + decodedText + '</strong></span>';
                stopScanner();
                setTimeout(function () {
                    bootstrap.Modal.getInstance(modalEl).hide();
                }, 800);
            },
            function onError() { }
        ).then(function () {
            isRunning = true;
            document.getElementById('scannerStatus').innerHTML =
                '<span class="text-white-50 small"><i class="bi bi-camera-video-fill me-1"></i>Arahkan kamera ke barcode produk...</span>';
        }).catch(function (err) {
            isRunning = false;
            var msg = err ? err.toString() : '';
            var info = 'Kamera tidak dapat diakses.';
            if (msg.indexOf('NotAllowedError') !== -1 || msg.indexOf('Permission') !== -1) {
                info = 'Izin kamera ditolak. Izinkan akses kamera di browser, lalu coba lagi.';
            } else if (msg.indexOf('NotFoundError') !== -1) {
                info = 'Tidak ada kamera ditemukan pada perangkat ini.';
            }
            document.getElementById('scannerStatus').innerHTML =
                '<span class="text-warning small"><i class="bi bi-exclamation-triangle-fill me-1"></i>' + info + '</span>';
        });
    }

    function stopScanner() {
        if (scanner && isRunning) {
            scanner.stop().then(function () {
                scanner.clear();
                isRunning = false;
            }).catch(function () {
                isRunning = false;
            });
        }
    }
})();
</script>
