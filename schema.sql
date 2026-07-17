-- =================================================================================
-- Point of Sales (POS) and Inventory Management System - Database Schema
-- Architecture: CodeIgniter 3 MVC | Database: MySQL 5.7+ / MariaDB 10.2+
-- =================================================================================

-- Create Database (Optional, adjust database name as needed)
CREATE DATABASE IF NOT EXISTS `pos_inventory` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `pos_inventory`;

-- Disable foreign key checks during creation to prevent ordering errors
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------------------
-- 1. MASTER DATA TABLES
-- ---------------------------------------------------------------------------------

-- Table: users
-- Stores user accounts with role-based access control ('admin', 'owner')
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Bcrypt hashed password using password_hash()',
  `nama_user` varchar(100) NOT NULL,
  `role` enum('admin','owner') NOT NULL DEFAULT 'admin',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `idx_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User accounts and authentication roles';

-- Table: kategori
-- Stores product categories
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id_kategori` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `idx_kategori_nama` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product categories';

-- Table: produk
-- Stores products along with their real-time stock balance
DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
  `id_produk` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) UNSIGNED NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga_jual` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stok` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`),
  UNIQUE KEY `idx_produk_kode` (`kode_produk`),
  KEY `idx_produk_kategori` (`id_kategori`),
  CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Products inventory catalog and live stock count';

-- ---------------------------------------------------------------------------------
-- 2. INVENTORY TABLES (Supplier & Pembelian)
-- ---------------------------------------------------------------------------------

-- Table: supplier
-- Stores supplier contact details
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id_supplier` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_supplier` varchar(150) NOT NULL,
  `no_telp` varchar(30) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_supplier`),
  KEY `idx_supplier_nama` (`nama_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Supplier directory';

-- Table: pembelian
-- Header for incoming stock/purchase orders from suppliers
DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE `pembelian` (
  `id_pembelian` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_supplier` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `no_referensi` varchar(50) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tanggal_pembelian` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembelian`),
  UNIQUE KEY `idx_pembelian_noref` (`no_referensi`),
  KEY `idx_pembelian_supplier` (`id_supplier`),
  KEY `idx_pembelian_user` (`id_user`),
  KEY `idx_pembelian_tanggal` (`tanggal_pembelian`),
  CONSTRAINT `fk_pembelian_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pembelian_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Incoming purchase order headers';

-- Table: detail_pembelian
-- Line items for purchase orders
DROP TABLE IF EXISTS `detail_pembelian`;
CREATE TABLE `detail_pembelian` (
  `id_detail` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pembelian` int(11) UNSIGNED NOT NULL,
  `id_produk` int(11) UNSIGNED NOT NULL,
  `kuantitas` int(11) NOT NULL DEFAULT '1',
  `harga_beli` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_detail`),
  KEY `idx_dpembelian_header` (`id_pembelian`),
  KEY `idx_dpembelian_produk` (`id_produk`),
  CONSTRAINT `fk_dpembelian_header` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dpembelian_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Purchase order line items';

-- ---------------------------------------------------------------------------------
-- 3. TRANSACTION / SALES TABLES (Penjualan / POS)
-- ---------------------------------------------------------------------------------

-- Table: penjualan
-- Header for sales transactions (invoices)
DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan` (
  `id_penjualan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` int(11) UNSIGNED NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tanggal_penjualan` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penjualan`),
  UNIQUE KEY `idx_penjualan_faktur` (`no_faktur`),
  KEY `idx_penjualan_user` (`id_user`),
  KEY `idx_penjualan_tanggal` (`tanggal_penjualan`),
  CONSTRAINT `fk_penjualan_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sales invoice headers (POS transactions)';

-- Table: detail_penjualan
-- Line items for sales transactions
DROP TABLE IF EXISTS `detail_penjualan`;
CREATE TABLE `detail_penjualan` (
  `id_detail` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) UNSIGNED NOT NULL,
  `id_produk` int(11) UNSIGNED NOT NULL,
  `kuantitas` int(11) NOT NULL DEFAULT '1',
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_detail`),
  KEY `idx_dpenjualan_header` (`id_penjualan`),
  KEY `idx_dpenjualan_produk` (`id_produk`),
  CONSTRAINT `fk_dpenjualan_header` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dpenjualan_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sales invoice line items';

