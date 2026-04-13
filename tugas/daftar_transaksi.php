<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Transaksi Peminjaman</h1>
        
        <?php
        // Hitung statistik dengan loop
        $total_transaksi = 0;
        $total_dipinjam = 0;
        $total_dikembalikan = 0;
        
        // Loop pertama untuk hitung statistik yang ditampilkan saja
        for ($i = 1; $i <= 10; $i++) {
            // Stop di transaksi ke-8 dengan break
            if ($i == 8) {
                break;
            }
            // Skip transaksi genap dengan continue
            if ($i % 2 == 0) {
                continue;
            }
            
            $status = ($i % 3 == 0) ? "Dikembalikan" : "Dipinjam";
            
            $total_transaksi++;
            if ($status == "Dipinjam") {
                $total_dipinjam++;
            } else {
                $total_dikembalikan++;
            }
        }
        ?>
        
        <!-- Tampilkan statistik dalam cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Ditampilkan</h5>
                        <p class="card-text fs-2"><?php echo $total_transaksi; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-dark bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Masih Dipinjam</h5>
                        <p class="card-text fs-2"><?php echo $total_dipinjam; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Sudah Dikembalikan</h5>
                        <p class="card-text fs-2"><?php echo $total_dikembalikan; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tampilkan tabel transaksi -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Hari Terlewat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Loop untuk tampilkan data secara detail
                    for ($i = 1; $i <= 10; $i++) {
                        // Gunakan break untuk stop di transaksi 8
                        if ($i == 8) {
                            break;
                        }
                        
                        // Gunakan continue untuk skip genap
                        if ($i % 2 == 0) {
                            continue;
                        }
                        
                        // Generate data transaksi
                        $id_transaksi = "TRX-" . str_pad($i, 4, "0", STR_PAD_LEFT);
                        $nama_peminjam = "Anggota " . $i;
                        $judul_buku = "Buku Teknologi Vol. " . $i;
                        $tanggal_pinjam = date('Y-m-d', strtotime("-$i days"));
                        $tanggal_kembali = date('Y-m-d', strtotime("+7 days", strtotime($tanggal_pinjam)));
                        $status = ($i % 3 == 0) ? "Dikembalikan" : "Dipinjam";
                        
                        // Hitung jumlah hari sejak pinjam
                        $tgl_pinjam_dt = new DateTime($tanggal_pinjam);
                        $tgl_sekarang_dt = new DateTime(date('Y-m-d'));
                        $selisih = $tgl_pinjam_dt->diff($tgl_sekarang_dt);
                        $jumlah_hari = $selisih->days;

                        // Badge berbeda untuk setiap status
                        $badge_class = ($status == "Dikembalikan") ? "badge bg-success" : "badge bg-warning text-dark";

                        echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>{$id_transaksi}</td>";
                        echo "<td>{$nama_peminjam}</td>";
                        echo "<td>{$judul_buku}</td>";
                        echo "<td>{$tanggal_pinjam}</td>";
                        echo "<td>{$tanggal_kembali}</td>";
                        echo "<td>{$jumlah_hari} Hari</td>";
                        echo "<td><span class='{$badge_class}'>{$status}</span></td>";
                        echo "</tr>";
                        
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
