-- ============================================================
-- LAB BAC - Broken Access Control (Tugas Kuliah)
-- Nama: kikikokok
-- Database: lab_bac
-- CATATAN: khusus praktikum & keperluan belajar. JANGAN dipakai di server produksi.
-- ============================================================

DROP DATABASE IF EXISTS lab_bac;
CREATE DATABASE lab_bac CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lab_bac;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  role ENUM('admin','kasir','pelanggan') NOT NULL DEFAULT 'pelanggan',
  alamat VARCHAR(200),
  kartu VARCHAR(20) COMMENT 'Rahasia: 4 digit terakhir kartu'
) ENGINE=InnoDB;

CREATE TABLE produk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  harga INT NOT NULL,
  stok INT NOT NULL DEFAULT 10
) ENGINE=InnoDB;

CREATE TABLE pesanan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  kode VARCHAR(20) NOT NULL,
  total INT NOT NULL,
  tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- Akun demo (password di SIMPAN PLAINTEXT agar terlihat rawan — disengaja!)
INSERT INTO users (id, username, password, nama, role, alamat, kartu) VALUES
(1,'admin','admin123','Rizky Admin','admin','Jl. Merdeka 1','5241'),
(2,'kasir','kasir123','Sari Kasir','kasir','Jl. Melati 8','8890'),
(3,'budi','budi123','Budi Santoso','pelanggan','Jl. Mawar 3','1234'),
(4,'sari','sari123','Sari Melati','pelanggan','Jl. Kenanga 7','9012'),
(5,'riko','riko123','Riko Pratama','pelanggan','Jl. Anggrek 12','4587');

INSERT INTO produk (id, nama, harga, stok) VALUES
(1,'Beras Premium 5kg', 78000, 40),
(2,'Minyak Goreng 1L', 21000, 55),
(3,'Gula Pasir 1kg', 17000, 30),
(4,'Kopi Sachet (10)', 13000, 60),
(5,'Sabun Mandi', 4500, 80);

INSERT INTO pesanan (id, user_id, kode, total, tanggal) VALUES
(1,3,'INV-1001', 131000, '2026-08-03 09:12:00'),
(2,4,'INV-1002', 34000,  '2026-08-11 14:40:00'),
(3,3,'INV-1003', 42000,  '2026-08-20 10:05:00'),
(4,5,'INV-1004', 78000,  '2026-08-27 16:22:00'),
(5,3,'INV-1005', 17000,  '2026-09-02 08:55:00'),
(6,5,'INV-1006', 94500,  '2026-09-05 11:37:00');