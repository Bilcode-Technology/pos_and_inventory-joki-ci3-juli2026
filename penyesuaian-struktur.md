Penyesuaian ERD
produk:
- harga_beli > dari tabel pembelian
- minimal_stok
- satuan
- barcode
- gambar
- status, enum : aktif, nonaktif

users:
- role, tambahkan 1 daftar enum : kasir

transaksi > penjualan:
- kode_transaksi > no_faktur
- tanggal > tanggal_penjualan
- id_member *belum ada fitur (request dari client)
- nama_pembeli *?
- diskon
- pajak
- total_harga
- grand_total
- metode_transaksi, enum : tunai, qris, transfer
- status, enum : selesai, batal

detail_transaksi > detail_penjualan:
- id_transaksi > id_penjualan
- tipe_item, enum : produk, paket
- id_paket *belum ada fitur (request dari client)
- qty > kuantitas
- harga > harga_satuan
- diskon_item

pembelian:
- tanggal > tanggal_pembelian
- total > total_harga
- status

detail_pembelian:
- id_detail_pembelian > id_detail
- qty > kuantitassekaran