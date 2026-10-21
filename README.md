# Sistem Informasi Data Mahasiswa Berbasis Jaringan

> **Proyek Sistem Jaringan Komputer — Kelompok 4**

Aplikasi ini mengelola data mahasiswa dan nilai melalui dua server yang terhubung lewat jaringan lokal. Kedua server menggunakan satu database MySQL yang berada di Laptop Server 1. Pengguna mengakses aplikasi melalui browser.

## Pembagian tugas tim (4 orang)

| Anggota | Bagian | Tanggung jawab |
|---|---|---|
| **zuli arifin** | Client | Mengakses Server 1 dan Server 2 lewat browser, memastikan halaman dapat dibuka dari jaringan yang sama, menguji alur penggunaan, dan mencatat hasil atau kendala. |
| **M.Maulfi fata al khair** | Server 1 dan database | Menjalankan Apache dan MySQL di Laptop 1, mengimpor database, serta mengelola aplikasi data mahasiswa: tambah, lihat, cari, ubah, dan hapus. |
| **Abadi** | Server 2 | Menjalankan aplikasi pengolahan nilai di Laptop 2, menghubungkannya ke database pada Laptop 1, dan memastikan nilai dapat dihitung serta disimpan. |
| **Nafra julian** | Integrasi jaringan | Mencatat alamat IP, menyelaraskan konfigurasi URL dan database, membantu pengaturan firewall, serta memastikan komunikasi antarperangkat berjalan. |

> Ganti “Anggota 1–4” dengan nama anggota kelompok. Anggota 1 adalah orang yang mendapat tugas client.

## Cara kerja sistem

```text
                         ┌── HTTP dari browser ──> Server 1: data mahasiswa ──┐
Client (browser) ────────┤                                                     ├──> Database db_kampus
                         └── HTTP dari browser ──> Server 2: pengolahan nilai ┘    di Laptop Server 1
```

- **Client** adalah browser pada laptop/HP pengguna. Client tidak menyimpan database dan tidak perlu membuat koneksi MySQL sendiri.
- **Server 1** menjalankan aplikasi PHP untuk mengelola tabel `mahasiswa`, sekaligus menjadi tempat MySQL.
- **Server 2** menjalankan aplikasi PHP untuk membaca data mahasiswa dan mengelola tabel `nilai`. Server 2 mengakses MySQL di Laptop Server 1 lewat jaringan.
- Tabel `nilai` terhubung ke tabel `mahasiswa` melalui NIM. Jika mahasiswa dihapus, data nilainya ikut terhapus karena aturan relasi database.

## Isi folder

```text
server1/       Aplikasi pengelolaan data mahasiswa
server2/       Aplikasi pengolahan nilai
Data base/
  db.sql       Pembuatan database, tabel, data contoh, dan akun akses Server 2
```

## Menjalankan di dua laptop dan satu client

### 1. Hubungkan perangkat ke jaringan yang sama

Hubungkan Laptop 1, Laptop 2, dan perangkat client ke Wi-Fi atau hotspot yang sama. Catat alamat IPv4 Laptop 1 dan Laptop 2. Di Windows, alamat dapat dilihat dengan menjalankan `ipconfig` dan mencari bagian **IPv4 Address**.

Contoh alamat (sesuaikan dengan jaringan kelompok):

- Laptop 1 / Server 1: `192.168.43.10`
- Laptop 2 / Server 2: `192.168.43.20`

### 2. Siapkan Laptop 1 (Server 1 dan database)

1. Instal XAMPP, lalu jalankan **Apache** dan **MySQL**.
2. Salin folder `server1` ke `C:\xampp\htdocs\server1`.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih **Import**, lalu pilih `Data base/db.sql` dari repositori.
5. Buka `server1/config.php`. Atur `SERVER2_URL` ke alamat Laptop 2, contohnya:

   ```php
   const SERVER2_URL = 'http://192.168.43.20/server2';
   ```

