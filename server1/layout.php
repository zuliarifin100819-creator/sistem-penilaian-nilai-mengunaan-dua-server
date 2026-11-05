<?php
require_once __DIR__ . '/config.php';
session_start();

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function flash(string $type, string $msg): void { $_SESSION['flash'] = [$type, $msg]; }

function page_header(string $title): void {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1">'
       . '<meta name="theme-color" content="#101b35">'
       . '<title>' . e($title) . ' - StudentHub</title>'
       . '<link rel="preconnect" href="https://fonts.googleapis.com">'
       . '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'
       . '<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">'
       . '<link rel="stylesheet" href="style.css"></head><body>'
       . '<div class="site-shell">'
       . '<header class="topbar"><div class="wrap topbar-inner">'
       . '<a class="brand" href="index.php"><span class="brand-mark">S</span><span class="brand-copy"><strong>StudentHub</strong><small>Student data center</small></span></a>'
       . '<nav class="main-nav">'
       . '<a class="nav-link" href="index.php"><span class="nav-icon">⌂</span> Data Mahasiswa</a>'
       . '<a class="nav-link" href="form.php"><span class="nav-icon">＋</span> Tambah Data</a>'
       . '<a class="nav-link nav-external" href="' . e(SERVER2_URL) . '/index.php" target="_blank" rel="noopener noreferrer"><span class="nav-icon">↗</span> Server 2</a>'
       . '</nav></div></header><main class="wrap page-content">';

    if ($f) {
        echo '<div class="alert ' . e($f[0]) . '"><span class="alert-symbol">' . ($f[0] === 'ok' ? '✓' : '!') . '</span><span>' . e($f[1]) . '</span></div>';
    }
}

function page_footer(): void {
    echo '</main><footer class="wrap footer"><span>© ' . date('Y') . ' StudentHub</span><span>Proyek Sistem Jaringan Komputer <i>•</i> Kelompok 4</span></footer></div></body></html>';
}
