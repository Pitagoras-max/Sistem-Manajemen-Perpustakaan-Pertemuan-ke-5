<?php
// Kode ini dibuat dengan bantuan Copilot dan Antigravity buat diajak ngobrol
// sama nyari solusi kalau kodenya ada yang susah atau error

session_start();

// Data buku (minimal 10)
$buku_list = [
    ['kode' => 'B001', 'judul' => 'Belajar PHP Dasar', 'kategori' => 'Programming', 'pengarang' => 'Eko Kurniawan', 'penerbit' => 'Informatika', 'tahun' => 2020, 'harga' => 85000, 'stok' => 15],
    ['kode' => 'B002', 'judul' => 'Mastering Laravel', 'kategori' => 'Programming', 'pengarang' => 'Sandhika Galih', 'penerbit' => 'Erlangga', 'tahun' => 2022, 'harga' => 120000, 'stok' => 5],
    ['kode' => 'B003', 'judul' => 'Desain Web dengan Bootstrap', 'kategori' => 'Web Design', 'pengarang' => 'Budi Raharjo', 'penerbit' => 'Informatika', 'tahun' => 2021, 'harga' => 75000, 'stok' => 0],
    ['kode' => 'B004', 'judul' => 'Jaringan Komputer', 'kategori' => 'Networking', 'pengarang' => 'Iwan Sofana', 'penerbit' => 'Informatika', 'tahun' => 2019, 'harga' => 90000, 'stok' => 10],
    ['kode' => 'B005', 'judul' => 'Sistem Basis Data', 'kategori' => 'Database', 'pengarang' => 'Fathansyah', 'penerbit' => 'Informatika', 'tahun' => 2018, 'harga' => 65000, 'stok' => 20],
    ['kode' => 'B006', 'judul' => 'Panduan MySQL', 'kategori' => 'Database', 'pengarang' => 'Abdul Kadir', 'penerbit' => 'Andi', 'tahun' => 2020, 'harga' => 80000, 'stok' => 0],
    ['kode' => 'B007', 'judul' => 'JavaScript Uncover', 'kategori' => 'Programming', 'pengarang' => 'Andre Pratama', 'penerbit' => 'Duniailkom', 'tahun' => 2021, 'harga' => 100000, 'stok' => 8],
    ['kode' => 'B008', 'judul' => 'Belajar UI/UX', 'kategori' => 'Web Design', 'pengarang' => 'Galih Pratama', 'penerbit' => 'Erlangga', 'tahun' => 2022, 'harga' => 110000, 'stok' => 12],
    ['kode' => 'B009', 'judul' => 'Pemrograman Python', 'kategori' => 'Programming', 'pengarang' => 'Budi Raharjo', 'penerbit' => 'Informatika', 'tahun' => 2023, 'harga' => 130000, 'stok' => 4],
    ['kode' => 'B010', 'judul' => 'Buku Sakti Hacker', 'kategori' => 'Networking', 'pengarang' => 'X-Code', 'penerbit' => 'Andi', 'tahun' => 2018, 'harga' => 150000, 'stok' => 0],
    ['kode' => 'B011', 'judul' => 'React & Node JS', 'kategori' => 'Programming', 'pengarang' => 'Eko Kurniawan', 'penerbit' => 'Informatika', 'tahun' => 2022, 'harga' => 140000, 'stok' => 6],
    ['kode' => 'B012', 'judul' => 'Mahir Cisco', 'kategori' => 'Networking', 'pengarang' => 'Iwan Sofana', 'penerbit' => 'Informatika', 'tahun' => 2021, 'harga' => 95000, 'stok' => 10]
];