Database `db_kampus` dan akun `kelompok4` dibuat oleh skrip SQL. Akun tersebut dipakai Server 2 untuk koneksi jaringan ke database.

### 3. Siapkan Laptop 2 (Server 2)

1. Instal XAMPP, lalu jalankan **Apache**. MySQL di Laptop 2 tidak perlu dijalankan.
2. Salin folder `server2` ke `C:\xampp\htdocs\server2`.
3. Buka `server2/config.php`. Ganti IP pada `DB_HOST` dan `SERVER1_URL` dengan alamat Laptop 1:

   ```php
   const DB_HOST = '192.168.43.10';
   const DB_USER = 'kelompok4';
   const DB_PASS = 'kelompok4';
   const DB_NAME = 'db_kampus';

   const SERVER1_URL = 'http://192.168.43.10/server1';
   ```

   Jangan mengubah tanda kutip atau tanda titik koma pada konfigurasi PHP.

### 4. Akses sebagai client

Buka browser pada perangkat client dan kunjungi:

- Server 1: `http://192.168.43.10/server1/`
- Server 2: `http://192.168.43.20/server2/`

Gunakan IP aktual dari Laptop 1 dan Laptop 2, bukan IP contoh di atas. Browser mengirim permintaan ke server melalui HTTP; aplikasi di server yang membaca atau menyimpan data di database.

## Alur penggunaan

1. Tambahkan atau ubah data mahasiswa melalui Server 1.
2. Buka Server 2. Data mahasiswa dibaca dari database yang sama sehingga mahasiswa baru dapat muncul di sana.
3. Pilih **Input Nilai**, kemudian isi nilai tugas, UTS, dan UAS antara 0–100.
4. Server 2 menghitung nilai akhir sebagai rata-rata tiga nilai dan menentukan status.
5. Hasil disimpan pada tabel `nilai` di database `db_kampus`.

Rumus bawaan:

```text
Nilai akhir = (nilai tugas + nilai UTS + nilai UAS) / 3
LULUS jika nilai akhir >= 60; selain itu TIDAK LULUS
```

Contoh: nilai 80, 75, dan 85 menghasilkan nilai akhir 80. Batas lulus diatur sebagai `BATAS_LULUS` pada `server2/config.php`.

## Checklist pengujian client

- [ ] Browser client dapat membuka halaman Server 1.
- [ ] Browser client dapat membuka halaman Server 2.
- [ ] Data mahasiswa yang ditambahkan di Server 1 muncul di Server 2.
- [ ] Nilai 80, 75, dan 85 menghasilkan nilai akhir 80.
- [ ] Nilai 40, 50, dan 45 menghasilkan status **TIDAK LULUS**.
- [ ] Nilai yang disimpan tetap muncul setelah halaman dimuat ulang.
- [ ] Perubahan data mahasiswa terlihat saat halaman Server 2 dibuka atau dimuat ulang.

## Bantuan jika ada kendala

- **Browser tidak dapat membuka server:** pastikan ketiga perangkat tersambung ke jaringan yang sama, Apache berjalan, IP yang digunakan benar, dan akses port 80 diizinkan oleh Windows Firewall.
- **Server 2 gagal tersambung ke database:** pastikan MySQL Laptop 1 berjalan, `db.sql` sudah diimpor, `DB_HOST` berisi IP Laptop 1, dan port 3306 dapat diakses dari Laptop 2.
- **Server 2 belum menampilkan mahasiswa baru:** muat ulang halaman dan pastikan data berhasil tersimpan di Server 1.
- Untuk demo satu laptop, ikuti konfigurasi bawaan di file PHP dan petunjuk menjalankan server lokal dari pengajar/kelompok. Konfigurasi dua laptop di atas menggunakan folder XAMPP dan akun database jaringan.

## Catatan keamanan

Akun database dalam contoh ini disiapkan untuk praktikum pada jaringan lokal. Jangan gunakan kata sandi contoh untuk server yang terbuka ke internet. Batasi akses database dan firewall hanya untuk jaringan yang diperlukan.

