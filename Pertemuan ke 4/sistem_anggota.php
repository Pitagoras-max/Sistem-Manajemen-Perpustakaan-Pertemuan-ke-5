<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .card-stat { transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">
    <?php
    // Include functions
    require_once 'functions_anggota.php';
    
    // Data anggota
    $anggota_list = [
        [
            "id" => "AGT-001",
            "nama" => "Budi Santoso",
            "email" => "budi@email.com",
            "telepon" => "081234567890",
            "alamat" => "Jakarta",
            "tanggal_daftar" => "2024-01-15",
            "status" => "Aktif",
            "total_pinjaman" => 5
        ],
        [
            "id" => "AGT-002",
            "nama" => "Siti Aminah",
            "email" => "siti@email.com",
            "telepon" => "082345678901",
            "alamat" => "Bandung",
            "tanggal_daftar" => "2024-02-20",
            "status" => "Aktif",
            "total_pinjaman" => 12
        ],
        [
            "id" => "AGT-003",
            "nama" => "Ahmad Dhani",
            "email" => "ahmad@email.com",
            "telepon" => "083456789012",
            "alamat" => "Surabaya",
            "tanggal_daftar" => "2023-11-10",
            "status" => "Non-Aktif",
            "total_pinjaman" => 2
        ],
        [
            "id" => "AGT-004",
            "nama" => "Rina Nose",
            "email" => "rina@email.com",
            "telepon" => "084567890123",
            "alamat" => "Yogyakarta",
            "tanggal_daftar" => "2024-03-05",
            "status" => "Aktif",
            "total_pinjaman" => 8
        ],
        [
            "id" => "AGT-005",
            "nama" => "Deddy Corbuzier",
            "email" => "deddy@email.com",
            "telepon" => "085678901234",
            "alamat" => "Semarang",
            "tanggal_daftar" => "2023-10-01",
            "status" => "Non-Aktif",
            "total_pinjaman" => 15
        ]
    ];
    
    // Proses Data Statistik menggunakan functions
    $total_anggota = hitung_total_anggota($anggota_list);
    $anggota_aktif = hitung_anggota_aktif($anggota_list);
    $anggota_nonaktif = $total_anggota - $anggota_aktif;
    $rata_rata = hitung_rata_rata_pinjaman($anggota_list);
    $teraktif = cari_anggota_teraktif($anggota_list);
    
    // Menjalankan Search & Sort by Request
    $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'default';
    
    // Terapkan search
    $display_list = search_anggota_by_nama($anggota_list, $search_query);
    
    // Terapkan sort
    if ($sort_by == 'nama_az') {
        $display_list = sort_anggota_by_nama($display_list);
    }
    
    // Split into Aktif and Non-Aktif
    $list_aktif = filter_by_status($display_list, 'Aktif');
    $list_nonaktif = filter_by_status($display_list, 'Non-Aktif');

    // Helper function for rendering table
    function render_table($data) {
        if (empty($data)) {
            echo '<div class="alert alert-warning m-3"><i class="bi bi-exclamation-triangle"></i> Tidak ada data anggota yang ditemukan.</div>';
            return;
        }
        ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>ID Anggota</th>
                        <th>Nama Lengkap</th>
                        <th>Kontak</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Total Pinjaman</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $index => $anggota): ?>
                    <tr>
                        <td class="text-center"><?php echo $index + 1; ?></td>
                        <td class="text-center font-monospace"><span class="badge bg-secondary"><?php echo $anggota['id']; ?></span></td>
                        <td class="fw-bold"><?php echo $anggota['nama']; ?></td>
                        <td>
                            <div>
                                <i class="bi bi-envelope-fill text-muted"></i> <?php echo $anggota['email']; ?>
                                <?php echo validasi_email($anggota['email']) ? '<i class="bi bi-check-circle-fill text-success" title="Email Valid"></i>' : '<i class="bi bi-x-circle-fill text-danger" title="Email Tidak Valid"></i>'; ?>
                            </div>
                            <div class="small"><i class="bi bi-telephone-fill text-muted"></i> <?php echo $anggota['telepon']; ?></div>
                        </td>
                        <td class="text-center"><?php echo format_tanggal_indo($anggota['tanggal_daftar']); ?></td>
                        <td class="text-center">
                            <?php if ($anggota['status'] == 'Aktif'): ?>
                                <span class="badge bg-success rounded-pill px-3"><i class="bi bi-check-circle"></i> <?php echo $anggota['status']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger rounded-pill px-3"><i class="bi bi-x-circle"></i> <?php echo $anggota['status']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center fw-bold text-primary fs-5"><?php echo $anggota['total_pinjaman']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    ?>
    
    <div class="container mt-5 mb-5">
        <h1 class="mb-4 text-center fw-bold text-primary"><i class="bi bi-people"></i> Sistem Anggota Perpustakaan</h1>
        
        <!-- Dashboard Statistik -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card card-stat bg-primary text-white h-100 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-lines-fill fs-1 me-3"></i>
                        <div>
                            <h6 class="card-title mb-1">Total Anggota</h6>
                            <h2 class="mb-0"><?php echo $total_anggota; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-success text-white h-100 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-check fs-1 me-3"></i>
                        <div>
                            <h6 class="card-title mb-1">Anggota Aktif</h6>
                            <h2 class="mb-0"><?php echo $anggota_aktif; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-danger text-white h-100 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-x fs-1 me-3"></i>
                        <div>
                            <h6 class="card-title mb-1">Non-Aktif</h6>
                            <h2 class="mb-0"><?php echo $anggota_nonaktif; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-info text-dark h-100 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-book fs-1 me-3 text-white"></i>
                        <div>
                            <h6 class="card-title mb-1">Rata-rata Pinjam</h6>
                            <h2 class="mb-0"><?php echo $rata_rata; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Anggota Teraktif -->
        <?php if ($teraktif): ?>
        <div class="card mb-4 shadow-sm border-success border-2">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="bi bi-trophy-fill me-2 text-warning fs-5"></i>
                <h5 class="mb-0">Anggota Teraktif</h5>
            </div>
            <div class="card-body p-4 bg-success bg-opacity-10">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 80px; height: 80px; font-size: 35px;">
                            <?php echo substr($teraktif['nama'], 0, 1); ?>
                        </div>
                    </div>
                    <div class="col">
                        <h3 class="mb-1 fw-bold text-success"><?php echo $teraktif['nama']; ?></h3>
                        <p class="text-muted mb-2"><i class="bi bi-person-badge"></i> ID: <?php echo $teraktif['id']; ?> | <i class="bi bi-envelope"></i> <?php echo $teraktif['email']; ?></p>
                        <span class="badge bg-success fs-6"><i class="bi bi-book-half"></i> Total Pinjaman: <?php echo $teraktif['total_pinjaman']; ?> Buku</span>
                        <span class="badge bg-secondary ms-2"><i class="bi bi-calendar-event"></i> Terdaftar sejak: <?php echo format_tanggal_indo($teraktif['tanggal_daftar']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Search and Sort Panel -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body bg-white rounded">
                <form method="GET" action="" class="row g-2 align-items-center justify-content-between">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="search" placeholder="Cari nama anggota..." value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                    </div>
                    <div class="col-md-auto d-flex gap-2">
                        <input type="hidden" name="sort" value="<?php echo $sort_by === 'nama_az' ? 'nama_az' : 'default'; ?>">
                        <?php if ($sort_by === 'nama_az'): ?>
                            <a href="sistem_anggota.php?sort=default<?php echo $search_query ? '&search='.urlencode($search_query) : ''; ?>" class="btn btn-primary"><i class="bi bi-sort-alpha-down"></i> Sortir A-Z (Aktif)</a>
                        <?php else: ?>
                            <a href="sistem_anggota.php?sort=nama_az<?php echo $search_query ? '&search='.urlencode($search_query) : ''; ?>" class="btn btn-outline-primary"><i class="bi bi-sort-alpha-down"></i> Sortir A-Z</a>
                        <?php endif; ?>
                        
                        <button type="submit" class="btn btn-success"><i class="bi bi-funnel"></i> Terapkan</button>

                        <?php if(!empty($search_query) || $sort_by === 'nama_az'): ?>
                            <a href="sistem_anggota.php" class="btn btn-danger"><i class="bi bi-x-circle"></i> Reset Semua</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Semua Anggota -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Data Anggota</h5>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-tabs px-3 pt-3" id="memberTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button" role="tab" aria-controls="semua" aria-selected="true">Semua Data (<?php echo count($display_list); ?>)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-success fw-bold" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif" type="button" role="tab" aria-controls="aktif" aria-selected="false">Anggota Aktif (<?php echo count($list_aktif); ?>)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-danger fw-bold" id="nonaktif-tab" data-bs-toggle="tab" data-bs-target="#nonaktif" type="button" role="tab" aria-controls="nonaktif" aria-selected="false">Anggota Non-Aktif (<?php echo count($list_nonaktif); ?>)</button>
                    </li>
                </ul>

                <div class="tab-content" id="memberTabContent">
                    <!-- Semua Data -->
                    <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
                        <?php render_table($display_list); ?>
                    </div>
                    
                    <!-- Anggota Aktif -->
                    <div class="tab-pane fade" id="aktif" role="tabpanel" aria-labelledby="aktif-tab">
                        <?php render_table($list_aktif); ?>
                    </div>
                    
                    <!-- Anggota Non-Aktif -->
                    <div class="tab-pane fade" id="nonaktif" role="tabpanel" aria-labelledby="nonaktif-tab">
                        <?php render_table($list_nonaktif); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
