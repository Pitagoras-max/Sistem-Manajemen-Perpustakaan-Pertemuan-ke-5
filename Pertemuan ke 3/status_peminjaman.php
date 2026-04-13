<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Status Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-book"></i> Sistem Perpustakaan
            </a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1 class="mb-4"><i class="bi bi-person-badge"></i> Sistem Status Peminjaman</h1>
        
        <?php
        // Data Anggota
        $nama_anggota = "Budi Santoso";
        $total_pinjaman = 2;
        $buku_terlambat = 1;
        $hari_keterlambatan = 5; // hari

        // Aturan Business Logic
        $maksimal_pinjam = 3;
        $denda_per_hari = 1000;
        $maksimal_denda = 50000;

        // Menghitung denda dan status menggunakan IF-ELSEIF-ELSE
        $total_denda = 0;
        $peringatan = "";
        $status_peminjaman = "";
        $bisa_pinjam = false;

        if ($buku_terlambat > 0) {
            // Hitung denda
            $total_denda = $buku_terlambat * $hari_keterlambatan * $denda_per_hari;
            
            // Cek maksimal denda
            if ($total_denda > $maksimal_denda) {
                $total_denda = $maksimal_denda;
            }
            
            $bisa_pinjam = false;
            $peringatan = "Anda memiliki $buku_terlambat buku yang terlambat dikembalikan selama $hari_keterlambatan hari!";
            $status_peminjaman = "Tidak bisa meminjam. Silakan kembalikan buku yang terlambat dan lunasi denda Anda terlebih dahulu.";

        } elseif ($total_pinjaman >= $maksimal_pinjam) {
            $bisa_pinjam = false;
            $status_peminjaman = "Tidak bisa meminjam karena Anda sudah mencapai batas maksimal $maksimal_pinjam buku.";

        } else {
            $bisa_pinjam = true;
            $status_peminjaman = "Anda bisa meminjam buku. Sisa kuota peminjaman Anda: " . ($maksimal_pinjam - $total_pinjaman) . " buku.";
        }

        // Menentukan level member menggunakan SWITCH
        $level_member = "";
        $warna_level = "";
        $icon_level = "";

        switch (true) {
            case ($total_pinjaman >= 0 && $total_pinjaman <= 5):
                $level_member = "Bronze";
                $warna_level = "warning"; // Bronze (oranye kecoklatan)
                $icon_level = "award";
                break;
            case ($total_pinjaman >= 6 && $total_pinjaman <= 15):
                $level_member = "Silver";
                $warna_level = "secondary"; // Silver (abu-abu)
                $icon_level = "award-fill";
                break;
            case ($total_pinjaman > 15):
                $level_member = "Gold";
                $warna_level = "warning text-dark"; // Gold Custom
                $icon_level = "trophy-fill";
                break;
            default:
                $level_member = "Bronze";
                $warna_level = "warning";
                $icon_level = "award";
                break;
        }

        // Kalkulasi Persentase Kuota Peminjaman
        $persentase_kuota = ($total_pinjaman / $maksimal_pinjam) * 100;
        ?>
        
        <!-- Dashboard Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h4 class="text-primary text-truncate"><?php echo $nama_anggota; ?></h4>
                        <p class="mb-0">Nama Anggota</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-<?php echo ($level_member == 'Silver' ? 'secondary' : 'warning'); ?>">
                    <div class="card-body text-center">
                        <h4 class="text-<?php echo str_replace(' text-dark', '', $warna_level); ?>">
                            <i class="bi bi-<?php echo $icon_level; ?>"></i> <?php echo $level_member; ?>
                        </h4>
                        <p class="mb-0">Level Member</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <h4 class="text-info"><?php echo $total_pinjaman; ?></h4>
                        <p class="mb-0">Total Pinjaman</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-<?php echo ($buku_terlambat > 0 ? 'danger' : 'success'); ?>">
                    <div class="card-body text-center">
                        <h4 class="text-<?php echo ($buku_terlambat > 0 ? 'danger' : 'success'); ?>"><?php echo $buku_terlambat; ?></h4>
                        <p class="mb-0">Buku Terlambat</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Peminjaman -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Status Peminjaman Saat Ini</h5>
            </div>
            <div class="card-body">
                <?php if ($peringatan != ""): ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Peringatan!</strong> <?php echo $peringatan; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($bisa_pinjam): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill flex-shrink-0 me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Status: Dapat Meminjam</strong><br>
                            <?php echo $status_peminjaman; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-x-circle-fill flex-shrink-0 me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong>Status: Tidak Dapat Meminjam</strong><br>
                            <?php echo $status_peminjaman; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Analisis Detail & Denda -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Analisis Kuota Peminjaman</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Kuota Digunakan</span>
                                <span><strong><?php echo $total_pinjaman; ?> dari <?php echo $maksimal_pinjam; ?> buku</strong></span>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-<?php echo ($total_pinjaman >= $maksimal_pinjam ? 'danger' : 'success'); ?>" 
                                     role="progressbar" 
                                     style="width: <?php echo $persentase_kuota; ?>%;">
                                    <?php echo number_format($persentase_kuota, 0); ?>%
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mt-3">
                            <i class="bi bi-info-circle"></i> Anggota diperbolehkan meminjam maksimal <?php echo $maksimal_pinjam; ?> buku pada waktu bersamaan.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Rincian Denda</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($total_denda > 0): ?>
                            <p><strong>Rincian keterlambatan dan denda saat ini:</strong></p>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Jumlah Buku Terlambat</span>
                                    <span class="badge bg-danger rounded-pill"><?php echo $buku_terlambat; ?> buku</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Lama Keterlambatan</span>
                                    <span class="badge bg-warning text-dark rounded-pill"><?php echo $hari_keterlambatan; ?> hari</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Tarif Denda</span>
                                    <span>Rp <?php echo number_format($denda_per_hari, 0, ',', '.'); ?> / hari / buku</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <strong>Total Denda Dikenakan</strong>
                                    <strong class="text-danger">Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></strong>
                                </li>
                            </ul>
                            <?php if ($total_denda >= $maksimal_denda): ?>
                                <div class="mt-2 text-danger small">
                                    <em>* Denda telah mencapai batas maksimal (Rp <?php echo number_format($maksimal_denda, 0, ',', '.'); ?>).</em>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-emoji-smile text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-success">Tidak Ada Denda</h5>
                                <p class="text-muted">Terima kasih telah mengembalikan buku tepat waktu.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> Sistem Perpustakaan</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
