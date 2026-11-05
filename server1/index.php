<?php
require_once __DIR__ . '/layout.php';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $st = db()->prepare('SELECT * FROM mahasiswa WHERE nim LIKE ? OR nama LIKE ? ORDER BY nim');
    $st->execute(["%$q%", "%$q%"]);
} else {
    $st = db()->query('SELECT * FROM mahasiswa ORDER BY nim');
}
$rows = $st->fetchAll();

page_header('Data Mahasiswa');
?>
<section class="page-heading">
  <div>
    <span class="eyebrow"><span class="eyebrow-dot"></span> STUDENT MANAGEMENT</span>
    <h1>Data Mahasiswa</h1>
    <p>Kelola dan pantau informasi mahasiswa dalam satu tempat.</p>
  </div>
  <a class="btn btn-primary" href="form.php"><span class="button-icon">＋</span> Tambah Mahasiswa</a>
</section>

<section class="summary-card">
  <div class="summary-icon">♧</div>
  <div><span class="summary-label">Total data mahasiswa</span><strong class="summary-number"><?= count($rows) ?></strong></div>
  <span class="summary-note"><?= $q !== '' ? 'Hasil pencarian' : 'Data terdaftar' ?></span>
</section>

<section class="panel">
  <div class="panel-heading">
    <div><h2>Daftar Mahasiswa</h2><p>Informasi mahasiswa yang tersimpan di sistem.</p></div>
    <form method="get" class="search">
      <span class="search-icon">⌕</span>
      <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari NIM atau nama..." aria-label="Cari NIM atau nama">
      <?php if ($q !== ''): ?><a class="clear-search" href="index.php" aria-label="Hapus pencarian">×</a><?php endif; ?>
      <button class="btn btn-search">Cari</button>
    </form>
  </div>

  <div class="table-wrap">
    <table>
      <thead><tr><th class="number-col">NO</th><th>NIM</th><th>NAMA MAHASISWA</th><th>PROGRAM STUDI</th><th>SEMESTER</th><th class="action-col">AKSI</th></tr></thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="6" class="empty"><span class="empty-icon">⌕</span><strong><?= $q !== '' ? 'Data tidak ditemukan' : 'Belum ada data mahasiswa' ?></strong><small><?= $q !== '' ? 'Coba gunakan kata kunci lain.' : 'Mulai dengan menambahkan data mahasiswa pertama.' ?></small></td></tr>
      <?php endif; foreach ($rows as $i => $r): ?>
        <tr>
          <td class="number-cell"><?= $i + 1 ?></td>
          <td><span class="nim-chip"><?= e($r['nim']) ?></span></td>
          <td><div class="student-cell"><span class="student-avatar"><?= e(strtoupper(substr(trim($r['nama']), 0, 1))) ?></span><strong><?= e($r['nama']) ?></strong></div></td>
          <td><?= e($r['prodi']) ?></td>
          <td><span class="semester-pill">Semester <?= e($r['semester']) ?></span></td>
          <td><div class="aksi">
            <a class="btn btn-edit" href="form.php?id=<?= (int)$r['id'] ?>">✎ <span>Ubah</span></a>
            <form method="post" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn btn-delete" type="submit">⌫ <span>Hapus</span></button></form>
          </div></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="panel-footer"><span>Menampilkan <strong><?= count($rows) ?></strong> data</span><span class="status-indicator"><i></i> Sistem aktif</span></div>
</section>
<?php page_footer(); ?>