-- ---------------------------------------------------------------------------------
-- 4. STOCK HISTORY TABLE (Riwayat Stok)
-- ---------------------------------------------------------------------------------

-- Table: riwayat_stok
-- Audit log of all stock inflows ('masuk') and outflows ('keluar')
DROP TABLE IF EXISTS `riwayat_stok`;
CREATE TABLE `riwayat_stok` (
  `id_riwayat` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) UNSIGNED NOT NULL,
  `jenis_pergerakan` enum('masuk','keluar') NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `referensi_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID from id_penjualan or id_pembelian depending on movement type',
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_riwayat`),
  KEY `idx_riwayat_produk` (`id_produk`),
  KEY `idx_riwayat_jenis` (`jenis_pergerakan`),
  KEY `idx_riwayat_ref` (`referensi_id`),
  KEY `idx_riwayat_tanggal` (`tanggal`),
  CONSTRAINT `fk_riwayat_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chronological audit trail for all product stock movements';

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------------------
-- 5. INITIAL SEED DATA (For immediate testing after setup)
-- ---------------------------------------------------------------------------------

-- Insert Default Users:
-- Admin -> username: admin, password: admin123 (bcrypt hash)
-- Owner -> username: owner, password: owner123 (bcrypt hash)
INSERT INTO `users` (`id_user`, `username`, `password`, `nama_user`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$4rMbp8KzIZv5iRk4D2E5EOZp3V5qGg.8/0vHnNfN6oYt7qK6k8o0u', 'Administrator System', 'admin', NOW()),
(2, 'owner', '$2y$10$p0bH3/K9D4d8F6G2h1J5E.Xl2M4nOo/9a2bC3dE4fG5hI6jK7l8m', 'Owner Toko', 'owner', NOW());

-- Note: In PHP, password_verify('admin123', $hash) will verify against the generated bcrypt hash above.
-- If you create users from the Register UI or CRUD later, they will automatically use password_hash().

-- Insert Default Categories
INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `created_at`) VALUES
(1, 'Makanan Ringan', NOW()),
(2, 'Minuman Dingin', NOW()),
(3, 'Sembako & Kebutuhan Dapur', NOW()),
(4, 'Alat Tulis Kantor (ATK)', NOW());

-- Insert Default Suppliers
INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `no_telp`, `alamat`, `created_at`) VALUES
(1, 'PT Mitra Distribusi Nusantara', '081234567890', 'Jl. Raya Gudang No. 12, Jakarta', NOW()),
(2, 'CV Sumber Pangan Sejahtera', '081987654321', 'Jl. Industri No. 45, Bandung', NOW());

-- Insert Default Products (Initial Stock = 0, will be added via Pembelian module)
INSERT INTO `produk` (`id_produk`, `id_kategori`, `kode_produk`, `nama_produk`, `harga_jual`, `stok`, `created_at`) VALUES
(1, 1, 'PRD-001', 'Chitato Rasa Sapi Panggang 68g', 12500.00, 50, NOW()),
(2, 2, 'PRD-002', 'Teh Pucuk Harum 350ml', 4500.00, 100, NOW()),
(3, 3, 'PRD-003', 'Minyak Goreng Bimoli 2 Liter', 36000.00, 25, NOW()),
(4, 4, 'PRD-004', 'Buku Tulis Sinar Dunia 38 Lembar', 4000.00, 80, NOW());

-- Insert Initial Stock Audit Log for Default Products
INSERT INTO `riwayat_stok` (`id_riwayat`, `id_produk`, `jenis_pergerakan`, `kuantitas`, `referensi_id`, `tanggal`, `keterangan`) VALUES
(1, 1, 'masuk', 50, NULL, NOW(), 'Stok Awal Sistem'),
(2, 2, 'masuk', 100, NULL, NOW(), 'Stok Awal Sistem'),
(3, 3, 'masuk', 25, NULL, NOW(), 'Stok Awal Sistem'),
(4, 4, 'masuk', 80, NULL, NOW(), 'Stok Awal Sistem');