// Ambil parameter GET
$keyword = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$min_harga = $_GET['min_harga'] ?? '';
$max_harga = $_GET['max_harga'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$status = $_GET['status'] ?? 'semua';
$sort = $_GET['sort'] ?? 'judul_asc';
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$errors = [];

// Validasi
if ($min_harga !== '' && $max_harga !== '') {
    if ($min_harga > $max_harga) {
        $errors[] = "Harga minimum tidak boleh lebih besar dari harga maksimum";
    }
}
if ($tahun !== '') {
    $current_year = date('Y');
    if ($tahun < 1900 || $tahun > $current_year) {
        $errors[] = "Tahun harus valid (1900 - $current_year)";
    }
}

// ==========================================
// BONUS: Proses recent search ke Session
// ==========================================
if (!empty($keyword)) {
    if(!isset($_SESSION['recent_searches'])) {
        $_SESSION['recent_searches'] = [];
    }
    // Jika keyword belum ada di session, tambahkan ke paling atas
    if(!in_array(strtolower($keyword), array_map('strtolower', $_SESSION['recent_searches']))) {
        array_unshift($_SESSION['recent_searches'], $keyword);
        // Batasi maksimal 5 pencarian terakhir
        $_SESSION['recent_searches'] = array_slice($_SESSION['recent_searches'], 0, 5);
    }
}

// Kosongkan recent search jika di-request
if (isset($_GET['clear_recent'])) {
    unset($_SESSION['recent_searches']);
    // Redirect untuk hilangkan parameter clear_recent dari URL
    header("Location: search_advanced.php");
    exit;
}

// ==========================================
// Filter dan sorting
// ==========================================
$hasil = [];

if (empty($errors)) {
    foreach ($buku_list as $buku) {
        $match = true;

        // Cek Keyword (Judul ATAU Pengarang)
        if ($keyword !== '') {
            $kw = strtolower($keyword);
            $judul_match = strpos(strtolower($buku['judul']), $kw) !== false;
            $pengarang_match = strpos(strtolower($buku['pengarang']), $kw) !== false;
            
            if (!$judul_match && !$pengarang_match) {
                $match = false;
            }
        }

        // Cek Kategori
        if ($kategori !== '' && $buku['kategori'] !== $kategori) {
            $match = false;
        }

        // Cek Harga Range
        if ($min_harga !== '' && $buku['harga'] < $min_harga) $match = false;
        if ($max_harga !== '' && $buku['harga'] > $max_harga) $match = false;

        // Cek Tahun Terbit
        if ($tahun !== '' && $buku['tahun'] != $tahun) $match = false;

        // Cek Status (Radio Stok)
        if ($status === 'tersedia' && $buku['stok'] == 0) $match = false;
        if ($status === 'habis' && $buku['stok'] > 0) $match = false;

        if ($match) {
            $hasil[] = $buku;
        }
    }

    // Eksekusi Sorting (Mengurutkan array multidimensi)
    usort($hasil, function($a, $b) use ($sort) {
        switch ($sort) {
            case 'judul_asc': return strcmp($a['judul'], $b['judul']);
            case 'judul_desc': return strcmp($b['judul'], $a['judul']);
            case 'harga_asc': return $a['harga'] <=> $b['harga'];
            case 'harga_desc': return $b['harga'] <=> $a['harga'];
            case 'tahun_asc': return $a['tahun'] <=> $b['tahun'];
            case 'tahun_desc': return $b['tahun'] <=> $a['tahun'];
            default: return strcmp($a['judul'], $b['judul']);
        }
    });
}

// ==========================================
// BONUS: Export Data Hasil Ke CSV
// ==========================================
if (isset($_GET['export_csv']) && $_GET['export_csv'] == '1' && empty($errors)) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="hasil_pencarian_buku.csv"');
    $output = fopen('php://output', 'w');
    // Header Kolom CSV
    fputcsv($output, ['Kode', 'Judul', 'Kategori', 'Pengarang', 'Penerbit', 'Tahun', 'Harga', 'Stok']);
    foreach ($hasil as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// ==========================================
// Menyiapkan Pagination (10 data per page)
// ==========================================
$per_page = 10;
$total_items = count($hasil);
$total_pages = max(ceil($total_items / $per_page), 1);
if ($page > $total_pages) $page = $total_pages;

$offset = ($page - 1) * $per_page;
$paged_hasil = array_slice($hasil, $offset, $per_page);

// ==========================================
// BONUS: Helper Fungsi Highlight Keyword
// ==========================================
function highlightKeyword($text, $keyword) {
    if (empty($keyword)) return htmlspecialchars($text);
    // Escape keyword supaya aman dari regex injection
    $escaped_keyword = preg_quote($keyword, '/');
    // Replace text array dengan <mark> text </mark> secara case-insensitive
    return preg_replace("/($escaped_keyword)/i", "<mark class='bg-warning'>$1</mark>", htmlspecialchars($text));
}

// Helper untuk menyusun URL Parameter saat berganti halaman pagination
function getQueryString($exclude = []) {
    $params = $_GET;
    foreach ($exclude as $key) {
        unset($params[$key]);
    }
    return http_build_query($params);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Search Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5 mb-5">
        <h2 class="mb-4"><i class="bi bi-search"></i> Pencarian Buku Lengkap</h2>
        
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- KOTAK FORM PENCARIAN -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Filter Pencarian</h5>
            </div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-bold">Keyword (Judul/Pengarang)</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword) ?>" placeholder="Kata kunci...">
                            
                            <!-- Menampilkan Rekaman Histori Session -->
                            <?php if(!empty($_SESSION['recent_searches'])): ?>
                            <div class="mt-1 small">
                                <span class="text-muted">Pencarian terakhir:</span>
                                <?php foreach($_SESSION['recent_searches'] as $rs): ?>
                                    <a href="?keyword=<?= urlencode($rs) ?>" class="badge bg-secondary text-decoration-none"><?= htmlspecialchars($rs) ?></a>
                                <?php endforeach; ?>
                                <a href="?clear_recent=1" class="text-danger text-decoration-none ms-1" title="Hapus histori">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                <option value="Programming" <?= $kategori == 'Programming' ? 'selected' : '' ?>>Programming</option>
                                <option value="Web Design" <?= $kategori == 'Web Design' ? 'selected' : '' ?>>Web Design</option>
                                <option value="Database" <?= $kategori == 'Database' ? 'selected' : '' ?>>Database</option>
                                <option value="Networking" <?= $kategori == 'Networking' ? 'selected' : '' ?>>Networking</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted fw-bold">Tahun Terbit</label>
                            <input type="number" name="tahun" class="form-control" value="<?= htmlspecialchars($tahun) ?>" min="1900" max="<?= date('Y') ?>" placeholder="Misal: 2021">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold">Status Ketersediaan</label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="sts_semua" value="semua" <?= $status == 'semua' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sts_semua">Semua</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="sts_ada" value="tersedia" <?= $status == 'tersedia' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sts_ada">Tersedia</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="sts_habis" value="habis" <?= $status == 'habis' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="sts_habis">Habis</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-1 border-top pt-2">
                        <div class="col-md-3">
                            <label class="form-label">Min Harga (Rp)</label>
                            <input type="number" name="min_harga" class="form-control" value="<?= htmlspecialchars($min_harga) ?>" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Max Harga (Rp)</label>
                            <input type="number" name="max_harga" class="form-control" value="<?= htmlspecialchars($max_harga) ?>" placeholder="200000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Urutkan Berdasarkan</label>
                            <select name="sort" class="form-select">
                                <option value="judul_asc" <?= $sort == 'judul_asc' ? 'selected' : '' ?>>Judul (A-Z)</option>
                                <option value="judul_desc" <?= $sort == 'judul_desc' ? 'selected' : '' ?>>Judul (Z-A)</option>
                                <option value="harga_asc" <?= $sort == 'harga_asc' ? 'selected' : '' ?>>Harga (Termurah)</option>
                                <option value="harga_desc" <?= $sort == 'harga_desc' ? 'selected' : '' ?>>Harga (Termahal)</option>
                                <option value="tahun_desc" <?= $sort == 'tahun_desc' ? 'selected' : '' ?>>Tahun (Terbaru)</option>
                                <option value="tahun_asc" <?= $sort == 'tahun_asc' ? 'selected' : '' ?>>Tahun (Terlama)</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="search_advanced.php" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($errors)): ?>
        <!-- KOTAK HASIL PENCARIAN -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 text-secondary">
                    Menampilkan <strong class="text-dark"><?= count($paged_hasil) ?></strong> data dari <strong class="text-dark"><?= $total_items ?></strong> buku yang ditemukan
                </h6>
                <?php if ($total_items > 0): ?>
                    <?php 
                    // Link export dengan membawa seluruh parameter query saat ini
                    $export_query = getQueryString(['export_csv']);
                    $export_link = "?" . $export_query . ($export_query ? "&" : "") . "export_csv=1";
                    ?>
                    <a href="<?= $export_link ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Export CSV
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Harga</th>
                                <th class="text-center">Status / Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($paged_hasil) > 0): ?>
                                <?php foreach($paged_hasil as $row): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= $row['kode'] ?></span></td>
                                    <!-- Judul dan pengarang menggunakan class highlight Keyword -->
                                    <td class="fw-bold"><?= highlightKeyword($row['judul'], $keyword) ?></td>
                                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                                    <td><?= highlightKeyword($row['pengarang'], $keyword) ?></td>
                                    <td><?= htmlspecialchars($row['penerbit']) ?></td>
                                    <td><?= $row['tahun'] ?></td>
                                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php if ($row['stok'] > 0): ?>
                                            <span class="badge bg-success rounded-pill px-3 py-2"><?= $row['stok'] ?> Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger rounded-pill px-3 py-2">Habis</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-journal-x" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Silakan ubah kriteria filter untuk menemukan buku.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Navigasi Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="card-footer bg-white py-3 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <?php 
                        // Query parameters saat ini tanpa 'page'
                        $base_query = getQueryString(['page']);
                        $link_prefix = "?" . $base_query . ($base_query ? "&" : "");
                        ?>
                        
                        <!-- Tombol Prev -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $link_prefix . 'page=' . ($page - 1) ?>">Sebelumnya</a>
                        </li>
                        
                        <!-- Angka Hal -->
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $link_prefix . 'page=' . $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Tombol Next -->
                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $link_prefix . 'page=' . ($page + 1) ?>">Selanjutnya</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
