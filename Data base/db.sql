-- Satu database untuk kedua server
CREATE DATABASE IF NOT EXISTS db_kampus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_kampus;

-- Dikelola oleh Server 1
CREATE TABLE IF NOT EXISTS mahasiswa (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  nim      VARCHAR(20)  NOT NULL UNIQUE,
  nama     VARCHAR(100) NOT NULL,
  prodi    VARCHAR(100) NOT NULL,
  semester INT          NOT NULL
);

-- Diolah oleh Server 2 (terhubung ke mahasiswa lewat NIM)
CREATE TABLE IF NOT EXISTS nilai (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nim         VARCHAR(20)  NOT NULL UNIQUE,
  nilai_tugas DECIMAL(5,2) NOT NULL,
  nilai_uts   DECIMAL(5,2) NOT NULL,
  nilai_uas   DECIMAL(5,2) NOT NULL,
  nilai_akhir DECIMAL(5,2) NOT NULL,
  status      VARCHAR(20)  NOT NULL,
  FOREIGN KEY (nim) REFERENCES mahasiswa(nim) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT IGNORE INTO mahasiswa (nim, nama, prodi, semester) VALUES
('23210032', 'Contoh Mahasiswa 1', 'Ilmu Komputer', 5),
('23210045', 'Contoh Mahasiswa 2', 'Ilmu Komputer', 5);

-- Akun agar Server 2 (laptop lain) bisa mengakses database ini lewat jaringan
CREATE USER IF NOT EXISTS 'kelompok4'@'%' IDENTIFIED BY 'kelompok4';
GRANT ALL PRIVILEGES ON db_kampus.* TO 'kelompok4'@'%';
FLUSH PRIVILEGES;
