<?php
require_once __DIR__ . '/layout.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$data = ['nim' => '', 'nama' => '', 'prodi' => 'Ilmu Komputer', 'semester' => 1];
$error = '';

if ($id) {
    $st = db()->prepare('SELECT * FROM mahasiswa WHERE id = ?');
    $st->execute([$id]);
    $data = $st->fetch();
    if (!$data) { flash('error', 'Data tidak ditemukan.'); header('Location: index.php'); exit; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nim'      => trim($_POST['nim'] ?? ''),
        'nama'     => trim($_POST['nama'] ?? ''),
        'prodi'    => trim($_POST['prodi'] ?? ''),
        'semester' => (int)($_POST['semester'] ?? 0),
    ];
    if ($data['nim'] === '' || $data['nama'] === '' || $data['prodi'] === '') {
        $error = 'NIM, nama, dan program studi wajib diisi.';
    } elseif (!preg_match('/^[0-9A-Za-z]{1,20}$/', $data['nim'])) {
        $error = 'NIM hanya boleh huruf/angka (maks. 20 karakter).';
    } elseif ($data['semester'] < 1 || $data['semester'] > 14) {
        $error = 'Semester harus antara 1 sampai 14.';
    } else {
        try {
            if ($id) {
                $st = db()->prepare('UPDATE mahasiswa SET nim=?, nama=?, prodi=?, semester=? WHERE id=?');
                $st->execute([$data['nim'], $data['nama'], $data['prodi'], $data['semester'], $id]);
                flash('ok', 'Data mahasiswa berhasil diubah.');
            } else {
                $st = db()->prepare('INSERT INTO mahasiswa (nim, nama, prodi, semester) VALUES (?,?,?,?)');
                $st->execute([$data['nim'], $data['nama'], $data['prodi'], $data['semester']]);
                flash('ok', 'Data mahasiswa berhasil ditambahkan.');
            }
            header('Location: index.php'); exit;
        } catch (PDOException $ex) {
            $error = $ex->getCode() === '23000' ? 'NIM sudah terdaftar.' : 'Gagal menyimpan: ' . $ex->getMessage();
        }
    }
}

page_header($id ? 'Ubah Mahasiswa' : 'Tambah Mahasiswa');
?>
<section class="page-heading form-heading">
  <div>
    <span class="eyebrow"><span class="eyebrow-dot"></span> STUDENT MANAGEMENT</span>
    <h1><?= $id ? 'Ubah Data Mahasiswa' : 'Tambah Mahasiswa' ?></h1>
    <p><?= $id ? 'Perbarui informasi mahasiswa dengan data terbaru.' : 'Lengkapi formulir berikut untuk menambahkan mahasiswa baru.' ?></p>
  </div>
  <a class="btn btn-light" href="index.php">← Kembali ke daftar</a>
</section>

<?php if ($error): ?><div class="alert error"><span class="alert-symbol">!</span><span><?= e($error) ?></span></div><?php endif; ?>

<div class="form-layout">
  <aside class="form-aside">
    <div class="aside-icon">✦</div>
    <h3>Data yang akurat,<br>pengelolaan lebih mudah.</h3>
    <p>Pastikan informasi yang dimasukkan sudah benar sebelum menyimpan data.</p>
    <div class="aside-decoration">S</div>
  </aside>

  <form method="post" class="card form-card">
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="form-card-heading"><h2>Informasi Mahasiswa</h2><span><b>*</b> Wajib diisi</span></div>
    <label for="nim">NIM <b>*</b><input id="nim" name="nim" value="<?= e($data['nim']) ?>" maxlength="20" placeholder="Contoh: 20240001" required><small>Masukkan NIM, maksimal 20 karakter.</small></label>
    <label for="nama">Nama Lengkap <b>*</b><input id="nama" name="nama" value="<?= e($data['nama']) ?>" maxlength="100" placeholder="Masukkan nama lengkap" required></label>
    <label for="prodi">Program Studi <b>*</b><input id="prodi" name="prodi" value="<?= e($data['prodi']) ?>" maxlength="100" placeholder="Contoh: Ilmu Komputer" required></label>
    <label for="semester">Semester <b>*</b><input id="semester" type="number" name="semester" min="1" max="14" value="<?= e($data['semester']) ?>" placeholder="1 - 14" required><small>Isi angka semester antara 1 sampai 14.</small></label>
    <div class="form-actions"><a class="btn btn-light" href="index.php">Batal</a><button class="btn btn-primary" type="submit">✓ Simpan Data</button></div>
  </form>
</div>
<?php page_footer(); ?>
