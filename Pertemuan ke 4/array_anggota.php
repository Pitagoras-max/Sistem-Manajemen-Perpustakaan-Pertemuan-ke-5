<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sistem Manajemen Perpustakaan - Daftar Anggota</h1>
        
        <?php
        // 1. Data multidimensional array anggota perpustakaan
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

        // Variabel untuk menyimpan statistik perhitungan
        $total_anggota = count($anggota_list);
        $anggota_aktif = 0;
        $anggota_nonaktif = 0;
        $total_semua_pinjaman = 0;
        $anggota_teraktif = null;
        $max_pinjaman = -1;

        // Mendapatkan state filter status dari query string (GET parameter)
        $filter_status = isset($_GET['status']) ? $_GET['status'] : 'Semua';

        // 2. Loop untuk memproses perhitungan statistik
        foreach ($anggota_list as $anggota) {
            // Hitung aktif / non-aktif
            if ($anggota['status'] == 'Aktif') {
                $anggota_aktif++;
            } else {
                $anggota_nonaktif++;
            }
            
            // Tambahkan ke total semua pinjaman
            $total_semua_pinjaman += $anggota['total_pinjaman'];
            
            // Cari anggota dengan pinjaman terbanyak
            if ($anggota['total_pinjaman'] > $max_pinjaman) {
                $max_pinjaman = $anggota['total_pinjaman'];
                $anggota_teraktif = $anggota;
            }
        }

        // Kalkulasi persentase dan rata-rata
        $persen_aktif = ($total_anggota > 0) ? round(($anggota_aktif / $total_anggota) * 100, 1) : 0;
        $persen_nonaktif = ($total_anggota > 0) ? round(($anggota_nonaktif / $total_anggota) * 100, 1) : 0;
        $rata_rata_pinjaman = ($total_anggota > 0) ? round($total_semua_pinjaman / $total_anggota, 1) : 0;
        ?>
        
        <!-- 3. Tampilkan statistik dalam cards Bootstrap -->
        <h4 class="mb-3">Statistik Keanggotaan</h4>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Anggota</h5>
                        <p class="card-text fs-1 mb-0"><?php echo $total_anggota; ?></p>
                        <small>Orang</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Anggota Aktif</h5>
                        <p class="card-text fs-1 mb-0"><?php echo $anggota_aktif; ?> <span class="fs-5 opacity-75">(<?php echo $persen_aktif; ?>%)</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Anggota Non-Aktif</h5>
                        <p class="card-text fs-1 mb-0"><?php echo $anggota_nonaktif; ?> <span class="fs-5 opacity-75">(<?php echo $persen_nonaktif; ?>%)</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-dark bg-info bg-opacity-50 border-info mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Rata-rata Pinjaman</h5>
                        <p class="card-text fs-1 mb-0">
                            <?php echo $rata_rata_pinjaman; ?> 
                            <span class="fs-5 text-muted">Buku per Anggota</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-dark bg-warning bg-opacity-50 border-warning mb-3 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Anggota Teraktif</h5>
                        <div class="d-flex align-items-baseline">
                            <p class="card-text fs-3 mb-0 fw-bold me-2"><?php echo $anggota_teraktif['nama']; ?></p>
                            <span class="fs-5 text-muted">(<?php echo $anggota_teraktif['total_pinjaman']; ?> Peminjaman)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 4. Bagian Filter Status Berupa Tombol -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0">Daftar Data Anggota</h4>
                <div>
                    <span class="me-2 fw-medium text-secondary">Filter by Status:</span>
                    <div class="btn-group" role="group" aria-label="Filter status anggota">
                        <a href="?status=Semua" class="btn btn-sm <?php echo $filter_status == 'Semua' ? 'btn-secondary' : 'btn-outline-secondary'; ?>">Semua Data</a>
                        <a href="?status=Aktif" class="btn btn-sm <?php echo $filter_status == 'Aktif' ? 'btn-success' : 'btn-outline-success'; ?>">Hanya Aktif</a>
                        <a href="?status=Non-Aktif" class="btn btn-sm <?php echo $filter_status == 'Non-Aktif' ? 'btn-danger' : 'btn-outline-danger'; ?>">Hanya Non-Aktif</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Tampilkan data ke dalam tabel -->
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead class="table-dark align-middle text-center">
                    <tr>
                        <th>No</th>
                        <th>ID Anggota</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Tgl Daftar</th>
                        <th>Status</th>
                        <th>Total Meminjam</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php
                    $no = 1;
                    $data_ditemukan = false;

                    // Loop untuk mencetak semua data anggota
                    foreach ($anggota_list as $anggota) {
                        
                        // Logika filter status
                        if ($filter_status != 'Semua' && $anggota['status'] != $filter_status) {
                            continue; // Skip jika status tidak sesuai yang dipilih
                        }
                        
                        $data_ditemukan = true;

                        // Pewarnaan Badge Status
                        $badge_class = ($anggota['status'] == "Aktif") ? "badge bg-success" : "badge bg-danger";
                        
                        echo "<tr>";
                        echo "<td class='text-center'>{$no}</td>";
                        echo "<td class='font-monospace text-center'>{$anggota['id']}</td>";
                        echo "<td class='fw-medium'>{$anggota['nama']}</td>";
                        echo "<td>{$anggota['email']}</td>";
                        echo "<td>{$anggota['telepon']}</td>";
                        echo "<td>{$anggota['alamat']}</td>";
                        echo "<td class='text-center'>{$anggota['tanggal_daftar']}</td>";
                        echo "<td class='text-center'><span class='{$badge_class}'>{$anggota['status']}</span></td>";
                        echo "<td class='text-center'>{$anggota['total_pinjaman']} kali</td>";
                        echo "</tr>";
                        
                        $no++;
                    }

                    // Tampilkan pesan kosong jika tidak ada data dari filter tersebut
                    if (!$data_ditemukan) {
                        echo "<tr><td colspan='9' class='text-center py-4 text-muted'>Tidak ada data anggota ditemukan untuk status '{$filter_status}'</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
