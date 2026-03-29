<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perhitungan Diskon - Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sistem Perhitungan Diskon Bertingkat</h1>
        
        <?php
        // Isi data pembeli dan buku di sini
        $nama_pembeli = "Budi Santoso";
        $judul_buku = "Laravel Advanced";
        $harga_satuan = 150000;
        $jumlah_beli = 4;
        $is_member = true; // true atau false
        
        // Hitung subtotal
        $subtotal = $harga_satuan * $jumlah_beli;
        
        // Tentukan persentase diskon berdasarkan jumlah
        $persentase_diskon = 0;
        if ($jumlah_beli > 10) {
            $persentase_diskon = 20;
        } elseif ($jumlah_beli >= 6 && $jumlah_beli <= 10) {
            $persentase_diskon = 15;
        } elseif ($jumlah_beli >= 3 && $jumlah_beli <= 5) {
            $persentase_diskon = 10;
        } else {
            $persentase_diskon = 0;
        }
        
        // Hitung diskon
        $diskon = $subtotal * ($persentase_diskon / 100);
        
        // Total setelah diskon pertama
        $total_setelah_diskon1 = $subtotal - $diskon;
        
        // Hitung diskon member jika member
        $diskon_member = 0;
        if ($is_member) {
            // Diskon member 5% dari total setelah diskon pertama (Sesuai contoh kasus)
            $diskon_member = $total_setelah_diskon1 * 0.05;
        }
        
        // Total setelah semua diskon
        $total_setelah_diskon = $total_setelah_diskon1 - $diskon_member;
        
        // Hitung PPN
        $ppn = $total_setelah_diskon * 0.11;
        
        // Total akhir
        $total_akhir = $total_setelah_diskon + $ppn;
        
        // Total penghematan
        $total_hemat = $diskon + $diskon_member;
        ?>
        
        <!-- Tampilkan hasil perhitungan dengan Bootstrap -->
        <div class="card shadow mb-5">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Rincian Pembelian dan Perhitungan</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th width="35%">Nama Pembeli</th>
                            <td>
                                <?= $nama_pembeli ?> 
                                <?php if ($is_member): ?>
                                    <span class="badge bg-success ms-2">Member</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary ms-2">Non-Member</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Judul Buku</th>
                            <td><?= $judul_buku ?></td>
                        </tr>
                        <tr>
                            <th>Harga Satuan</th>
                            <td>Rp <?= number_format($harga_satuan, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Beli</th>
                            <td><?= $jumlah_beli ?> buku</td>
                        </tr>
                        <tr>
                            <th>Subtotal</th>
                            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                        </tr>
                        
                        <?php if ($persentase_diskon > 0): ?>
                        <tr>
                            <th>Diskon (<?= $persentase_diskon ?>%)</th>
                            <td class="text-danger">- Rp <?= number_format($diskon, 0, ',', '.') ?></td>
                        </tr>
                        <?php endif; ?>

                        <?php if ($is_member): ?>
                        <tr>
                            <th>Diskon Member (5%)</th>
                            <td class="text-danger">
                                - Rp <?= number_format($diskon_member, 0, ',', '.') ?> 
                                <small class="text-muted">(dari Rp <?= number_format($total_setelah_diskon1, 0, ',', '.') ?>)</small>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <tr>
                            <th>Total setelah diskon</th>
                            <td>Rp <?= number_format($total_setelah_diskon, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>PPN (11%)</th>
                            <td>Rp <?= number_format($ppn, 0, ',', '.') ?></td>
                        </tr>
                        <tr class="table-primary">
                            <th><h5 class="mb-0">Total Akhir</h5></th>
                            <td><h5 class="mb-0 fw-bold">Rp <?= number_format($total_akhir, 0, ',', '.') ?></h5></td>
                        </tr>
                        <tr class="table-success">
                            <th>Total Hemat</th>
                            <td><strong class="text-success">Rp <?= number_format($total_hemat, 0, ',', '.') ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
